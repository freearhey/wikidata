<?php

namespace Wikidata\Tests;

use Wikidata\Entity;

class EntityTest extends TestCase
{
  protected $lang = 'en';

  protected $entity;

  public function setUp(): void
  {
    $this->lang = 'es';

    $this->entity = new Entity($this->dummy, $this->lang);
  }

  public function testGetEntityId()
  {
    $id = str_replace("http://www.wikidata.org/entity/", "", $this->dummy[0]['item']);

    $this->assertEquals($id, $this->entity->id);
  }

  public function testGetEntityLang()
  {
    $this->assertEquals($this->lang, $this->entity->lang);
  }

  public function testGetEntityLabel()
  {
    $this->assertEquals($this->dummy[0]['itemLabel'], $this->entity->label);
  }

  public function testGetWikipediaArticle()
  {
    $this->assertEquals($this->dummy[0]['wikipediaArticle'], $this->entity->wikipedia_article);
  }

  public function testGetEntityAliases()
  {
    $aliases = explode(', ', $this->dummy[0]['itemAltLabel']);

    $this->assertEquals($aliases, $this->entity->aliases);
  }

  public function testGetEntityWithoutAliases()
  {
    $dummy = [
      [
          "item" => "http://www.wikidata.org/entity/Q49546",
          "itemLabel" => "acetone",
          "wikipediaArticle" => "https://en.wikipedia.org/wiki/Acetone",
          "itemDescription" => "chemical compound",
          "itemAltLabel" => NULL,
          "prop" => "http://www.wikidata.org/prop/P4952",
          "propertyLabel" => "safety classification and labelling",
          "statement" => "http://www.wikidata.org/entity/statement/Q49546-68675f20-4644-b8be-6280-f8cec5399f36",
          "propertyValue" => "http://www.wikidata.org/entity/Q2005334",
          "propertyValueLabel" => "Regulation (EC) No. 1272/2008",
          "qualifier" => "http://www.wikidata.org/entity/P5041",
          "qualifierLabel" => "GHS hazard statement",
          "qualifierValue" => "http://www.wikidata.org/entity/Q51844420",
          "qualifierValueLabel" => "H336"
      ]
    ];

    $entity = new Entity($dummy, 'en');

    $this->assertEquals([], $entity->aliases);
  }

  public function testGetEntityDescription()
  {
    $this->assertEquals($this->dummy[0]['itemDescription'], $this->entity->description);
  }

  public function testGetEntityProperties()
  {
    $properties = $this->entity->properties;

    $this->assertInstanceOf('Illuminate\Support\Collection', $properties);

    $this->assertInstanceOf('Wikidata\Property', $properties->first());
  }
}
