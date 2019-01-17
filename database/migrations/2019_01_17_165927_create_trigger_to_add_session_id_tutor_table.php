<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateTriggerToAddSessionIdTutorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('tutor_sessions');
        Schema::create('tutor_sessions',function(Blueprint $table){
            $table->increments('id');
            $table->string('session_id')->unique();
            $table->unsignedInteger('tutor_id');
            $table->unsignedInteger('subject_id');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->timestamps();
            $table->foreign('tutor_id')->references('id')->on('tutors');
            $table->foreign('subject_id')->references('id')->on('subjects');
        });
        DB::unprepared("
               CREATE TRIGGER  insert_session_id_to_tutor_session
                BEFORE INSERT ON tutor_sessions
                FOR EACH  ROW
                BEGIN
                    SET NEW.session_id = UUID();
                END;
           ");
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
