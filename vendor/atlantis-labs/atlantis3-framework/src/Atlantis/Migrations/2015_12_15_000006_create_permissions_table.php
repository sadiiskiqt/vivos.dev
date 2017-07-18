<?php

use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('permissions', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');
      $table->integer("role_id");
      $table->string("type", 255);
      $table->text("value");

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('permissions');
  }

}
