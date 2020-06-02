<?php

namespace Wikidata\Tests;

use Wikidata\SearchResult;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class SearchResultTest extends TestCase
{
  protected $dummy;

  protected $result;

  public function setUp(): void
  {
    $this->dummy = [
      'id' => 'Q1111',
      'lang' => 'en',
      'label' => 'Dummy label',
      'wikipediaArticle' => 'https://en.wikipedia.org/wiki/Dummy',
      'aliases' => [ 'Dummy alias' ],
      'description' => 'Dummy description'
    ];

    $collection = new Collection($this->dummy);

    $this->result = new SearchResult($collection);
  }

  public function testGetResultId()
  {
    $this->assertEquals($this->dummy['id'], $this->result->id);
  }

  public function testGetResultLang()
  {
    $this->assertEquals($this->dummy['lang'], $this->result->lang);
  }

  public function testGetResultLabel()
  {
    $this->assertEquals($this->dummy['label'], $this->result->label);
  }

  public function testGetResultWikipediaArticle()
  {
    $this->assertEquals($this->dummy['wikipediaArticle'], $this->result->wikipedia_article);
  }

  public function testGetResultAliases()
  {
    $this->assertEquals($this->dummy['aliases'], $this->result->aliases);
  }

  public function testGetResultDescription()
  {
    $this->assertEquals($this->dummy['description'], $this->result->description);
  }
}
