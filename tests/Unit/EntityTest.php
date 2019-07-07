<?php

namespace Wikidata\Tests;

use Wikidata\Entity;
use Wikidata\Property;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
  protected $lang = 'en';

  protected $dummy;

  protected $entity;

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

    $this->lang = 'es';

    $this->entity = new Entity([$this->dummy], $this->lang);
  }

  public function testGetEntityId()
  {
    $id = str_replace("http://www.wikidata.org/entity/", "", $this->dummy['item']);

    $this->assertEquals($id, $this->entity->id);
  }

  public function testGetEntityLang()
  {
    $this->assertEquals($this->lang, $this->entity->lang);
  }

  public function testGetEntityLabel()
  {
    $this->assertEquals($this->dummy['itemLabel'], $this->entity->label);
  }

  public function testGetEntityAliases()
  {
    $aliases = explode(', ', $this->dummy['itemAltLabel']);

    $this->assertEquals($aliases, $this->entity->aliases);
  }

  public function testGetEntityDescription()
  {
    $this->assertEquals($this->dummy['itemDescription'], $this->entity->description);
  }

  public function testGetEntityProperties() 
  {
    $property = new Property($this->dummy);
    $properties = collect([ $property->id => $property ]);

    $this->assertEquals($properties, $this->entity->properties);
  }
}