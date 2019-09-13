<?php

namespace Wikidata;

use Wikidata\Qualifier;

class Value
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
   * @var array List of property qualifiers
   */
  public $qualifiers;

  /**
   * @param \Illuminate\Support\Collection $data
   */
  public function __construct($data)
  {
    $this->parseData($data);
  }

  public function qualifiers()
  {
    return $this->getQualifiers();
  }

  public function getQualifiers()
  {
    return $this->qualifiers->toArray();
  }

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
