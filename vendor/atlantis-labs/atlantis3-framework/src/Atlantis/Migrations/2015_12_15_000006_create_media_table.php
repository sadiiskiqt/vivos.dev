<?php

use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    //
    Schema::create('media', function(\Illuminate\Database\Schema\Blueprint $table) {
      $table->increments('id');
      $table->string("filename", 255)->nullable();
      $table->string("original_filename", 255)->nullable(); 
      $table->string("tablet_name", 255)->nullable();      
      $table->string("phone_name", 255)->nullable();  
      $table->string("filesize", 255)->nullable();
      $table->string("thumbnail", 255)->nullable();
      $table->text("caption")->nullable();
      $table->string("credit", 255)->nullable();
      $table->text("description")->nullable();
      $table->string("type", 255)->nullable();
      $table->string("alt", 255)->nullable();
      $table->integer("weight")->nullable();
      $table->text("css")->nullable();
      $table->string("anchor_link", 255)->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    Schema::drop('media');
  }

}
