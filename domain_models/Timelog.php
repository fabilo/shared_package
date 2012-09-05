<?php
/** Timelog
 *	@Description: store a unit of time for work on a project/category
 */
class Timelog extends AbstractEntity {
	protected $date, 
	 	$start_time, 
		$end_time, 
		$hours,
		$user_id,
		$category_id, 
		$project_id, 
		$notes, 
		$project_name, 
		$category_name;
		
	/** check object is valid for saving
	 *
	 */
	public function validate() {
		// date is required
		if (!$this->date) throw new Exception('Invalid date');
		// start time is required
		if (!$this->start_time) throw new Exception('Invalid start time');
		// validate relative start and end times
		if ($this->end_time && ($this->start_time > $this->end_time)) throw new Exception('Start time must be before end time');
		
		// calculate hours value
		if ($this->start_time && $this->end_time) {
			// calculate hours by using the difference between timestamps
			$this->hours = ($this->getTimestampFromTime($this->end_time) - $this->getTimestampFromTime($this->start_time))/3600;
		}
		
		$this->modified_ts = date('Y-m-d H:i:s');
		return true;
	}
	
	private function getTimestampFromTime($time) {
		$hrs = substr($time, 0, strlen($time)-2);
		$mins = substr($time, -2);
		return mktime((float)$hrs, (float)$mins);
	}
		
	public function setStartTime($time) {
		$this->start_time = $this->sanatizeTime($time);
	}
	
	public function setEndTime($time) {
		$this->end_time = $this->sanatizeTime($time);
	}
	
	public function getStartTimeNice() {
		if (!$this->start_time) return null;
		
		// zero pad time and add semicolon
		$time = sprintf('%02s',substr($this->start_time, 0, -2));
		$time .= ':';
		$time .= sprintf('%02s',substr($this->start_time, -2));
		return $time;
	}
	
	
	public function getEndTimeNice() {
		if (!$this->end_time) return null;
		
		// zero pad time and add semicolon
		$time = sprintf('%02s',substr($this->end_time, 0, -2));
		$time .= ':';
		$time .= sprintf('%02s',substr($this->end_time, -2));
		return $time;
	}
		
	public function setNotes($notes) {
		$this->notes = $this->sanitizeString($notes);
		
		return $this;
	}
	
	/** setDate
	 *	set date for timelog. 
	 *
	 *	date parameter must be format yyyy-mm-dd
	 *	@var date: date to be set for timelog's date
	 */ 
	public function setDate($date) {
		// validate date format to yyyy-mm-dd
		$date = trim($date);
		if (!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $date)) {
			throw new Invalid_Argument_Exception(
          "Trying to set invalid date (".$date.") for Timelog.");
		}
		
		$this->date = $date;
		
		return $this;
	}
	
	
	public function getNotesExtract($limit=50) {
		if (strlen($this->notes) > $limit) {
			// add span 
			return '<span class="truncated" id="notes-'.$this->id.'">'.substr($this->notes, 0, $limit).'...</span>';
		}
		return $this->notes;
	}
}