<?php

namespace Wikidata\Tests;

use Wikidata\Value;

class ValueTest extends TestCase
{
  protected $value;

  public function setUp(): void
  {
    $this->value = new Value([$this->dummy]);
  }

  public function testGetValueId()
  {
    $id = str_replace('http://www.wikidata.org/entity/', '', $this->dummy['propertyValue']);

    $this->assertEquals($id, $this->value->id);
  }

  public function testGetValueLabel()
  {
    $this->assertEquals($this->dummy['propertyValueLabel'], $this->value->label);
  }

  public function testGetValueQualifiers()
  {
    $qualifiers = $this->value->qualifiers;

    $this->assertInstanceOf('Illuminate\Support\Collection', $qualifiers);

    $this->assertInstanceOf('Wikidata\Qualifier', $qualifiers->first());
  }

  public function testGetValueQualifiersAsArray()
  {
    $qualifiers = $this->value->qualifiers();

    $this->assertEquals(true, is_array($qualifiers));

    $this->assertInstanceOf('Wikidata\Qualifier', $qualifiers[0]);
  }
}
