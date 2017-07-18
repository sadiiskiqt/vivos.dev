<?php

use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('tags', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');
      $table->string("resource", 255)->nullable();
      $table->integer("resource_id")->nullable();
      $table->string("tag", 255)->nullable();      
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('tags');
  }

}
