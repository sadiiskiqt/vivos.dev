<?php

use Illuminate\Database\Migrations\Migration;

class AddPageCanonicalUrl extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    if (!Schema::hasColumn('pages', 'canonical_url')) {
      Schema::table('pages', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->string("canonical_url", 255)->nullable();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    if (Schema::hasColumn('pages', 'gallery_id')) {
      Schema::table('pages', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("canonical_url");
      });
    }
  }
  
  
}
