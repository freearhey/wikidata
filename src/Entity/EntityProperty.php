<?php namespace Wikidata\Entity;

use Wikidata\Property\PropertyReference;
use Wikidata\Property\PropertyQualifier;
use Wikidata\Property\PropertySnak;

class EntityProperty {
	
	/**
	 * Class constructor
	 * @param StdClass object $property Entity property
	 */
	public function __construct($property) {

		$this->id = $property->id;
		$this->mainsnak = new PropertySnak($property->mainsnak);
		$this->type = $property->type;
		$this->rank = $property->rank;
		$this->references = (isset($property->references)) ? array_map([$this, 'createPropertyReferences'], $property->references) : null;
		$this->qualifiers = array_map([$this, 'createPropertyQualifiers'], (array) $property->qualifiers);
		$this->{'qualifiers-order'} = $property->{'qualifiers-order'};

	}

	/**
	 * Creating list of objects with qualifiers
	 * @param  array $qualifiers List properties
	 * @return array             List of /Property/PropertyQualifier
	 */
	private function createPropertyQualifiers($qualifiers) {

		return array_map([$this, 'createPropertyQualifier'], $qualifiers);

	}

	/**
	 * Creating property reference object
	 * @param  StdClass object $reference Property reference
	 * @return object            /Property/PropertyReference
	 */
	private function createPropertyReferences($reference) {

		return new PropertyReference($reference);

	}

	/**
	 * Creating property qualifier object
	 * @param  StdClass object $qualifier Property qualifier
	 * @return object            /Property/PropertyQualifier
	 */
	private function createPropertyQualifier($qualifier) {

		return new PropertyQualifier($qualifier);

	}

	/**
	 * Get mainsnak of property
	 * @return object /Property/PropertySnak
	 */
	public function getMainsnak() {

		return $this->mainsnak;

	}

}