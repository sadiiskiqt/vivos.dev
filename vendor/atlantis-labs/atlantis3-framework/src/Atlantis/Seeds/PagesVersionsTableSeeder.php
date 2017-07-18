<?php

namespace Atlantis\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesVersionsTableSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {

    $data = $this->getData();

    foreach ($data as $row) {
      DB::table('pages_versions')->insert($row);
    }
  }

  private function getData() {

    $date = \Carbon\Carbon::now()->toDateTimeString();

    return [
        [
            'page_id' => 1,
            'version_id' => 1,
            'page_body' => '<h1>Welcome to Atlantis 3!</h1>

<h4>We are excited to have you working with Atlantis and have prepared some helpful links to get you started.</h4>

<h4><a class="button" href="http://www.atlantis-cms.com/faq">FAQ</a><a class="button" href="http://www.atlantis-cms.com/user-docs">View documentation</a><a class="button" href="http://www.atlantis-cms.com/docs">For Developers</a></h4>

<h4>If you have any questions, or suggestions, or anything else you want to share with us...please give us your feedback <a href="http://www.atlantis-cms.com/feedback" target="_blank">here</a> or join the discussion on <a href="http://forum.atlantis-cms.com/" target="_blank">our forum</a>.</h4>
',
            'user_id' => 1,
            'meta_description' => 'description',
            'meta_keywords' => 'keywords',
            'language' => 'en',
            'active' => 1,
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'page_id' => 2,
            'version_id' => 1,
            'page_body' => '',
            'user_id' => 1,
            'seo_title' => 'Page not Found',
            'meta_description' => '',
            'meta_keywords' => '',
            'language' => 'en',
            'active' => 1,
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'page_id' => 3,
            'version_id' => 1,
            'page_body' => '',
            'user_id' => 1,
            'seo_title' => 'Site Login',
            'meta_description' => '',
            'meta_keywords' => '',
            'language' => 'en',
            'active' => 1,
            'created_at' => $date,
            'updated_at' => $date
        ]
    ];
  }

}
