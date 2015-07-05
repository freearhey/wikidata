<?php namespace Wikidata\Entity;

class EntityValue {
	
	/**
	 * Class constructor
	 * @param object $value StdClass object
	 */
	public function __construct($value) {

		$this->language = $value->language;
		$this->value = $value->value;

	}

	/**
	 * Get only value without language
	 * @return string
	 */
	public function getValue() {

		return $this->value;

	}

}