<?php

/*
 * Migration: Accommodations
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class CreateAccommodationCheckboxCategoryTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {

        Schema::create('accommodations_checkbox_category', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string("sCheckboxTitle");
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('accommodations_checkbox_category');
    }

}
