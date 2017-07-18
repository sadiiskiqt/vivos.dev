<?php

use Illuminate\Database\Migrations\Migration;

class CreatePagesVersionsTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('pages_versions', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');
      $table->integer("page_id");
      $table->integer("version_id")->nullable();      
      $table->text("page_body")->nullable();
      $table->text("excerpt")->nullable();
      $table->text("related_title")->nullable();
      $table->integer("user_id");
      $table->text("notes")->nullable();
      $table->text("mobile_body")->nullable();
      $table->string("seo_title", 255)->nullable();
      $table->text("meta_description")->nullable();
      $table->text("meta_keywords")->nullable();
      $table->string("language", 255)->nullable();
      $table->integer("active")->nullable();     
      
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('pages_versions');
  }

}
