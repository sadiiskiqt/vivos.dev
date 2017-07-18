<?php

use Illuminate\Database\Migrations\Migration;

class CreatePatternsVersionsTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('patterns_versions', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');
      $table->text("text")->nullable();
      $table->string("view", 255)->nullable();
      $table->integer("user_id")->nullable();
      $table->string("language", 255)->nullable();
      $table->integer("pattern_id")->nullable();
      $table->integer("version_id")->nullable();
      $table->integer("active")->nullable();           
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('patterns_versions');
  }

}
