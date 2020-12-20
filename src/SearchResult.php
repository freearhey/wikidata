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
  public $wiki_url;

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
  public function __construct($data, $lang = 'en')
  {
    $this->parseData($data);
    $this->lang = $lang;
  }

  private function parseData($data)
  {
    $this->id = isset($data['id']) ? $data['id'] : null;
    $this->label = isset($data['label']) ? $data['label'] : null;
    $this->aliases = isset($data['aliases']) ? $data['aliases'] : [];
    $this->description = isset($data['description']) ? $data['description'] : null;
    $this->wiki_url = isset($data['wiki_url']) ? $data['wiki_url'] : null;
  }
}
