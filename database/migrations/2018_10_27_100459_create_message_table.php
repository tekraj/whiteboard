<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('messages', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('from_id');
            $table->unsignedInteger('to_id');
            $table->text('message');
            $table->enum('user_type',['tutor','student']);
            $table->string('user_name');
            $table->timestamps();
            $table->softDeletes();
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
