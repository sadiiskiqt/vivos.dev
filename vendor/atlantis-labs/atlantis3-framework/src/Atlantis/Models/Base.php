<?php

namespace Atlantis\Models;

use Illuminate\Database\Eloquent\Model;

class Base extends Model {

  protected static $rules;
  protected $errors;

  public function getScenario() {
    return static::$rules[$this->scenario];
  }

  public function setScenario($name) {
    if (array_key_exists($name, static::$rules)) {
      $this->scenario = $name;
    }
  }

  public function validate() {

    $validate = \Illuminate\Support\Facades\Validator::make($this->attributes, $this->getScenario());

    if ($validate->passes()) {
      return true;
    }

    $this->errors = $validate->messages();

    return false;
  }

  public function getErrors() {
    return $this->errors;
  }

}