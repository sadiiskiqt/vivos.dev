<?php

namespace Atlantis\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesUsersTableSeeder extends Seeder {

  private $data = [
      [
          'user_id' => 1,
          'role_id' => 1
      ],
      [
          'user_id' => 1,
          'role_id' => 3
      ]
  ];

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {

    foreach ($this->data as $row) {
      DB::table('roles_users')->insert($row);
    }
  }
}
