<?php

use Illuminate\Database\Migrations\Migration;

class CreatePatternsMasksTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('patterns_masks', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');
      $table->integer("pattern_id")->nullable();
      $table->string("mask", 255)->nullable();       
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('patterns_masks');
  }

}
