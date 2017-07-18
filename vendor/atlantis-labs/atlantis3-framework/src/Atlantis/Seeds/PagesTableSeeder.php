<?php

namespace Atlantis\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesTableSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {

    $data = $this->getData();

    foreach ($data as $row) {
      DB::table('pages')->insert($row);
    }
  }

  private function getData() {

    $date = \Carbon\Carbon::now()->toDateTimeString();

    return [
        [
            'id' => 1,
            'name' => 'home page',
            'url' => '/',
            'categories_id' => 1,
            'author' => 'admin',
            'template' => 'default',
            'is_ssl' => 0,
            'status' => 1,
            'user' => 1,
            'mobile_template' => 'default',
            'cache' => 1,
            'protected' => 0,
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'id' => 2,
            'name' => '404',
            'url' => '404',
            'categories_id' => 0,
            'author' => 'admin',
            'template' => '404',
            'is_ssl' => 0,
            'status' => 1,
            'user' => 1,
            'mobile_template' => '404',
            'cache' => 1,
            'protected' => 0,
            'created_at' => $date,
            'updated_at' => $date
        ],
        [
            'id' => 3,
            'name' => 'Site Login',
            'url' => 'site-login',
            'categories_id' => 0,
            'author' => 'admin',
            'template' => 'site-login',
            'is_ssl' => 0,
            'status' => 1,
            'user' => 1,
            'mobile_template' => 'site-login',
            'cache' => 1,
            'protected' => 0,
            'created_at' => $date,
            'updated_at' => $date
        ]
    ];
  }

}
