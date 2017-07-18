<?php

 namespace %mod_namespace%\%capital_name%\Seed;

/*
 * Seed: %capital_name%
 * @Atlantis CMS
 * v 1.0
 */

class %capital_name%Seeder extends \Illuminate\Database\Seeder
{

  public function run()
  {

     $setup = require(base_path(). '/modules/%mod_dir%/%lower_name%/src/%mod_namespace%/%capital_name%/Setup/Setup.php');

     //check for the module with the same name
    $result = \DB::table("modules")
            ->where("name", "=", $setup['name'])->first();

    if (is_null($result)) {

      \DB::table("modules")
              ->insert([
                  'name' => $setup['name'],
                  'author' => $setup['author'],
                  'version' => $setup['version'],
                  'namespace' => $setup['moduleNamespace'],
                  'path' => $setup['path'],
                  'provider' => $setup['provider'],
                  'extra' => serialize($setup['extra']),
                  'adminURL' => $setup['adminURL'],
                  'icon' => $setup['icon'],
                  'active' => 1,
                  'description' => $setup['description'],
                  'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                  'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
              ]);

     }
  }

}
