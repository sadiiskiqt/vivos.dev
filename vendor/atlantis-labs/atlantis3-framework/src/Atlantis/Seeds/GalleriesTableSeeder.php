<?php

namespace Atlantis\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GalleriesTableSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {

    $data = $this->getData();

    foreach ($data as $row) {
      DB::table('galleries')->insert($row);
    }
  }

  private function getData() {

    $date = \Carbon\Carbon::now()->toDateTimeString();

    return [
        [
            'id' => 1,
            'name' => 'Page Preview',
            'description' => 'This is a special gallery used to hold page preview thumbnails. You can attach them to a page using the Page sidebar menu - Related Image.',
            'created_at' => $date,
            'updated_at' => $date
        ]
    ];
  }

}
