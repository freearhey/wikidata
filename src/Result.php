<?php

namespace Wikidata;

class Result
{
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
  }

  /**
   * Get property value by it name if exist
   *
   * @param string $name
   * @return mix Return property values
   */
  public function __get($name) 
  {
    return $this->get($name);
  }

  /**
   * Get property value by it name if exist
   *
   * @param string $name
   * @return mix Return property values
   */
  public function get($name) 
  {
    return ($this->data->has($name)) ? $this->data->get($name) : null;
  }

  /**
   * Convert all result properties to array
   *
   * @return array Return list of all result data as an array
   */
  public function toArray() 
  {
    return $this->data->toArray();
  }
}