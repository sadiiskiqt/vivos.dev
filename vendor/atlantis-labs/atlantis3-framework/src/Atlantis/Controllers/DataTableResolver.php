<?php

namespace Atlantis\Controllers;

use App\Http\Controllers\Controller;

class DataTableResolver extends Controller {

  public function resolve() {
    
    $namespaceClass = rawurldecode(request()->get('namespaceClass'));
   
    $dataTableClass = new $namespaceClass();
    
    return $dataTableClass->getData(request());    
  }

}
