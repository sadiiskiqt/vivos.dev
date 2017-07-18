<?php

namespace Atlantis\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {

    $data = $this->getData();

    foreach ($data as $row) {
      DB::table('users')->insert($row);
    }
  }

  private function getData() {

    $date = \Carbon\Carbon::now()->toDateTimeString();

    return [
        [
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@atlantis-cms.com',
            'password' => Hash::make('admin123'),
            'language' => 'en',
            'created_at' => $date,
            'updated_at' => $date
        ]
    ];
  }

}
