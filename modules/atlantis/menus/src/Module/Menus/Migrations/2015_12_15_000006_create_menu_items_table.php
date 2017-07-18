<?php

use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {

    if (!Schema::hasTable('menu_items')) {
      Schema::create('menu_items', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->increments('id');
        $table->integer('menu_id')->nullable();
        $table->integer('parent_id')->default(0);
        $table->integer('child_id')->default(0);
        $table->integer('weight')->default(1);
        $table->string('label', 255)->nullable();
        $table->string('url', 255)->nullable();
        $table->string('onclick', 255)->nullable();
        $table->string('class', 255)->nullable();
        $table->text('attributes');
        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {

    if (Schema::hasTable('menu_items')) {
      Schema::drop('menu_items');
    }
  }

}
