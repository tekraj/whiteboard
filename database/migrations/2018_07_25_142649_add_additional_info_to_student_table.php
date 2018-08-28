<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalInfoToStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function(Blueprint $table){
            $table->unsignedInteger('class_id')->after('uuid');
            $table->string('address')->nullable()->after('email');
            $table->string('contact_no')->after('address')->nullable();
            $table->date('dob')->after('contact_no')->nullable();
            $table->enum('gender',['male','female','others'])->after('dob');
            $table->string('profile_pic')->nullable()->after('gender');
            $table->string('school_name')->nullable()->after('profile_pic');
            $table->string('father_name')->nullable()->after('school_name');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->foreign('class_id')->references('id')->on('classes');
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
