<?php

use Illuminate\Database\Migrations\Migration;

class CreatePagesCategoriesTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('pages_categories', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');
      $table->string("category_name", 255)->nullable();
      $table->string("category_action", 255)->nullable();
      $table->string("category_string", 255)->nullable();
      $table->string("category_view", 255)->nullable();
      $table->string("category_url", 255)->nullable();    
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('pages_categories');
  }

}
