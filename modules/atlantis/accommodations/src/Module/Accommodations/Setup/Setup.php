<?php

/*
 * Setup: Accommodations
 * @Atlantis CMS
 * v 1.0
 */

return [
    'name' => 'Accommodations',
    'author' => 'Atlantis CMS',
    'version' => '1.0',
    'adminURL' => 'admin/modules/accommodations', // admin/modules/accommodations
    /**
     * ex. icon icon-Files
     * http://docteur-abrar.com/wp-content/themes/thunder/admin/stroke-gap-icons/index.html
     *
     * ex. fa fa-beer
     * http://fontawesome.io/icons/
     */
    'icon' => 'icon icon-House',
    'path' => 'atlantis/accommodations/src',
    'moduleNamespace' => 'Module\Accommodations',
    'seedNamespace' => 'Module\Accommodations\Seed',
    'seeder' => '\Module\Accommodations\Seed\AccommodationsSeeder',
    'provider' => 'Module\Accommodations\Providers\AccommodationsServiceProvider',
    'migration' => 'modules/atlantis/accommodations/src/Module/Accommodations/Migrations/',
    'extra' => NULL,
    'description' => 'Accommodations'
];
