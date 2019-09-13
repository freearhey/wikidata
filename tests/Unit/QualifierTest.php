<?php

namespace Wikidata\Tests;

use Wikidata\Qualifier;

class QualifierTest extends TestCase
{
  protected $qualifier;

  public function setUp(): void
  {
    $this->qualifier = new Qualifier($this->dummy[0]);
  }

  public function testGetQualifierId()
  {
    $id = str_replace('http://www.wikidata.org/entity/', '', $this->dummy[0]['qualifier']);

    $this->assertEquals($id, $this->qualifier->id);
  }

  public function testGetQualifierLabel()
  {
    $this->assertEquals($this->dummy[0]['qualifierLabel'], $this->qualifier->label);
  }

  public function testGetQualifierValue()
  {
    $this->assertEquals($this->dummy[0]['qualifierValueLabel'], $this->qualifier->value);
  }
}
