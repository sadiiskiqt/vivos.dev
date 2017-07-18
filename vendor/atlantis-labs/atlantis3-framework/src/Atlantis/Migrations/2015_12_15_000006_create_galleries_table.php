<?php

use Illuminate\Database\Migrations\Migration;

class CreateGalleriesTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('galleries', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');
      $table->string("name", 255)->nullable();
      $table->text("description")->nullable();
      $table->text("images")->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('galleries');
  }

}
