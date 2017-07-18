<?php

namespace Atlantis\Models\Interfaces;


interface IPatternInterface {
  
      public function buildPatterns($url);
      
      public function inlinePatterns($body);
      
      public function processPatterns($results);
    
}