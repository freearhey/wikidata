<?php

namespace Wikidata\Tests;

use Wikidata\Qualifier;

class QualifierTest extends TestCase
{
  protected $qualifier;

  public function setUp(): void
  {
    $this->qualifier = new Qualifier($this->dummy);
  }

  public function testGetQualifierId()
  {
    $id = str_replace('http://www.wikidata.org/entity/', '', $this->dummy['qualifier']);

    $this->assertEquals($id, $this->qualifier->id);
  }

  public function testGetQualifierLabel()
  {
    $this->assertEquals($this->dummy['qualifierLabel'], $this->qualifier->label);
  }

  public function testGetQualifierValue()
  {
    $this->assertEquals($this->dummy['qualifierValueLabel'], $this->qualifier->value);
  }
}
