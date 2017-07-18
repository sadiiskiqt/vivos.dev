<?php

/*
  |--------------------------------------------------------------------------
  | MULTI SITES
  |--------------------------------------------------------------------------
  |
  | Change MULTI_SITES=true in .env
  | Add all sites in this config
  |
  | If all sites is separated with new atlantis installation
  | please copy this config (multi-sites.php) in all /config folders.
  |
 */

return [

    /**
     * random string
     * needs to be the same in all sites
     */
    'key' => 'kjasdkjqowe1341sewwq2bnmx',
    
    'sites' => [
        'site-1' => [
            'domain' => 'http://a3.dev.gentecsys.net',
            'name' => 'Site 1',
            /*
             * Only one can be a master site
             */
            'master' => TRUE
        ],
        
        'site-2' => [
            'domain' => 'http://a3.angel.dev.gentecsys.net',
            'name' => 'Site 2',
            'master' => FALSE
        ]
    ]
];
