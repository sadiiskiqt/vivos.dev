<?php

/*
 * Config: CKEditor
 * @Atlantis CMS
 * v 1.0
 */

return [
   /*
    * $this->app->bind("Module\CKEditor", "Atlantis\\CKEditor\\Controllers\\CKEditorController");
    * 
    * 'appBind' => [
    *    'Module\CKEditor' => 'Module\\CKEditor\\Controllers\\CKEditorController'
    *],
    *[
    *    'Module\CKEditor\CustomController' => 'Module\\CKEditor\\Controllers\\CustomController'
    *]
    */
    'appBind' => [
        'Module\CKEditor' => 'Module\\CKEditor\\Controllers\\CKEditorController'
    ]
   ];
