<?php
namespace App\Repositories;


use App\Models\StudentSession;
use App\Models\TutorSession;
use Carbon\Carbon;

class SessionRepository{
    public static function getStudentSessions($userId,$subjectId,$daterange=''){
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        if(!empty($daterange)){
            $date_range = explode('-', $daterange);
            $startDate = Carbon::parse($date_range[0])->format('Y-m-d 00:00:00');
            $endDate = Carbon::parse($date_range[1])->format('Y-m-d 23:59:59');
        }


        $session = StudentSession::selectRaw("student_sessions.start_time,student_sessions.end_time,tutors.name as tutor_name,student_sessions.session_id,students.name,subjects.name as subject")
            ->join('students','students.id','=','student_sessions.student_id')
            ->join('subjects','subjects.id','=','student_sessions.subject_id')
            ->join('tutors','tutors.id','=','student_sessions.tutor_id')
            ->where('student_sessions.student_id',$userId)
            ->where('student_sessions.subject_id',$subjectId)
            ->whereBetween('student_sessions.start_time',[$startDate,$endDate])
            ->groupBy('student_sessions.id')->get();

        return $session;
    }


    public static function getTutorSessions($userId,$subjectId,$daterange=''){

        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        if(!empty($daterange)){
            $date_range = explode('-', $daterange);
            $startDate = Carbon::parse($date_range[0])->format('Y-m-d 00:00:00');
            $endDate = Carbon::parse($date_range[1])->format('Y-m-d 23:59:59');
        }
        $session = TutorSession::selectRaw("tutor_sessions.start_time,tutor_sessions.end_time,tutor_sessions.session_id,tutors.name,subjects.name as subject")
            ->join('tutors','tutors.id','=','tutor_sessions.tutor_id')
            ->join('subjects','subjects.id','=','tutor_sessions.subject_id')
            ->where('tutor_sessions.tutor_id',$userId)
            ->where('tutor_sessions.subject_id',$subjectId)
            ->whereBetween('tutor_sessions.start_time',[$startDate,$endDate])
            ->groupBy('tutor_sessions.id')->get();
        return $session;
    }
}