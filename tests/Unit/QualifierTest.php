<?php

namespace Wikidata\Tests;

use Wikidata\Qualifier;

class QualifierTest extends TestCase
{
  protected $qualifier;

  public function setUp(): void
  {
    $this->qualifier = new Qualifier($this->dummyProperties[0]);
  }

  public function testGetQualifierId()
  {
    $id = str_replace('http://www.wikidata.org/entity/', '', $this->dummyProperties[0]['qualifier']);

    $this->assertEquals($id, $this->qualifier->id);
  }

  public function testGetQualifierLabel()
  {
    $this->assertEquals($this->dummyProperties[0]['qualifierLabel'], $this->qualifier->label);
  }

  public function testGetQualifierValue()
  {
    $this->assertEquals($this->dummyProperties[0]['qualifierValueLabel'], $this->qualifier->value);
  }
}
