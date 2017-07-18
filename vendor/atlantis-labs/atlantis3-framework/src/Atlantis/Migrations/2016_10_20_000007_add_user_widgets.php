<?php

use Illuminate\Database\Migrations\Migration;

class AddUserWidgets extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    if (!Schema::hasColumn('users', 'widgets')) {
      Schema::table('users', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->text("widgets")->nullable();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    if (Schema::hasColumn('users', 'widgets')) {
      Schema::table('users', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("widgets");
      });
    }
  }
  
  
}
