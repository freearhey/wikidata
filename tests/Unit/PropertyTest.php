<?php

namespace Wikidata\Tests;

use Wikidata\Property;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
  protected $dummy;
  protected $dummyChild;

  protected $property;
  protected $childProperty;

  public function setUp(): void
  {
    $this->dummy = [
      'item' => 'http://www.wikidata.org/entity/Q11019',
      'itemLabel' => 'máquina',
      'itemDescription' => 'conjunto de elementos móviles y fijos orientados para realizar un trabajo determinado',
      'itemAltLabel' => 'maquina',
      'prop' => 'http://www.wikidata.org/entity/P101',
      'propertyLabel' => 'campo de trabajo',
      'propertyValue' => 'ingeniería'
    ];

    $this->dummyChild = [
      'item' =>      'http://www.wikidata.org/entity/Q49546',
      'itemLabel' =>      'acetone',
      'itemDescription' =>      'chemical compound',
      'itemAltLabel' =>      '2-propanone, beta-ketopropane, dimethyl ketone, dimethylketone, ketone propane, methyl ketone, propanone',
      'prop' =>      'http://www.wikidata.org/prop/P2300',
      'propertyLabel' =>      'minimal lethal dose',
      'propertyValue' =>      '8000',
      'propertyValueLabel' =>      '8000',
      'qualifier' =>      'http://www.wikidata.org/entity/P2352',
      'qualifierLabel' =>      'applies to taxon',
      'qualifierValue' =>      'dog'
    ];

    $this->property = new Property($this->dummy);
    $this->childProperty = new Property($this->dummyChild);
  }

  public function testGetPropertyId()
  {
    $id = str_replace('http://www.wikidata.org/entity/', '', $this->dummy['prop']);
    $child_id = str_replace('http://www.wikidata.org/entity/', '', $this->dummyChild['qualifier']);

    $this->assertEquals($id, $this->property->id);
    $this->assertEquals($child_id, $this->childProperty->id);
  }

  public function testGetPropertyLabel()
  {
    $this->assertEquals($this->dummy['propertyLabel'], $this->property->label);
    $this->assertEquals($this->dummyChild['qualifierLabel'], $this->childProperty->label);
  }

  public function testGetPropertyValue()
  {
    $this->assertEquals($this->dummy['propertyValue'], $this->property->value);
    $this->assertEquals($this->dummyChild['qualifierValue'], $this->childProperty->value);
  }

  public function testGetPropertyParent()
  { 
    $parent_id = str_replace('http://www.wikidata.org/entity/', '', $this->dummyChild['prop']);
    $this->assertEquals($parent_id, $this->childProperty->parent);
  }
}
