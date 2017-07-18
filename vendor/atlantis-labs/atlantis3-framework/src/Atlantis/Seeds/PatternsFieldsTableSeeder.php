<?php

namespace Atlantis\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatternsFieldsTableSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {

    $data = $this->getData();

    foreach ($data as $row) {
      DB::table('patterns_fields')->insert($row);
    }
  }

  private function getData() {

    return [];
  }

}
