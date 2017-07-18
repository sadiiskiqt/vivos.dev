<?php

namespace %mod_namespace%\%capital_name%\Models;

use Atlantis\Models\Base;

class Search extends Base {

  public static function get($search) {
     
     //DO all operations here , need to return an array with  url / name keypair
     
     return ['/demo-page' => 'name'];    
   }
}