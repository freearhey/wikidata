<?php namespace Wikidata\Entity;

class Entity {
	
	private $entity = array(),
			$aliases = array(),
			$labels = array(),
			$descriptions = array(),
			$properties = array();


	/**
	 * Class constructor
	 * @param \Response\EntityResponse $entity
	 */
	public function __construct($entity) {

		$this->entity['page_id'] = $entity->pageid;
		$this->entity['ns'] = $entity->ns;
		$this->entity['title'] = $entity->title;
		$this->entity['lastrevid'] = $entity->lastrevid;
		$this->entity['modified'] = $entity->modified;
		$this->entity['id'] = $entity->id;
		$this->entity['type'] = $entity->type;
		$this->aliases = array_map([$this, 'createEntityAlias'], (array) $entity->aliases);
		$this->labels = array_map([$this, 'createEntityValue'], (array) $entity->labels);
		$this->descriptions = array_map([$this, 'createEntityValue'], (array) $entity->descriptions);
		$this->properties = array_map([$this, 'createEntityProperties'], (array) $entity->claims);

	}

	/**
	 * Creating list of objects with properties
	 * @param  array $properties List properties
	 * @return array             List of /Entity/EntityProperty
	 */
	private function createEntityProperties($properties) {

		return array_map([$this, 'createEntityProperty'], (array) $properties);

	}

	/**
	 * Creating entity property object
	 * @param  StdClass object $property Entity property
	 * @return object           /Entity/EntityProperty
	 */
	private function createEntityProperty($property) {

		return new EntityProperty($property);

	}

	/**
	 * Creating list of objects with entity aliases
	 * @param  array $alias List entity aliases
	 * @return array       List of /Entity/EntityAliases
	 */
	private function createEntityAlias($alias) {

		return array_map([$this, 'createEntityValue'], $alias);

	}

	/**
	 * Creating entity value object
	 * @param  StdClass object $value Entity alias
	 * @return object        /Entity/EntityValue
	 */
	private function createEntityValue($value) {

		return new EntityValue($value);

	}

	/**
	 * Get entity property by id and language
	 * @param  string $id   See more at https://www.wikidata.org/wiki/Wikidata:List_of_properties
	 * @param  string $lang Language of property's value
	 * @return mix      Return list all property values or null if property not exist
	 */
	public function getPropertyValues($id, $lang = 'en') {

		$id = strtoupper($id);

		if(!isset($this->properties[$id]))
			return null;

		$output = [];

		foreach($this->properties[$id] as $property) {

			$output[] = $property->getMainsnak()->getDatavalue()->getValue($lang);

		}

		return $output;

	}

	/**
	 * Get entity label
	 * @param  string $lang Language of entity's label
	 * @return mix  Return string with value or null     
	 */
	public function getLabel($lang = 'en') {

		if(!isset($this->labels[$lang]))
			return null;
			

		return $this->labels[$lang]->getValue();

	}

	/**
	 * Get entity description
	 * @param  string $lang Language of entity's description
	 * @return mix
	 */
	public function getDescription($lang = 'en') {

		if(!isset($this->descriptions[$lang]))
			return null;

		return $this->descriptions[$lang]->getValue();

	}

}