<?php namespace Wikidata\Property;

class PropertySnak {
	
	/**
	 * Class constructor
	 * @param object $snak StdClass object with snak
	 */
	public function __construct($snak) {

		$this->snaktype = $snak->snaktype;
		$this->property = $snak->property;
		$this->datatype = $snak->datatype;
		$this->datavalue = new PropertyDatavalue($snak->datavalue);

	}

	/**
	 * Get only datavalue of snak
	 * @return object /Property/PropertyDatavalue
	 */
	public function getDatavalue() {

		return $this->datavalue;

	}

}