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

  public function testChangeLanguage() 
  {
    $wikidata = new Wikidata('fr');

    $this->assertEquals('fr', $wikidata->language);
  }

  public function testSearchByTerm() 
  {
    $results = $this->wikidata->search('car');

    $this->assertInstanceOf('Illuminate\Support\Collection', $results);

    $result = $results->first();

    $this->assertInstanceOf('Wikidata\Result', $result);
  }

  public function testSearchByPropertyIdAndStringValue() 
  {
    $results = $this->wikidata->searchBy('P646', '/m/02mjmr');

    $this->assertInstanceOf('Illuminate\Support\Collection', $results);

    $result = $results->first();

    $this->assertInstanceOf('Wikidata\Result', $result);
  }

  public function testSearchByPropertyIdAndEntityId() 
  {
    $results = $this->wikidata->searchBy('P39', 'Q11696');

    $this->assertInstanceOf('Illuminate\Support\Collection', $results);

    $result = $results->first();

    $this->assertInstanceOf('Wikidata\Result', $result);
  }

  public function testGetEntityWithAllPropertiesById() 
  {
    $entity = $this->wikidata->get('Q76');

    $this->assertInstanceOf('Wikidata\Entity', $entity);
  }

}