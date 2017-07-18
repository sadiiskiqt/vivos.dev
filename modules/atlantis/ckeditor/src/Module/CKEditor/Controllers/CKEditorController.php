<?php

 namespace Module\CKEditor\Controllers;

/*
 * Controller: CKEditor
 * @Atlantis CMS
 * v 1.0
 */

use App\Http\Controllers\Controller;

class CKEditorController extends Controller
{


  public function __construct()
  {


  }

  public function index()
  {

       return \View::make('ckeditor::admin/blank' ,  [ 'msg'  => "Demo" ] );

  }

}
