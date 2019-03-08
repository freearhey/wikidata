<?php

namespace Wikidata\Tests;

use Wikidata\Wikidata;
use PHPUnit\Framework\TestCase;

class WikidataTest extends TestCase 
{
  protected $wikidata;

  public function setUp()
  {
    $this->wikidata = new Wikidata();
  }

  public function testSearchByTerm() 
  {
    $results = $this->wikidata->search('London');

    $this->assertInstanceOf('Illuminate\Support\Collection', $results);

    $result = $results->first();

    $this->assertInstanceOf('Wikidata\SearchResult', $result);
  }

  public function testSearchResultIsEmpty() 
  {
    $results = $this->wikidata->search('asdfgh');

    $this->assertEquals(true, $results->isEmpty());
  }

  public function testSearchByPropertyIdAndStringValue() 
  {
    $results = $this->wikidata->searchBy('P646', '/m/02mjmr');

    $this->assertInstanceOf('Illuminate\Support\Collection', $results);

    $result = $results->first();

    $this->assertInstanceOf('Wikidata\SearchResult', $result);
  }

  public function testSearchByPropertyIdAndEntityId() 
  {
    $results = $this->wikidata->searchBy('P39', 'Q11696');

    $this->assertInstanceOf('Illuminate\Support\Collection', $results);

    $result = $results->first();

    $this->assertInstanceOf('Wikidata\SearchResult', $result);
  }

  public function testGetEntityById() 
  {
    $entity = $this->wikidata->get('Q11019', 'es');

    $this->assertInstanceOf('Wikidata\Entity', $entity);
  }

  public function testGetEntityWithWrongId() 
  {
    $entity = $this->wikidata->get('test');

    $this->assertEquals(null, $entity);
  }

}