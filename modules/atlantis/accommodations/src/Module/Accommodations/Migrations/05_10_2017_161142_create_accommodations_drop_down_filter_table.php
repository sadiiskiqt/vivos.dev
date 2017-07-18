<?php

/*
 * Migration: Accommodations
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class CreateAccommodationsDropDownFilterTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {

        Schema::create('accommodations_option_filter', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->integer("optionId");
            $table->integer("roomId");
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('accommodations_option_filter');
    }

}
