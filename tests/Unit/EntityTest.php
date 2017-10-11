<?php

namespace Wikidata\Tests;

use Wikidata\Entity;
use Illuminate\Support\Collection;

class EntityTest extends \PHPUnit_Framework_TestCase 
{
  protected $entity;

  public function setUp()
  {
    $dummy = new Collection([
      'some_property' => new Collection([ 'foo' ])
    ]);

    $this->entity = new Entity($dummy);
  }

  public function testGetPropertyValuesBySlug() 
  {
    $this->assertEquals(['foo'], $this->entity->some_property);

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