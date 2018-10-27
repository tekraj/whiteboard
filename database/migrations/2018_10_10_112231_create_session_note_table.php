<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_notes', function (Blueprint $table){
            $table->increments('id');
            $table->text('note');
            $table->unsignedInteger('user_id');
            $table->enum('user_type',['tutor','student']);
            $table->unsignedInteger('subject_id');
            $table->timestamps();
            $table->foreign('subject_id')->references('id')->on('subjects');
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
