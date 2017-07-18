<?php


/*
 * Setup: Menus
 * @Atlantis CMS
 * v 1.2.1
 */

return [
    'name' => 'Menus',
    'author' => 'Atlantis CMS',
    'version' => '1.2.1',
    'adminURL' => 'admin/modules/menus', // admin/modules/menus
    /**
     * ex. icon icon-Files
     * http://docteur-abrar.com/wp-content/themes/thunder/admin/stroke-gap-icons/index.html
     * 
     * ex. fa fa-beer
     * http://fontawesome.io/icons/
     */
    'icon' => 'icon icon-Menu',
    'path' => 'atlantis/menus/src',
    'moduleNamespace' => 'Module\Menus',
    'seedNamespace' => 'Module\Menus\Seed',    
    'seeder' => '\Module\Menus\Seed\MenusSeeder',
    'provider' => 'Module\Menus\Providers\MenusServiceProvider',
    'migration' => 'modules/atlantis/menus/src/Module/Menus/Migrations/',
    'extra' => NULL,
    'description' => 'Build and manipulate navigational menus for your site.'
   ];
