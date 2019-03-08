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
  public function __construct($data, $lang = 'en') 
  {
    $this->id = $data['id'];
    $this->lang = $lang;
    $this->label = $data['label'];
    $this->description = $data['description'];
    $this->aliases = $data['aliases'];
  }
}