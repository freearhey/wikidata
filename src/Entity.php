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
   * @var string Entity label
   */
  public $lang;
  
  /**
   * @var string Entity label
   */
  public $label;
  
  /**
   * @var string[] Array of all entity aliases
   */
  public $aliases = [];
  
  /**
   * @var string Entity description
   */
  public $description;

  /**
   * @var string[] Array of all entity properties
   */
  public $properties = [];

  /**
   * @param \Illuminate\Support\Collection $data
   */
  public function __construct($data, $lang) 
  {
    $data = $this->formatData($data);

    $this->id = $data['id'];
    $this->lang = $lang;
    $this->label = $data['label'];
    $this->aliases = $data['aliases'];
    $this->description = $data['description'];
    $this->properties = $data['properties'];
  }

  private function formatData($data)
  {
    $id = str_replace("http://www.wikidata.org/entity/", "", $data[0]['item']['value']);
    $label = $data[0]['itemLabel']['value'];
    $description = $data[0]['itemDescription']['value'];
    $aliases = explode(', ', $data[0]['itemAltLabel']['value']);

    $collection = collect($data);
    $properties = $collection->mapWithKeys(function($prop) {
      $property = new Property($prop);
      return [$property->id => $property];
    });
    
    return [
      'id' => $id,
      'label' => $label,
      'description' => $description,
      'aliases' => $aliases,
      'properties' => $properties
    ];
  }
}