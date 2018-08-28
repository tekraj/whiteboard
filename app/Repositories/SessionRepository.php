<?php
namespace App\Repositories;


use App\Models\StudentSession;
use App\Models\TutorSession;
use Carbon\Carbon;

class SessionRepository{
    public static function getStudentSessions($userId,$filters=[]){
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        if(isset($filters['start_date']) && !empty($filters['start_date'])){
            $startDate = Carbon::parse($filters['start_date'])->startOfDay();
        }
        if(isset($filters['end_date']) && !empty($filters['end_date'])) {
            $endDate = Carbon::parse(($filters['end_date']))->endOfDay();
        }

        $session = StudentSession::selectRaw("student_sessions.start_time,student_sessions.end_time,tutors.name as tutor_name,student_sessions.session_id,students.name,subjects.name as subject")
            ->join('students','students.id','=','student_sessions.student_id')
            ->join('subjects','subjects.id','=','student_sessions.subject_id')
            ->join('tutors','tutors.id','=','student_sessions.tutor_id')
            ->where('student_sessions.student_id',$userId)
            ->whereBetween('student_sessions.start_time',[$startDate,$endDate])
            ->groupBy('student_sessions.id')->get();
        return $session;
    }


    public static function getTutorSessions($userId,$filters=[]){
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        if(isset($filters['start_date']) && !empty($filters['start_date'])){
            $startDate = Carbon::parse($filters['start_date'])->startOfDay();
        }
        if(isset($filters['end_date']) && !empty($filters['end_date'])) {
            $endDate = Carbon::parse(($filters['end_date']))->endOfDay();
        }
        $session = TutorSession::selectRaw("tutor_sessions.start_time,tutor_sessions.end_time,tutor_sessions.session_id,tutors.name,subjects.name as subject")
            ->join('tutors','tutors.id','=','tutor_sessions.tutor_id')
            ->join('subjects','subjects.id','=','tutor_sessions.subject_id')
            ->where('tutor_sessions.tutor_id',$userId)
            ->whereBetween('tutor_sessions.start_time',[$startDate,$endDate])
            ->groupBy('tutor_sessions.id')->get();
        return $session;
    }
}