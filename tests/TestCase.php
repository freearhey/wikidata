<?php 

namespace Wikidata\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
  protected $dummy = [
    'item' => 'http://www.wikidata.org/entity/Q11019',
    'itemLabel' => 'máquina',
    'itemDescription' => 'conjunto de elementos móviles y fijos orientados para realizar un trabajo determinado',
    'itemAltLabel' => 'maquina',
    'statement' => 'wds:Q11019-18628e57-437b-8a98-6176-13a32b3e02dd',
    'prop' => 'http://www.wikidata.org/entity/P101',
    'propertyLabel' => 'campo de trabajo',
    'propertyValue' => 'http://www.wikidata.org/entity/Q517596',
    'propertyValueLabel' => 'mechanism',
    'qualifier' => 'http://www.wikidata.org/entity/P805',
    'qualifierLabel' => 'statement is subject of',
    'qualifierValue' => 'http://www.wikidata.org/entity/Q23857720',
    'qualifierValueLabel' => 'Q23857720',
  ];
}
