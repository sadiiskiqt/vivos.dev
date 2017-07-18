<?php

use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('roles', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');
      $table->string("name", 255);
      $table->text("description");      

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('roles');
  }

}
