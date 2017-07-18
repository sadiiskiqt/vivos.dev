<?php

use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('pages', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');
      $table->string("name", 255);
      $table->string("path", 255)->nullable();
      $table->string("url", 255);
      $table->integer("categories_id")->nullable();
      $table->string("author", 255)->nullable();
      $table->string("template", 255)->nullable();
      $table->integer("is_ssl")->default(0);
      $table->integer("status")->default(1);
      $table->dateTime("start_date")->nullable();
      $table->dateTime("end_date")->nullable();
      $table->text("styles")->nullable();
      $table->text("scripts")->nullable();
      $table->integer("user")->nullable();
      $table->string("mobile_template", 255)->nullable();
      $table->integer("cache")->default(1);
      $table->integer("preview_thumb_id")->nullable();
      $table->integer("protected")->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('pages');
  }

}
