<?php

use Illuminate\Database\Migrations\Migration;

class CreateMultisitesSessionsTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('multisites_sessions', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');      
      $table->integer("logged_user");
      $table->string("key", 255)->nullable();
      $table->string("value", 255)->nullable();
      $table->string("ip", 255)->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('multisites_sessions');
  }

}
