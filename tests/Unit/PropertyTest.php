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
      'item' => [
        'type' => 'uri',
        'value' => 'http://www.wikidata.org/entity/Q11019'
      ],
      'prop' => [
        'type' => 'uri',
        'value' => 'http://www.wikidata.org/entity/P101'
      ],
      'itemLabel' => [
        'xml:lang' => 'es',
        'type' => 'literal',
        'value' => 'máquina'
      ],
      'itemDescription' => [
        'xml:lang' => 'es',
        'type' => 'literal',
        'value' => 'conjunto de elementos móviles y fijos orientados para realizar un trabajo determinado'
      ],
      'itemAltLabel' => [
        'xml:lang' => 'es',
        'type' => 'literal',
        'value' => 'maquina'
      ],
      'propLabel' => [
        'xml:lang' => 'es',
        'type' => 'literal',
        'value' => 'campo de trabajo'
      ],
      'valueLabel' => [
        'xml:lang' => 'es',
        'type' => 'literal',
        'value' => 'ingeniería'
      ]
    ];

    $this->property = new Property($this->dummy);
  }

  public function testGetPropertyId()
  {
    $id = str_replace("http://www.wikidata.org/entity/", "", $this->dummy['prop']['value']);

    $this->assertEquals($id, $this->property->id);
  }

  public function testGetPropertyLabel()
  {
    $this->assertEquals($this->dummy['propLabel']['value'], $this->property->label);
  }

  public function testGetPropertyValue()
  {
    $this->assertEquals($this->dummy['propValues']['value'], $this->property->value);
  }
}