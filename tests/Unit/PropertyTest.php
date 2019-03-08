<?php

namespace Wikidata\Tests;

use Wikidata\Property;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
  protected $dummy;

  protected $property;

  public function setUp()
  {
    $this->dummy = [
      'item' => 'http://www.wikidata.org/entity/Q11019',
      'prop' => 'http://www.wikidata.org/entity/P101',
      'itemLabel' => 'máquina',
      'itemDescription' => 'conjunto de elementos móviles y fijos orientados para realizar un trabajo determinado',
      'itemAltLabel' => 'maquina',
      'propLabel' => 'campo de trabajo',
      'valueLabel' => 'ingeniería'
    ];

    $this->property = new Property($this->dummy);
  }

  public function testGetPropertyId()
  {
    $id = str_replace("http://www.wikidata.org/entity/", "", $this->dummy['prop']);

    $this->assertEquals($id, $this->property->id);
  }

  public function testGetPropertyLabel()
  {
    $this->assertEquals($this->dummy['propLabel'], $this->property->label);
  }

  public function testGetPropertyValue()
  {
    $this->assertEquals($this->dummy['propValues'], $this->property->value);
  }
}