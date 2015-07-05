<?php namespace Wikidata\Response;

use Wikidata\Entity\Entity;

class PropertyResponse
{
	
	private $properties,
			$success;

	/**
	 * Class constructor
	 * @param json $data Wikidata json response with property
	 * @param string $lang Language	
	 */
	public function __construct($data, $lang) {
		
		$response = json_decode($data);

		$this->properties = array_map([$this, 'createEntity'], (array) $response->entities);
		$this->success = $response->success;
		$this->lang = $lang;
		
	}

	/**
	 * Get first property
	 * @return object /Entity/Entity
	 */
	public function first() {

		$properties = array_values($this->properties);

		return $properties[0];

	}

	/**
	 * Creating entity
	 * @param  object $item StdClass object with property
	 * @return object       /Entity/Entity
	 */
	private function createEntity($item) {

		return new Entity($item);

	}

	/**
	 * Get label of property
	 * @return string
	 */
	public function getLabel() {

		return $this->first()->getLabel($this->lang);

	}


}