<?php

namespace Atlantis\Helpers;

class AtlantisRedirect {

  private $redirect;
  
  public function __construct() {

    $this->redirect = NULL;
  }

  public function set($redirect) {

    $this->redirect = $redirect;
  }  

  public function get() {
    return $this->redirect;
  }

}
