<?php

use Illuminate\Database\Migrations\Migration;

class CreatePatternsFieldsTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('patterns_fields', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');
      $table->integer("pattern_id")->nullable();
      $table->string("key", 255)->nullable();
      $table->text("value")->nullable();           
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('patterns_fields');
  }

}
