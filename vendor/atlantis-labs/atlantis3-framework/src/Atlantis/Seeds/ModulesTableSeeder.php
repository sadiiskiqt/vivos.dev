<?php

namespace Atlantis\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulesTableSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {

    $data = $this->getData();

    foreach ($data as $row) {
      DB::table('modules')->insert($row);
    }
  }

  private function getData() {

    $date = \Carbon\Carbon::now()->toDateTimeString();
    
    return [
        [
            'name' => 'Site',
            'author' => 'Atlantis CMS',
            'version' => '1.0',
            'namespace' => 'Module\Site',
            'path' => 'atlantis/site/src',
            'provider' => 'Module\Site\Providers\SiteServiceProvider',
            'created_at' => $date,
            'updated_at' => $date
        ]
        /*
        [
            'name' => 'Google Analytics',
            'author' => 'Atlantis CMS',
            'version' => '1.0',
            'namespace' => 'Module\GoogleAnalytics',
            'path' => 'atlantis/googleanalytics/src',
            'provider' => 'Module\GoogleAnalytics\Providers\GoogleAnalyticsServiceProvider'
        ],
        
        [
            'name' => 'Blog',
            'author' => 'Atlantis CMS',
            'version' => '1.0',
            'namespace' => 'Module\Blog',
            'path' => 'atlantis/blog/src',
            'provider' => 'Module\Blog\Providers\BlogServiceProvider'
        ],
        [
            'name' => 'Vanity Url',
            'author' => 'Atlantis CMS',
            'version' => '1.0',
            'namespace' => 'Module\Vanityurl',
            'path' => 'atlantis/vanityurl/src',
            'provider' => 'Module\Vanityurl\Providers\VanityurlServiceProvider'
        ],
        [
            'name' => 'Navis',
            'author' => 'Atlantis CMS',
            'version' => '1.0',
            'namespace' => 'Module\Navis',
            'path' => 'atlantis/navis/src',
            'provider' => 'Module\Navis\Providers\NavisServiceProvider'
        ],
        [
            'name' => 'Api',
            'author' => 'Atlantis CMS',
            'version' => '1.0',
            'namespace' => 'Module\Api',
            'path' => 'atlantis/api/src',
            'provider' => 'Module\Api\Providers\ApiServiceProvider'
        ],         
        [
            'name' => 'Search',
            'author' => 'Atlantis CMS',
            'version' => '1.0',
            'namespace' => 'Module\Search',
            'path' => 'atlantis/search/src',
            'provider' => 'Module\Search\Providers\SearchServiceProvider'
        ]
         * 
         */
    ];
  }

}
