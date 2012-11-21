<?php
class Timelog_Report extends Base_Render_Library {
	protected $_timelog_factory;
	
	public function __construct($timelog_factory) {
		$this->_timelog_factory = $timelog_factory;
	}
	
	public function getClarityReportForWeekHtml($week, $year) {
			// get data
			$timelogs = $this->_timelog_factory->getCategoryHoursForClarity($year, $week);

			// setup columns - each day of week
			$columns = array('Category');
			// get start & end dates for week
			$columns = array_merge($columns, Timesheet::getDatesForWeek($week, $year));
			// add total column to end
			$columns[]= 'Total';

			// format data into rows array
			$rows = array();
			// loop each timelog record
			foreach ($timelogs AS $timelog) {
				// check if row (timelog category) exists
				if (!array_key_exists($timelog['category'], $rows)) $rows[$timelog['category']] = array('Category'=>$timelog['category']);
				// insert hours for day & category
				$rows[$timelog['category']][$timelog['date']] = $timelog['hours'];
				// add to total column
				@$rows[$timelog['category']]['Total'] += $timelog['hours'];
			}
			// sort rows array by key
			ksort($rows); 

			// setup totals
			$totals = array_fill_keys(Timesheet::getDatesForWeek($week, $year),0);
			$totals['Category'] = 'Totals:';

			foreach ($this->_timelog_factory->getTotalHoursPerDay($year, $week) AS $day) {
				$totals[$day['date']] = $day['hours'];
			}
			
			return $this->renderView('timelog/reports/clarity', array(
				'columns' => $columns, 
				'rows' => $rows, 
				'totals' => $totals
			));
	}
}