<?php

namespace Wikidata;

use Wikidata\Value;

class Property
{
  /**
   * @var string Property Id
   */
  public $id;

  /**
   * @var string Property label
   */
  public $label;

  /**
   * @var \Illuminate\Support\Collection Collection of property values
   */
  public $values;

  /**
   * @param array $data
   */
  public function __construct($data)
  {
    $this->parseData($data);
  }

  /**
   * Get list of all property values as an array
   * 
   * @return Wikidata/Value[]
   */
  public function values()
  {
    return $this->getValues();
  }

  /**
   * One more way to get list of all property values as an array
   * 
   * @return Wikidata/Value[]
   */
  public function getValues()
  {
    return $this->values->toArray();
  }

  /**
   * Parse input data
   * 
   * @param array $data
   */
  private function parseData($data)
  {
    $grouped = collect($data)->groupBy('statement');
    $flatten = $grouped->flatten(1);

    $this->id = get_id($flatten[0]['prop']);
    $this->label = $flatten[0]['propertyLabel'];
    $this->values = $grouped->values()->map(function($v) {
      return new Value($v->toArray());
    });
  }
}
