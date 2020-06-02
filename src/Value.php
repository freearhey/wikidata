<?php

namespace Wikidata;

use Wikidata\Qualifier;

class Value
{
  /**
   * @var string Value Id
   */
  public $id;

  /**
   * @var string Value label
   */
  public $label;

  /**
   * @var \Illuminate\Support\Collection Collection of value qualifiers
   */
  public $qualifiers;

  /**
   * @param array $data
   */
  public function __construct($data)
  {
    $this->parseData($data);
  }

  /**
   * Parse input data
   *
   * @param array $data
   */
  private function parseData($data)
  {
    $this->id = get_id($data[0]['propertyValue']);
    $this->label = $data[0]['propertyValueLabel'];
    $this->qualifiers = collect($data)->map(function($item) {
      if($item['qualifier']) {
        return new Qualifier($item);
      }

      return null;
    })->filter();
  }
}
