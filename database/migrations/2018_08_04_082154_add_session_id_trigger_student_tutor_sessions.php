<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class AddSessionIdTriggerStudentTutorSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::unprepared("
                CREATE TRIGGER IF NOT EXIST insert_session_id_to_student_session
                BEFORE INSERT ON student_sessions
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
