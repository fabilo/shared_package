<?php
class Timesheet extends Base_Render_Library {
	protected $_timelog_factory, 
		$_project_factory, 
		$_timelog_categories_factory, 
		$_user;
	
	public function __construct($timelog_factory, $project_factory, $timelog_categories_factory, $user) {
		$this->_timelog_factory = $timelog_factory;
		$this->_project_factory = $project_factory;
		$this->_timelog_categories_factory = $timelog_categories_factory;
		$this->_user = $user;
	}

	/**
	 *	Get HTML table row of total hours for a week
	 *	@param int $week - Week to get timelog totals for
	 *	@param int $year - Year to get timelog totals for
	 */
	public function getTotalHoursTableRowHtmlForWeek($week, $year=null, $expand_first_row=false) {
		// default year to this year if not passed
		if (!$year) $year = Date('Y');
		
		// get start and end dates for week
		$week_range_dates = self::getRangeDatesForWeek($week, $year);
		
		// get total hours for week html
		$week_hours = $this->_timelog_factory->getTotalHoursForWeek($year, $week);
		$html = $this->renderView('timelog/week_table_row', array(
			'year'=>$year,
			'week'=>$week, 
			'week_hours'=>$week_hours, 
			'start_date' => $week_range_dates['start_date'], 
			'end_date' => $week_range_dates['end_date']
		));
		
		// now loop days between start & end dates
		$first_row = $expand_first_row; // first row displayed - used to expand the first day's timelogs if required
		for ($i=strtotime($week_range_dates['end_date']); $i>=strtotime($week_range_dates['start_date']); $i-=(24*3600)) {
			if ($day_html = $this->getTotalHoursTableRowHtmlForDate(date('Y-m-d',$i), $first_row)) {
				// row returned, overwrite flag
				$first_row = false;
				$html .= $day_html;
			}
		}
		
		return $this->renderView('timelog/list_table', array('html'=>$html));
	}
	
	/**
	 *	Get html table row of total hours for a day
	 *	@param date $date - date to get hours total for
	 *	@param bool $expand_timelogs - whether to append timelogs html for the date 	 
	 */
	public function getTotalHoursTableRowHtmlForDate($date, $expand_timelogs=false) {
		if ($day = array_shift($this->_timelog_factory->getDayTotalsByDateRange($date, $date))) {
			$html = $this->renderView('timelog/day_table_row', array('day'=>$day, 'expand_timelogs'=>$expand_timelogs));
	
			if ($expand_timelogs) {
				$html .= $this->getTimelogsTableRowHtmlForDate($date);
			}
			return $html;
		}
	}

	/**
	 * 	Get html table rows of timelogs for a date
	 *	@param date $date - date to get timelogs for
	 */
	public function getTimelogsTableRowHtmlForDate($date) {
		if ($timelogs = $this->_timelog_factory->getForDate($date)) {
			return $this->renderView('timelog/timelog_table_rows', array('timelogs'=>$timelogs));
		}
	}
	
	/**
	 *	Get Html form for a timelog
	 *	@param Timelog $timelog - timelog to be displayed in the form
	 *	@param array $data - variables to be 
	 */
	public function getTimelogFormHtml($timelog, $data=array()) {
		// setup form render variables
		$data = array_merge($this->_view_globals, $data);
		$data['timelog'] = $timelog;
		$data['projects'] = $this->_user->getVisibleProjects($this->_project_factory);
		$data['categories'] = $this->_user->getVisibleTimelogCategories($this->_timelog_categories_factory);
		return $this->renderView('timelog/timelog_form', $data);
	}

	/**
	 *	Get array of start and end dates for a week
	 *	@param int $week - week to return dates for
	 *	@return array including the range dates for the week
	 *		array['start_date'], and array['end_date']
	 */
	public static function getRangeDatesForWeek($week, $year) {
		$return = array(); 

		// calc start & end timestamps for individual day html
		$end_time = mktime(0,0,0,1,1,$year)+($week*7*24*3600);
		$start_time = $end_time - (6*24*3600);

		$return['end_date'] = date('Y-m-d', $end_time);
		$return['start_date'] = date('Y-m-d', $start_time);
		return $return;
	}
	
	public static function getDatesForWeek($week, $year) {
		$dates = array();
		$date_range = self::getRangeDatesForWeek($week, $year);
		// init cur_date for looping days and building days in week
		$cur_date = $date_range['start_date'];
		while ($cur_date <= $date_range['end_date']) {
			// add current date to columns arr
			$dates[]= $cur_date; 
			// increment cur_date by a day
			$cur_date = date('Y-m-d', strtotime($cur_date.' +1 day'));
		}
		return $dates;
	}
}