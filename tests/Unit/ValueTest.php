<?php

namespace Wikidata\Tests;

use Wikidata\Value;

class ValueTest extends TestCase
{
  protected $value;

  public function setUp(): void
  {
    $this->value = new Value($this->dummyProperties);
  }

  public function testGetValueId()
  {
    $id = str_replace('http://www.wikidata.org/entity/', '', $this->dummyProperties[0]['propertyValue']);

    $this->assertEquals($id, $this->value->id);
  }

  public function testGetValueLabel()
  {
    $this->assertEquals($this->dummyProperties[0]['propertyValueLabel'], $this->value->label);
  }

  public function testGetValueQualifiers()
  {
    $qualifiers = $this->value->qualifiers;

    $this->assertInstanceOf('Illuminate\Support\Collection', $qualifiers);

    $this->assertInstanceOf('Wikidata\Qualifier', $qualifiers->first());
  }
}
