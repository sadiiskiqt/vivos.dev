<?php

use Illuminate\Database\Migrations\Migration;

class CreateMenuCachesTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {

    if (!Schema::hasTable('menu_caches')) {
      Schema::create('menu_caches', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->increments('id');
        $table->integer('menu_id')->nullable();
        $table->text('compiled');
        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {

    if (!Schema::hasTable('menu_caches')) {
      Schema::drop('menu_caches');
    }
  }

}
