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
   * @var string QID of the parent in case this is a qualifier
   */
  public $parent;

  /**
   * @param \Illuminate\Support\Collection $data
   */
  public function __construct($data)
  {
    $data = $this->formatData($data);

    $this->id = $data['id'];
    $this->label = $data['label'];
    $this->value = $data['value'];
    $this->parent = $data['parent'];
  }

  private function formatData($data)
  {
    $parent = $label = $id = $value = null;
    if (!isset($data['qualifier']) || !$data['qualifier']) {
      $id = $this->normalizeId($data['property']);
      $label = $data['propertyLabel'];
      $value = $data['propertyValue'];
    } else {
      $id = $this->normalizeId($data['qualifier']);
      $label = $data['qualifierLabel'];
      $value = $data['qualifierValue'];
      $parent = $this->normalizeId($data['property']);
    }

    return [
      'id' => $id,
      'label' => $label,
      'value' => $value,
      'parent' => $parent
    ];
  }

  /**
   * Get wether this property is a qualifier, meaning it has a parent property
   *
   * @return boolean
   */
  public function isQualifier() {
    return $this->parent != null;
  }

  /**
   * Turn a Wikidata URL into a QID
   *
   * @param string $id URL of the Wikidata to be normalized
   * @return string
   */
  private function normalizeId($id)
  {
    return str_replace("http://www.wikidata.org/entity/", "", $id);
  }
}
