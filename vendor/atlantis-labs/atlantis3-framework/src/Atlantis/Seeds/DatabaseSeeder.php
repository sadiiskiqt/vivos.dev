<?php

namespace Atlantis\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    Model::unguard();

    $this->call(PermissionsTableSeeder::class);
    $this->call(UsersTableSeeder::class);
    $this->call(RolesUsersTableSeeder::class);
    $this->call(RolesTableSeeder::class);
    $this->call(ConfigTableSeeder::class);
    $this->call(ModulesTableSeeder::class);
    $this->call(PagesTableSeeder::class);
    $this->call(PagesVersionsTableSeeder::class);
    $this->call(PagesCategoriesTableSeeder::class);
    $this->call(PatternsTableSeeder::class);
    $this->call(PatternsVersionsTableSeeder::class);
    $this->call(PatternsFieldsTableSeeder::class);
    $this->call(PatternsMasksTableSeeder::class);
    $this->call(GalleriesTableSeeder::class);
    
    $this->command->info('Tables seeded!');
    Model::reguard();
  }

}
