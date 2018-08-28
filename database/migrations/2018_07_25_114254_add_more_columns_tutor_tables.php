<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnsTutorTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tutors', function (Blueprint $table){
            $table->unsignedInteger('subject_id')->after('uuid');
            $table->string('contact_no')->after('email');
            $table->enum('gender',['male','female','others'])->after('contact_no');
            $table->string('address')->after('gender');
            $table->string('profile_pic')->after('address');
            $table->string('school_name')->after('profile_pic')->nullable();
            $table->date('dob')->nullable()->after('school_name');
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
