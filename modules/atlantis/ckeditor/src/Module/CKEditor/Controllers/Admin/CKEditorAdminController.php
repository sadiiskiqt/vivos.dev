<?php

namespace Module\CKEditor\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminModulesController;

class CKEditorAdminController extends AdminModulesController {

  public function __construct() {
    parent::__construct(\Config::get('ckeditor.setup'));
  } 

  public function getIndex($id = null) {

    return view("ckeditor-admin::admin/blank");
  }

}
