<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;
    protected $table = 'subjects';

    public function students(){
        return $this->belongsToMany('App\Models\Student','student_subject','subject_id','student_id');
    }
}
