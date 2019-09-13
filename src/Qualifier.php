<?php

namespace Wikidata;

class Qualifier
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
   * @var string Property value
   */
  public $value;

  /**
   * @param \Illuminate\Support\Collection $data
   */
  public function __construct($data)
  {
    $this->parseData($data);
  }

  private function parseData($data)
  {
    $this->id = get_id($data['qualifier']);
    $this->label = $data['qualifierLabel'];
    $this->value = $data['qualifierValueLabel'];
  }
}
