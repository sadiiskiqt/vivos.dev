<?php

namespace Atlantis\Helpers\Output;

class Json implements \Atlantis\Helpers\Interfaces\IOutputInterface {
  
  public function output($source, $params) {
    
    if( is_array( $source )) {
      
         return \Response::make(json_encode($source), '200')->header('Content-Type', 'application/json');
       
    }
  }
  
}