<?php

use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {

    if (!Schema::hasTable('menus')) {
      Schema::create('menus', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->increments('id');
        $table->string("name", 255)->nullable();
        $table->text('menu_attributes');
        $table->string("css", 255)->nullable();
        $table->string("element_id", 255)->nullable();
        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {

    if (Schema::hasTable('menus')) {
      Schema::drop('menus');
    }
  }

}
