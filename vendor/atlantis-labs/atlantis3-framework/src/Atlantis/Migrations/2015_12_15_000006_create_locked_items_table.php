<?php

use Illuminate\Database\Migrations\Migration;

class CreateLockedItemsTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('locked_items', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');
      $table->string("item_type", 255)->nullable();
      $table->integer("item_id")->nullable();
      $table->integer("user_id")->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('locked_items');
  }

}
