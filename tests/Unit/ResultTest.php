<?php

namespace Wikidata\Tests;

use Wikidata\Result;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase 
{
  protected $dummy;

  protected $result;

  public function setUp()
  {
    $this->dummy = [
      'id' => 'Q1111',
      'label' => 'Dummy label',
      'aliases' => [ 'Dummy alias' ],
      'description' => 'Dummy description'
    ];

    $collection = new Collection($this->dummy);

    $this->result = new Result($collection);
  }

  public function testGetResultId() 
  {
    $this->assertEquals('Q1111', $this->result->id);
  }

  public function testGetResultLabel() 
  {
    $this->assertEquals('Dummy label', $this->result->label);
  }

  public function testGetResultAliases() 
  {
    $this->assertEquals([ 'Dummy alias' ], $this->result->aliases);
  }

  public function testGetResultDescription() 
  {
    $this->assertEquals('Dummy description', $this->result->description);
  }

  public function testGetAllResultDataAsArray() 
  {
    $this->assertEquals($this->dummy, $this->result->toArray());
  }
}