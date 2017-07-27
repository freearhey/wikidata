<?php

namespace Wikidata;

class Entity
{

  public $properties = [];

  private $data;

  public function __construct($data) {

    $this->data = $data;
    $this->properties = $data->keys();

  }

  public function __get($name) {

    return $this->get($name);

  }

  public function get($name) {

    return ($this->has($name)) ? $this->data->get($name)->toArray() : [];

  }

  public function has($name) {

    return $this->data->has($name);

  }

  public function toArray() {

    return $this->data->toArray();

  }

}