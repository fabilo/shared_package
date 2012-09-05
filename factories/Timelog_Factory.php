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
		$timelog = $smt->fetchObject(self::$_fetch_class);
		
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
			SELECT date, MIN(start_time) AS start_time, MAX(end_time) AS end_time, SUM(hours) AS hours 
			FROM ".self::$_table_name."
			WHERE user_id = ? AND date >= ? AND date <= ?
			GROUP BY date
			ORDER BY date DESC
		");
			
		$smt->execute(array($user_id, $start_date, $end_date));
		return $smt->fetchAll(PDO::FETCH_CLASS, self::$_fetch_class);
	}
	
	/** Get timelogs for a date
	 *
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
}