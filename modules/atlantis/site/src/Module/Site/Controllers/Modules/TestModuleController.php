<?php

namespace Module\Site\Controllers\Modules;

use App\Http\Controllers\Controller;

/**
 * TestModuleController
 * exemplary front-end controller
 * This controller is used to overrides module controller
 */
class TestModuleController extends Controller {

  /**
   * overrides function from: \Module\TestModule\Controllers\TestModuleController
   * @param type $aParams
   * @return type
   */
  public function testFunc($aParams = NULL) {   
    
    return 'hello';
  }

}
