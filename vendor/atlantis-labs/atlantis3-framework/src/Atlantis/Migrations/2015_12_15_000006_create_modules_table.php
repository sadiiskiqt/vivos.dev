<?php

use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('modules', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');
      $table->string("name", 255)->nullable();
      $table->string("author", 255)->nullable();
      $table->string("version", 45)->nullable();
      $table->string("namespace", 255)->nullable();
      $table->string("path", 255)->nullable();
      $table->string("provider", 255)->nullable();
      $table->string("adminURL", 255)->nullable();
      $table->string("icon", 255)->nullable();
      $table->text("extra")->nullable();
      $table->integer("active")->default(1);
      $table->text("description");
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('modules');
  }

}
