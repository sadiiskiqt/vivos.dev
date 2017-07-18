<?php

namespace Atlantis\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {

    $data = $this->getData();

    foreach ($data as $row) {
      DB::table('roles')->insert($row);
    }
  }

  private function getData() {

    return [
        [
            'id' => 1,
            'name' => 'admin-login',
            'description' => 'Grant access to admin.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ],
        [
            'id' => 2,
            'name' => 'site-login',
            'description' => 'Grant access to password protected pages.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ],
        [
            'id' => 3,
            'name' => 'admin',
            'description' => 'Administrative user, has access to everything.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ],
        [
            'id' => 4,
            'name' => 'editor',
            'description' => 'People with editorial privileges',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]
    ];
  }

}
