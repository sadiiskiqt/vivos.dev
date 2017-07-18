<?php

use Illuminate\Database\Migrations\Migration;

class CreateConfigTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('config', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');
      $table->string("config_key", 255);
      $table->text("config_value")->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('config');
  }

}
