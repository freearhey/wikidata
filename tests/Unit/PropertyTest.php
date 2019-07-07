<?php

namespace Wikidata\Tests;

use Wikidata\Property;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
  protected $dummy;

  protected $property;

  public function setUp(): void
  {
    $this->dummy = [
      'item' => 'http://www.wikidata.org/entity/Q11019',
      'itemLabel' => 'máquina',
      'itemDescription' => 'conjunto de elementos móviles y fijos orientados para realizar un trabajo determinado',
      'itemAltLabel' => 'maquina',
      'prop' => 'http://www.wikidata.org/entity/P101',
      'propLabel' => 'campo de trabajo',
      'propValue' => 'ingeniería'
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
    $this->assertEquals($this->dummy['propValue'], $this->property->value);
  }
}