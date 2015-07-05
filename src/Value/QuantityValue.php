<?php namespace Wikidata\Value;

class QuantityValue {
	
	/**
	 * Class constructor
	 * @param object $value StdClass object with quantity value
	 */
	public function __construct($value) {

		$this->amount = $value->amount;
		$this->unit = $value->unit;
		$this->upperBound = $value->upperBound;
		$this->lowerBound = $value->lowerBound;

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