<?php

namespace Wikidata;

use Wikidata\Property;

class Entity
{
  /**
   * @var string Entity Id
   */
  public $id;

  /**
   * @var string Entity language
   */
  public $lang;
  
  /**
   * @var string Entity label
   */
  public $label;
  
  /**
   * @var string[] List of entity aliases
   */
  public $aliases = [];
  
  /**
   * @var string Entity description
   */
  public $description;

  /**
   * @var \Illuminate\Support\Collection Collection of entity properties
   */
  public $properties;

  /**
   * @param array $data
   * @param string $lang
   */
  public function __construct($data, $lang) 
  {
    $this->parseData($data);
    $this->lang = $lang;
  }

  /**
   * Get list of all entity properties as an array
   * 
   * @return Wikidata/Property[]
   */
  public function properties()
  {
    return $this->getProperties();
  }

  /**
   * One more way to get list of all entity properties as an array
   * 
   * @return Wikidata/Property[]
   */
  public function getProperties()
  {
    return $this->properties->toArray();
  }

  /**
   * Parse input data
   * 
   * @param array $data
   */
  private function parseData($data)
  {    
    $this->id = get_id($data[0]['item']);
    $this->label = $data[0]['itemLabel'];
    $this->aliases = explode(', ', $data[0]['itemAltLabel']);
    $this->description = $data[0]['itemDescription'];

    $collection = collect($data)->groupBy('prop');
    $this->properties = $collection->mapWithKeys(function($item) {
      $property = new Property($item);

      return [$property->id => $property];
    });
  }
}
