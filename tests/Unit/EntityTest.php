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
