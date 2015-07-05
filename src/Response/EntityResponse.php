<?php namespace Wikidata\Response;

use Wikidata\Entity\Entity;

class EntityResponse {
	
	private $entities,
			$success;

	/**
	 * Class constructor
	 * @param json $data Wikidata json response with entities
	 */
	public function __construct($data) {
		
		$response = json_decode($data);

		$this->entities = array_map([$this, 'createEntity'], (array) $response->entities);
		$this->success = $response->success;
		
	}

	/**
	 * Get all entity or only single by id
	 * @param  integer $id Entity id (like Q26) or null
	 * @return mix    Return array with /Entity/Entity or single /Entity/Entity
	 */
	public function get($id = null) {

		if($id)
			return $this->entities[$id];

		return $this->entities;

	}

	/**
	 * Get first entity
	 * @return object /Entity/Entity
	 */
	public function first() {

		$entities = array_values($this->entities);

		return $entities[0];

	}

	/**
	 * Check if no entities
	 * @return boolean
	 */
	public function isEmpty() {

		return (count($this->entities) < 1) ? true : false;

	}

	/**
	 * Creating entity object
	 * @param  object $item StdClass object with entity
	 * @return object       /Entity/Entity
	 */
	private function createEntity($item) {

		return new Entity($item);

	}

}