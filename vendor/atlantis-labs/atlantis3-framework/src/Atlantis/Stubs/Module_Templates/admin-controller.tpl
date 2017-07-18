<?php

namespace %mod_namespace%\%capital_name%\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminModulesController;

class %capital_name%AdminController extends AdminModulesController {

  public function __construct() {
    parent::__construct(\Config::get('%lower_name%.setup'));
  }

  public function getIndex($id = null) {

    return view("%lower_name%-admin::admin/blank");
  }

}
