<?php namespace Wikidata\Value;

class TimeValue {
	
	/**
	 * Class constructor
	 * @param object $value StdClass object with time value
	 */
	public function __construct($value) {

		$this->time = $value->time;
		$this->timezone = $value->timezone;
		$this->before = $value->before;
		$this->after = $value->after;
		$this->precision = $value->precision;
		$this->calendarmodel = $value->calendarmodel;

	}

	/**
	 * Get value
	 * @return object Return object with all data
	 *
	 * TODO: prepare value to display
	 */
	public function getValue() {

		return $this;

	}

}