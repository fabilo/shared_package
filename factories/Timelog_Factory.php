<?php
class Timelog_Factory extends Base_PDO_Factory { 
	public static $_table_name = 'timelogs';
	public static $_fetch_class = 'Timelog';
	protected $_user_id;
	
	public function __construct($db, $user_id) {
		parent::__construct($db);
		$this->_user_id = $user_id;
		if (!$this->_user_id) throw new Exception('invalid user id for timelog factory');
	}
	
	private static function selectString() {
		$sql =  'SELECT '.self::$_table_name.'.*, '.Project_Factory::$_table_name.'.name AS project_name, '.Timelog_Categories_Factory::$_table_name.'.name AS category_name ';
		$sql .= ' FROM '.self::$_table_name.' ';
		$sql .= ' LEFT JOIN '.Project_Factory::$_table_name.' ON ('.Project_Factory::$_table_name.'.id = '.self::$_table_name.'.project_id) ';
		$sql .= ' LEFT JOIN '.Timelog_Categories_Factory::$_table_name.' ON ('.Timelog_Categories_Factory::$_table_name.'.id = '.self::$_table_name.'.category_id) ';
		return $sql;
	}
	
	public function getById($id) {
		$smt = $this->_db->prepare(
			self::selectString().'
			WHERE '.self::$_table_name.'.id = ?'
		);
		$smt->execute(array($id));
		if (!$timelog = $smt->fetchObject(self::$_fetch_class)) {
			throw new Exception('Timelog not found.');
		}
		
		// check user owns timelog
		if ($timelog->getUserId() != $this->_user_id) throw new Exception('Permission denied to access timelog object with id: '.$id);
		
		return $timelog;
	}
	
	public function get() {
		$smt = $this->_db->prepare('SELECT * FROM '.self::$_table_name.' WHERE user_id = ?');
		$smt->execute(array($this->_user_id));
		return $smt->fetchAll(PDO::FETCH_CLASS, self::$_fetch_class);
	}
	
	/** insert timelog object into db.
	 *
	 *	@var $obj - timelog object to insert into databse. Assumes obj is pre-validated. 
	 *	@return id of timelog record once inserted into db.
	 */
	public function insert(Timelog $obj) {
		// convert obj to array so we can process both
		// if (is_object($obj)) $obj = (array) $obj;
		
		$smt = $this->_db->prepare(
			"INSERT INTO ".self::$_table_name." (`date`, `start_time`, `end_time`, `hours`, `user_id`, `category_id`, `project_id`, `notes`, `created_ts`, `modified_ts`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)"
		);
		
		$smt->execute(array(
			$obj->date, $obj->start_time, $obj->end_time, $obj->hours, $obj->user_id, $obj->category_id, $obj->project_id, $obj->notes
		));
		
		// return timelog id
		return (int) $this->_db->lastInsertId();
	}
	
	/** update timelog record with given timelog object
	 *
	 *	@var $obj - Timelog object to save to update in db. Assumes obj is pre-validated. 
	 *  @return result of udate query.
	 */
	public function update(Timelog $obj) {
		$smt = $this->_db->prepare(
			"UPDATE ".self::$_table_name." SET `date` = ?, `start_time` = ?, `end_time` = ?, `hours` = ?, `category_id` = ?, `project_id` = ?, `notes` = ?, modified_ts = CURRENT_TIMESTAMP WHERE id = ? and user_id = ?"
		);
		
		return $smt->execute(array(
			$obj->date, $obj->start_time, $obj->end_time, $obj->hours, $obj->category_id, $obj->project_id, $obj->notes, $obj->id, $this->_user_id
		));
	}
	
	/** Get timelog totals for days across a timeframe
	 *
	 *	Columns to return: 
	 *	- date
	 *	- start_time: earliest start_time for a timelog for that day
	 *	- end-time: latest end_time for timelog for that day
	 *	- hours: sum of hours of all timelogs for that day
	 *
	 *	@var $start_date - start of date range to return timelog records for
	 *	@var $end_date - end of date range to return timelog records for
	 *	@var $user_id - id of user to return records for - defaults to null
	 */
	public function getDayTotalsByDateRange($start_date, $end_date, $user_id=null) {
		// default user_id to current user
		if (!$user_id) $user_id = $this->_user_id;
		
		// prepare query
		$smt = $this->_db->prepare("
			SELECT date, MIN(start_time) AS start_time, MAX(end_time) AS end_time, SUM(hours) AS hours, 
			 CONCAT(YEAR(MIN(date)),'-',WEEK(MIN(date))) AS year_week
			FROM ".self::$_table_name."
			WHERE user_id = ? AND date >= ? AND date <= ?
			GROUP BY date
			ORDER BY date DESC
		");
			
		$smt->execute(array($user_id, $start_date, $end_date));
		return $smt->fetchAll(PDO::FETCH_CLASS, self::$_fetch_class);
	}
	
	/** 
	 * Get timelogs for a date
	 */
	public function getForDate($date, $user_id=null) {
		// default user_id to current user
		if (!$user_id) $user_id = $this->_user_id;
		
		// prepare query
		$smt = $this->_db->prepare(
			self::selectString()."
			WHERE user_id = ? AND date = ?
			ORDER BY start_time DESC
		");
			
		$smt->execute(array($user_id, $date));
		return $smt->fetchAll(PDO::FETCH_CLASS, self::$_fetch_class);
	}
	
	/**
	 *  Delete timelog
	 *	@var $id int - id for timelog record
	 */
	public function delete($id) {
		// try get timelog - restricted by user
		$timelog = $this->getById($id); 
		
		// delete timelog 
		$smt = $this->_db->prepare("DELETE FROM ".self::$_table_name." WHERE id = ?");
		return $smt->execute(array($id));
	}
	
	/**
	 * Get total amount of hours for a week
	 *	@param int $start_week - first week to return hours for
	 *	@param int $end_week - (optional) end of range to return total hours for weeks
	 *	@return array of timelog objects 
	 */
	public function getTotalHoursForWeek($year, $start_week, $end_week=null) {
		// query params 
		$params = array(
			$this->_user_id, 
			$year
		);
		
		// check if end_week argument was passed
		if ($end_week) {
			// end week argument was passed 
			$where = " (YEAR(date) = ? AND DATE_FORMAT(date, '%v') >= ? AND DATE_FORMAT(date, '%v') <= ?) ";
			// add to query params array
			$params[]= (int) $start_week; 
			$params[]= (int) $end_week;
		}
		else {
			// no end_week paramter value, 
			$where = " (YEAR(date) = ? AND DATE_FORMAT(date, '%v') = ?) ";
			// add to query params array 
			$params[]= (int) $start_week;
		}
		
		// prepare query
		$smt = $this->_db->prepare("
			SELECT SUM(hours) AS hours 
			FROM ".self::$_table_name."
			WHERE user_id = ? 
			AND ".$where."
			GROUP BY DATE_FORMAT(date, '%v')
			ORDER BY date DESC
		");
			
		$smt->execute($params);
		return $smt->fetchColumn();
	}
	
	/**
	 * 
	 */
	public function getDayTotalsByWeek($week, $year=null) {
		// default year to now
		if (!$year) $year = date('Y');
		$time = strtotime("1 January $year", time());
		
		// get start day & end day for week
		$time += ((7*$week))*24*3600;
		$end_date = date('Y-m-d', $time);
		$start_date = date('Y-m-d', ($time-(6*86400)));		
		
		$result = $this->getDayTotalsByDateRange($start_date, $end_date);
		
		die(print_r($result));
	}
	
	/**
	 * Get total hours per day for each project clarity name
	 *	@param int year - year to get timelogs for
	 *	@param int week - week to get timelogs for
	 *	@return array of rowsets for the result
	 */
	public function getCategoryHoursForClarityPerDay($year, $week) {
		$q = "
			SELECT t.date, SUM(t.hours) AS hours, IFNULL(p.clarity_reference, IFNULL(p.name, IFNULL(c.clarity_reference, c.name))) AS category
			FROM ".self::$_table_name." t
			LEFT JOIN ".Project_Factory::$_table_name." p ON p.id = t.project_id
			LEFT JOIN ".Timelog_Categories_Factory::$_table_name." c ON c.id = t.category_id
			WHERE user_id = ?
			AND YEAR(date) = ?
			AND WEEK(date) = ?
			GROUP BY date, category
			ORDER BY date, category";	
		$smt = $this->_db->prepare($q);		
		$smt->execute(array(
			$this->_user_id,
			$year, 
			$week
		));
		return $smt->fetchAll();		
	}

	/**
	 * Get total hours per day for each timelog category
	 *	@param int year - year to get timelogs for
	 *	@param int week - week to get timelogs for
	 *	@return array of rowsets for the result
	 */
	public function getCategoryHoursPerDay($year, $week) {
		$q = "
			SELECT t.date, SUM(t.hours) AS hours, c.name AS category
			FROM ".self::$_table_name." t
			LEFT JOIN ".Timelog_Categories_Factory::$_table_name." c ON c.id = t.category_id
			WHERE user_id = ?
			AND YEAR(date) = ?
			AND WEEK(date) = ?
			GROUP BY date, category
			ORDER BY date, category";	
		$smt = $this->_db->prepare($q);		
		$smt->execute(array(
			$this->_user_id,
			$year, 
			$week
		));
		return $smt->fetchAll();
	}
	
	/**
	 * Get total hours per day
	 *	@param int year - year to get timelogs for
	 *	@param int week - week to get timelogs for
	 *	@return array of rowsets for the result
	 */
	public function getTotalHoursPerDay($year, $week) {
		$smt = $this->_db->prepare("
			SELECT timelogs.date, SUM(timelogs.hours) AS hours
			FROM timelogs
			WHERE user_id = ?
			AND YEAR(date) = ?
			AND WEEK(date) = ?
			GROUP BY date
			ORDER BY date
		");
		$smt->execute(array(
			$this->_user_id,
			$year, 
			$week
		));
		return $smt->fetchAll();		
	}
}



