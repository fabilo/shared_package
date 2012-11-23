<?php
class Timelog_Report extends Base_Render_Library {
	protected $_timelog_factory;
	
	public function __construct($timelog_factory) {
		$this->_timelog_factory = $timelog_factory;
	}
	
	public function getClarityReportForWeekHtml($week, $year) {
			// get data
			$timelogs = $this->_timelog_factory->getCategoryHoursForClarityPerDay($year, $week);

			// setup columns - each day of week
			$columns = array('Category');
			// get start & end dates for week
			$columns = array_merge($columns, Timesheet::getDatesForWeek($week, $year));
			// append total column
			$columns[]= 'Total';
			// append percentage column
			$columns[]= '%';

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
			$totals['Total'] = 0;

			foreach ($this->_timelog_factory->getTotalHoursPerDay($year, $week) AS $day) {
				$totals['Total'] += $totals[$day['date']] = $day['hours'];
			}

			// caculate category percentage loading
			foreach ($rows AS $categoryName => $data) {
				$rows[$categoryName]['%'] = sprintf("%01.1f%%", ($rows[$categoryName]['Total'] / $totals['Total'])*100);
			}
			
			return $this->renderView('timelog/reports/basic_table', array(
				'columns' => $columns, 
				'rows' => $rows, 
				'totals' => $totals
			));
	}

	public function getCategoryReportForWeekHtml($week, $year) {
			// get data
			$timelogs = $this->_timelog_factory->getCategoryHoursPerDay($year, $week);

			// setup columns - each day of week
			$columns = array('Category');
			// get start & end dates for week
			$columns = array_merge($columns, Timesheet::getDatesForWeek($week, $year));
			// append total column
			$columns[]= 'Total';
			// append percentage column
			$columns[]= '%';

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
			$totals['Total'] = 0;
			foreach ($this->_timelog_factory->getTotalHoursPerDay($year, $week) AS $day) {
				$totals['Total'] += $totals[$day['date']] = $day['hours'];
			}

			// caculate category percentage loading
			foreach ($rows AS $categoryName => $data) {
				$rows[$categoryName]['%'] = sprintf("%01.1f%%", ($rows[$categoryName]['Total'] / $totals['Total'])*100);
			}
			
			return $this->renderView('timelog/reports/basic_table', array(
				'columns' => $columns, 
				'rows' => $rows, 
				'totals' => $totals
			));
	}	
}