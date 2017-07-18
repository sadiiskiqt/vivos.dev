<?php

use Illuminate\Database\Migrations\Migration;

class CreatePatternsTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('patterns', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');
      $table->string("name", 255)->nullable();
      $table->string("url", 255)->nullable();
      $table->string("type", 255)->nullable();
      $table->string("outputs", 255)->nullable();
      $table->integer("status")->nullable();
      $table->integer("weight")->nullable();
      $table->dateTime("start_date")->nullable();
      $table->dateTime("end_date")->nullable();
      $table->string("mobile", 255)->nullable();              
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('patterns');
  }

}
