<?php

namespace Atlantis\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatternsMasksTableSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {

    $data = $this->getData();

    foreach ($data as $row) {
      DB::table('patters_masks')->insert($row);
    }
  }

  private function getData() {

    return [];
  }

}
