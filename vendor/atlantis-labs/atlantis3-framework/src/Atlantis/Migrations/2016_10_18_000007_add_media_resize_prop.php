<?php

use Illuminate\Database\Migrations\Migration;

class AddMediaResizeProp extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    if (!Schema::hasColumn('media', 'resize')) {
      Schema::table('media', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->text("resize")->nullable();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    if (Schema::hasColumn('media', 'resize')) {
      Schema::table('media', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("resize");
      });
    }
  }
  
  
}
