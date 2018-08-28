<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public function students (){
        return $this->belongsToMany('App\Models\Student','schedule_student','schedule_id','student_id');
    }

    public function subject(){
        return $this->belongsTo('App\Models\Subject','subject_id');
    }

    public function tutor(){
        return $this->belongsTo('App\Models\Tutor','tutor_id');
    }
}
