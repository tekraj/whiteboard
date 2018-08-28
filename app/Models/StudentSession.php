<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSession extends Model
{
    public function tutor(){
        return $this->belongsTo('App\Models\Tutor','tutor_id');
    }

    public function subject(){
        return $this->belongsTo('App\Models\Subject','subject_id');
    }
}
