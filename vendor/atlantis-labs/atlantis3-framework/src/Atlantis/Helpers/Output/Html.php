<?php

namespace Atlantis\Helpers\Output;

class Html implements \Atlantis\Helpers\Interfaces\IOutputInterface {
  
  public function output($params, $source) {
    
     if( \View::exists( 'atlantis::page/' . $source )) {
             return \View('atlantis::page/' . $source ,  $params );
     }
    
  }
  
}