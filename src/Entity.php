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
   * @var string Entity language
   */
  public $lang;

  /**
   * @var string Entity label
   */
  public $label;

  /**
   * @var string A link to a Wikipedia article about this entity
   */
  public $wiki_url = null;

  /**
   * @var string[] List of entity aliases
   */
  public $aliases = [];

  /**
   * @var string Entity description
   */
  public $description;

  /**
   * @var \Illuminate\Support\Collection Collection of entity properties
   */
  public $properties = [];

  /**
   * @param array $data
   * @param string $lang
   */
  public function __construct($data, $lang)
  {
    $this->lang = $lang;
    $this->parseData($data);
  }

  /**
   * Parse input data
   *
   * @param array $data
   */
  private function parseData($data)
  {
    $lang = $this->lang;
    $site = $lang . 'wiki';

    $this->id = $data['id'];
    $this->label = isset($data['labels'][$lang]) ? $data['labels'][$lang]['value'] : null;
    $this->description = isset($data['descriptions'][$lang]) ? $data['descriptions'][$lang]['value'] : null;
    $this->wiki_url = isset($data['sitelinks'][$site]) ? $data['sitelinks'][$site]['url'] : null;
    $this->aliases = isset($data['aliases'][$lang]) ? collect($data['aliases'][$lang])->pluck('value')->toArray() : [];
  }

  /**
   * Parse entity properties from sparql result
   *
   * @param array $data
   */
  public function parseProperties($data)
  {
    $collection = collect($data)->groupBy('prop');
    $this->properties = $collection->mapWithKeys(function ($item) {
      $property = new Property($item);

      return [$property->id => $property];
    });
  }

  public function toArray()
  {
    return [
      'id' => $this->id,
      'lang' => $this->lang,
      'label' => $this->label,
      'description' => $this->description,
      'wiki_url' => $this->wiki_url,
      'aliases' => $this->aliases,
      'properties' => $this->properties,
    ];
  }
}
