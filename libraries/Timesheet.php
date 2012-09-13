<?php
class Timesheet {
	protected $_timelog_factory;
	public $_view_globals;
	
	public function __construct($timelog_factory) {
		$this->_timelog_factory = $timelog_factory;
	}

	public function getHtmlForWeek($week, $year=null) {
		// default year to this year if not passed
		if (!$year) $year = Date('Y');
		
		// get total hours for week html
		$week_hours = $this->_timelog_factory->getTotalHoursForWeek($week);
		$html = $this->renderView('timelog/week_table_row', array('week'=>$week, 'week_hours'=>$week_hours));
		
		// calc start & end timestamps for individual day html
		$end_time = mktime(0,0,0,1,1,$year)+($week*7*24*3600);
		$start_time = $end_time - (6*24*3600);

		$end_date = date('Y-m-d', $end_time);
		$start_date = date('Y-m-d', $start_time);
		
		// now loop days between start & end dates
		for ($i=$start_time; $i<=$end_time; $i+=(24*3600)) {
			$html .= $this->getTimelogsHtmlForDate(date('Y-m-d',$i));
		}
		
		$html = $this->renderView('timelog/list_table', array('html'=>$html));

		return $html;
	}

	public function getTimelogsHtmlForDate($date) {
		$timelogs = $this->_timelog_factory->getForDate($date);
		return $this->renderView('timelog/timelog_table_rows', array('timelogs'=>$timelogs));
	}
	
	public function getHtmlForDate($date, $expand_timelogs=false) {
		$day = array_shift($this->_timelog_factory->getDayTotalsByDateRange($date, $date));
		$html = $this->renderView('timelog/day_table_row', array('day' => $day));
		
		if ($expand_timelogs) {
			$html .= $this->getTimelogsHtmlForDate($date);
		}
		return $html;
	}
	
	protected function renderView($view_template, $data) {
		extract($data);
		extract($this->_view_globals);
		ob_start();
		include 'views/'.$view_template.'.php';
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
}