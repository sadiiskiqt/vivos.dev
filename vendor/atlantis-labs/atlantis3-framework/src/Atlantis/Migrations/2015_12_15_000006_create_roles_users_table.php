<?php

use Illuminate\Database\Migrations\Migration;

class CreateRolesUsersTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('roles_users', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');
      $table->integer("user_id");
      $table->integer("role_id");
      

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('roles_users');
  }

}
