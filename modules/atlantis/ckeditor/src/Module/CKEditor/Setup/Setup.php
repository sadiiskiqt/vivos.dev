<?php

/*
 * Setup: CKEditor
 * @Atlantis CMS
 * v 1.1.1
 */

return [
    'name' => 'CKEditor',
    'author' => 'Atlantis CMS',
    'version' => '1.1.3',
    'adminURL' => NULL, // admin/modules/ckeditor
    /**
     * ex. icon icon-Files
     * http://docteur-abrar.com/wp-content/themes/thunder/admin/stroke-gap-icons/index.html
     * 
     * ex. fa fa-beer
     * http://fontawesome.io/icons/
     */
    'icon' => 'icon icon-Pencil',
    'path' => 'atlantis/ckeditor/src',
    'moduleNamespace' => 'Module\CKEditor',
    'seedNamespace' => 'Module\CKEditor\Seed',
    'seeder' => '\Module\CKEditor\Seed\CKEditorSeeder',
    'provider' => 'Module\CKEditor\Providers\CKEditorServiceProvider',
    'migration' => 'modules/atlantis/ckeditor/src/Module/CKEditor/Migrations/',
    'extra' => [
        /**
         * only for editor modules like CKEditor, Redaktor...
         */
        'type' => 'editor',
        'editorClass' => 'Module\CKEditor\CKEditorBuilder'
    ],
    'description' => 'The famous WYSIWYG editor.'
];
