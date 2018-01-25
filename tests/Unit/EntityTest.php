<?php

namespace Wikidata\Tests;

use Wikidata\Entity;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
  protected $entity;

  public function setUp()
  {
    $this->id = 'Q26';
    $this->label = 'Northern Ireland';
    $this->aliases = [ 'NIR', 'UKN', 'North Ireland' ];
    $this->description = 'region in north-west Europe, part of the United Kingdom';
    
    $data = new Collection([
      'some_property' => new Collection([ 'foo' ])
    ]);

    $this->entity = new Entity($this->id, $this->label, $this->aliases, $this->description, $data);
  }

  public function testGetEntityId()
  {
    $this->assertEquals($this->id, $this->entity->id);
  }

  public function testGetEntityLabel()
  {
    $this->assertEquals($this->label, $this->entity->label);
  }

  public function testGetEntityAliases()
  {
    $this->assertEquals($this->aliases, $this->entity->aliases);
  }

  public function testGetEntityDescription()
  {
    $this->assertEquals($this->description, $this->entity->description);
  }

  public function testGetListOfAllProperties() 
  {
    $this->assertEquals(['some_property'], $this->entity->properties);
  }

  public function testGetPropertyValuesBySlug() 
  {
    $this->assertEquals(['foo'], $this->entity->get('some_property'));
  }

  public function testCheckBySlugIfPropertyExist() 
  {
    $this->assertEquals(true, $this->entity->has('some_property'));
  }

  public function testGetAllEntityDataAsArray() 
  {
    $this->assertEquals([ 'some_property' => [ 'foo' ] ], $this->entity->toArray());
  }
}