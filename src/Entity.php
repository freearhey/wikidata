<?php

namespace Wikidata;

class Entity
{
  /**
   * @var string Entity Id
   */
  public $id;
  
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
   * @var boolean Status of entity
   */
  public $exists = true;

  /**
   * @var string[] Array of all entity properties
   */
  public $properties = [];

  /**
   * @var \Illuminate\Support\Collection
   */
  private $data;

  /**
   * @param \Illuminate\Support\Collection $data
   */
  public function __construct($id, $label, $aliases, $description, $data) 
  {
    if(!$id) {
      $this->exists = false;
    }

    $this->id = $id;
    $this->label = $label;
    $this->aliases = $aliases;
    $this->description = $description;
    $this->data = $data;
    $this->properties = $data->keys()->toArray();
  }

  /**
   * Get property value by it slug if exist
   *
   * @param string $name
   * @return array Return an array of property values
   */
  public function get($name) 
  {
    return ($this->has($name)) ? $this->data->get($name)->toArray() : [];
  }

  /**
   * Check that entity has property
   *
   * @param string $name
   * @return bool  Return true if property exist or false if not
   */
  public function has($name) 
  {
    return $this->data->has($name);
  }

  /**
   * Convert all entity properties to array
   *
   * @return array Return list of all entity data as an array
   */
  public function toArray() 
  {
    return $this->data->toArray();
  }
}