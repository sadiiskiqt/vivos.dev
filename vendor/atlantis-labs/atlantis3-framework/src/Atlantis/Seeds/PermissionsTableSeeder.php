<?php

namespace Atlantis\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder {

  private $data = [       
      [
          'role_id' => 4,
          'type' => 'pages',
          'value' => 1
      ],
      [
          'role_id' => 4,
          'type' => 'patterns',
          'value' => 1
      ],
      [
          'role_id' => 4,
          'type' => 'media',
          'value' => 1
      ]
  ];

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {

    foreach ($this->data as $row) {
      
      DB::table('permissions')->insert($row);
    }
  }

}
