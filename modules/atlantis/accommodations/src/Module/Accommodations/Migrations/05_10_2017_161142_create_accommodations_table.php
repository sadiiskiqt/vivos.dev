<?php

/*
 * Migration: Accommodations
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class CreateAccommodationsTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {

        Schema::create('accommodations', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string("room_title");
            $table->string("booking_link");
            $table->string("gallery_id");//to integer
            $table->text('body', false, true);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('accommodations');
    }

}
