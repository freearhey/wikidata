<?php

namespace Wikidata;

class Entity
{
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
  public function __construct($data) 
  {
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