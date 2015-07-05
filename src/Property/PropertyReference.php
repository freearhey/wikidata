<?php namespace Wikidata\Property;

class PropertyReference {
	
	/**
	 * Class constructor
	 * @param object $reference StdClass object with reference
	 */
	public function __construct($reference) {

		$this->hash = $reference->hash;
		$this->snaks = array_map([$this, 'createPropertyReferenceSnaks'], (array) $reference->snaks);
		$this->{'snaks-order'} = $reference->{'snaks-order'};

	}

	/**
	 * Creating list of property snaks
	 * @param  array $snaks Property snaks
	 * @return array        List of /Property/PropertySnak
	 */
	private function createPropertyReferenceSnaks($snaks) {

		return array_map([$this, 'createPropertySnak'], $snaks);

	}

	/**
	 * Creating property snak object
	 * @param  object $snak StdClass object
	 * @return object       /Property/PropertySnak
	 */
	private function createPropertySnak($snak) {

		return new PropertySnak($snak);

	}

}