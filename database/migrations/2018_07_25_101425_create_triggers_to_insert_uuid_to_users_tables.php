<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateTriggersToInsertUuidToUsersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
                CREATE TRIGGER insert_uuid_admins
                BEFORE INSERT ON admins
                FOR EACH  ROW
                BEGIN
                    SET NEW.uuid = UUID();
                END;
               ");
        DB::unprepared("
                CREATE TRIGGER insert_uuid_students
                BEFORE INSERT ON students
                FOR EACH  ROW
                BEGIN
                    SET NEW.uuid = UUID();
                END;
               ");
        DB::unprepared("
                CREATE TRIGGER insert_uuid_tutors
                BEFORE INSERT ON tutors
                FOR EACH  ROW
                BEGIN
                    SET NEW.uuid = UUID();
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
