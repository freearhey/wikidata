<?php

namespace Wikidata;

class Result
{

  private $data;

  public function __construct($data) {

    $this->data = collect($data);

  }

  public function __get($name) {

    return $this->get($name);

  }

  public function get($name) {

    return ($this->data->has($name)) ? $this->data->get($name) : null;

  }

  public function first() {

    return $this->data->first();

  }

  public function toArray() {

    return $this->data->toArray();

  }

}