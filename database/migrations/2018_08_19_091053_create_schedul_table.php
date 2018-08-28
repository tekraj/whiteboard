<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function(Blueprint $table){
            $table->increments('id');
            $table->timestamp('schedule_start_time');
            $table->timestamp('schedule_end_time');
            $table->unsignedInteger('tutor_id');
            $table->unsignedInteger('subject_id');
            $table->unsignedInteger('created_by');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('tutor_id')->references('id')->on('tutors');
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
