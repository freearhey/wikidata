<?php

namespace Wikidata;

class SearchResult
{
  /**
   * @var string
   */
  public $id;

  /**
   * @var string
   */
  public $lang;

  /**
   * @var string
   */
  public $label;

  /**
   * @var string
   */
  public $description;

  /**
   * @var array of strings
   */
  public $aliases;

  /**
   * @param array $data
   */
  public function __construct($data, $lang = 'en', $source = 'api') 
  {
    $data = $this->extractData($data, $source);

    $this->id = $data['id'];
    $this->lang = $lang;
    $this->label = $data['label'];
    $this->description = $data['description'];
    $this->wikipedia_article = $data['wikipediaArticle'];
    $this->aliases = $data['aliases'];
  }

  private function extractData($data, $source) {

    if($source == 'api') {
      $id = isset($data['id']) ? $data['id'] : null;
      $label = isset($data['label']) ? $data['label'] : null;
      $aliases = isset($data['aliases']) ? $data['aliases'] : [];
      $description = isset($data['description']) ? $data['description'] : null;
    } else {
      $id = isset($data['item']) ? str_replace("http://www.wikidata.org/entity/", "", $data['item']) : null;
      $label = isset($data['itemLabel']) ? $data['itemLabel'] : null;
      $aliases = isset($data['itemAltLabel']) ? explode(', ', $data['itemAltLabel']) : [];
      $description = isset($data['itemDescription']) ? $data['itemDescription'] : null;
      $wikipediaArticle = isset($data['wikipediaArticle']) ? $data['wikipediaArticle'] : null;
    }

    return [
      'id' => $id,
      'label' => $label,
      'aliases' => $aliases,
      'description' => $description,
      'wikipediaArticle' => $wikipediaArticle,
    ];
  }
}
