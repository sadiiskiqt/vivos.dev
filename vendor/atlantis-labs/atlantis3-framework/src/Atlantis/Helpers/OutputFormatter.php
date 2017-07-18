<?php

namespace Atlantis\Helpers;

class OutputFormatter {

  private $formatterClass;
  private $source;


  public function __construct( $formatterClass, $source ) {

    $this->formatterClass = $formatterClass;

    $this->source = $source;

  }

  public function output($params = null) {

      return $this->formatterClass->output($this->source, $params);

  }

}
