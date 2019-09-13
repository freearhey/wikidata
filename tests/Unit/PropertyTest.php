<?php

namespace Wikidata\Tests;

use Wikidata\Property;

class PropertyTest extends TestCase
{
  protected $property;

  public function setUp(): void
  {
    $this->property = new Property([$this->dummy]);
  }

  public function testGetPropertyId()
  {
    $id = str_replace('http://www.wikidata.org/entity/', '', $this->dummy['prop']);

    $this->assertEquals($id, $this->property->id);
  }

  public function testGetPropertyLabel()
  {
    $this->assertEquals($this->dummy['propertyLabel'], $this->property->label);
  }

  public function testGetPropertyValues()
  {
    $values = $this->property->values;

    $this->assertInstanceOf('Illuminate\Support\Collection', $values);

    $this->assertInstanceOf('Wikidata\Value', $values->first());
  }

  public function testGetPropertyValuesAsArray()
  {
    $values = $this->property->values();

    $this->assertEquals(true, is_array($values));

    $this->assertInstanceOf('Wikidata\Value', $values[0]);
  }
}
