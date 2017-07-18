<?php

namespace Atlantis\Models\Pattern;

use Atlantis\Helpers\RegexMatcher as RegexMatcher;
use Atlantis\Helpers\Tools as Tools;

class Text {

  protected $data;

  public function __construct($oData) {

    $this->data = $oData;
  
  }
  
    public function init() {

     $text = $this->data->text; 
      
    /* searching for patternfunc calls inside the pattern itself */
    $matches = RegexMatcher::matchPatternFunc($this->data->text);

    if (count($matches[1]) > 0) {

      foreach ($matches[1] as $func) {

        $response = Tools::makeAppCallFromString($func);

        $text = RegexMatcher::removePatternFunc($func, $response, $text);
        
      }
    }

    return $text;
  }
  
}