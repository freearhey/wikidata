<?php namespace Wikidata\Property;

class PropertyQualifier {
	
	/**
	 * Class constructor
	 * @param object $qualifier StdClass object with qualifier
	 */
	public function __construct($qualifier) {

		$this->hash = $qualifier->hash;
		$this->snaktype = $qualifier->snaktype;
		$this->property = $qualifier->property;
		$this->datatype = $qualifier->datatype;
		$this->datavalue = new PropertyDatavalue($qualifier->datavalue);

	}

	/**
	 * Get property datavalue
	 * @return object /Property/PropertyDatavalue
	 */
	public function getDatavalue() {

		return $this->datavalue;

	}

}