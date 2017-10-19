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
    $dummy = new Collection([
      'some_property' => new Collection([ 'foo' ])
    ]);

    $this->entity = new Entity($dummy);
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