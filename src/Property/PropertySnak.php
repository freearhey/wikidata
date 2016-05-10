<?php namespace Wikidata\Property;

use Wikidata\Exception\PropertySnakHaveNoValue;
use Wikidata\Property\NullPropertyDatavalue;

class PropertySnak {
	
	/**
	 * Class constructor
	 * @param object $snak StdClass object with snak
	 */
	public function __construct($snak) {

		$this->snaktype = $snak->snaktype;
		$this->property = $snak->property;
		$this->datatype = $snak->datatype;
		$this->datavalue = ($snak->snaktype == 'value') ? new PropertyDatavalue($snak->datavalue) : new NullPropertyDatavalue();		

	}

	/**
	 * Get only datavalue of snak
	 * @return object /Property/PropertyDatavalue
	 */
	public function getDatavalue() {

		return $this->datavalue;

	}

}