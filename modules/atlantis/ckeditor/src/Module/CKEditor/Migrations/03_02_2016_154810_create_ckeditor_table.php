<?php

/*
 * Migration: CKEditor
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class CreateCKEditorTable extends Migration {

        /**
        * Run the migrations.
        */
        public function up()
        {
                /*
                Schema::create('ckeditor', function(\Illuminate\Database\Schema\Blueprint $table)
                {
                        $table->increments('id');
                        $table->string("title");
                        $table->timestamps();
                });
                * 
                */
        }

        /**
        * Reverse the migrations.
        */
        public function down()
        {
                //
        }

}
