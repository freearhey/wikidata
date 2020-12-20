<?php

namespace Wikidata\Tests;

use Wikidata\Entity;

class EntityTest extends TestCase
{
  protected $lang = 'es';

  protected $entity;

  public function setUp(): void
  {
    $this->entity = new Entity($this->dummy[0], $this->lang);
  }

  public function testGetEntityId()
  {
    $this->assertEquals($this->dummy[0]['id'], $this->entity->id);
  }

  public function testGetEntityLang()
  {
    $this->assertEquals($this->lang, $this->entity->lang);
  }

  public function testGetEntityLabel()
  {
    $this->assertEquals($this->dummy[0]['labels'][$this->lang]['value'], $this->entity->label);
  }

  public function testGetEntityDescription()
  {
    $this->assertEquals($this->dummy[0]['descriptions'][$this->lang]['value'], $this->entity->description);
  }

  public function testGetWikiUrl()
  {
    $this->assertEquals($this->dummy[0]['sitelinks'][$this->lang . 'wiki']['url'], $this->entity->wiki_url);
  }

  public function testGetEntityAliases()
  {
    $aliases = collect($this->dummy[0]['aliases'][$this->lang])->pluck('value')->toArray();

    $this->assertEquals($aliases, $this->entity->aliases);
  }

  public function testGetEntityWithoutAliases()
  {
    $entity = new Entity($this->dummy[1], 'en');

    $this->assertEquals([], $entity->aliases);
  }

  public function testGetEntityProperties()
  {
    $this->entity->parseProperties($this->dummyProperties);

    $properties = $this->entity->properties;

    $this->assertInstanceOf('Illuminate\Support\Collection', $properties);

    $this->assertInstanceOf('Wikidata\Property', $properties->first());
  }
}
