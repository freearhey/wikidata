<?php

namespace Wikidata;

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
   * @var string Property value
   */
  public $value;

  /**
   * @param \Illuminate\Support\Collection $data
   */
  public function __construct($data) 
  {
    $data = $this->formatData($data);

    $this->id = $data['id'];
    $this->label = $data['label'];
    $this->value = $data['value'];
  }

  private function formatData($data)
  {
    $id = str_replace("http://www.wikidata.org/entity/", "", $data['prop']['value']);
    $label = $data['propLabel']['value'];
    $value = $data['propValues']['value'];

    return [
      'id' => $id,
      'label' => $label,
      'value' => $value
    ];
  }
}