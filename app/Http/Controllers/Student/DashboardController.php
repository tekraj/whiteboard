<?php

namespace App\Http\Controllers\Student;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use App\Models\Schedule;
use View;
class DashboardController extends Controller
{
    public function index()
    {
        $pageTitle = 'Dashboard';
        $user = Auth::guard('student')->user();
        $subjects = Subject::where('status', 1)->pluck('name', 'id')->toArray();

        $monthStartDate = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
        $monthEndDate = Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59');
        $allSchedules = Schedule::selectRaw("schedules.*,tutors.name as tutor_name,subjects.name as subject_name ")
            ->join('schedule_student','schedule_student.schedule_id','=','schedules.id')
            ->join('tutors','tutors.id','=','schedules.tutor_id')
            ->join('subjects','subjects.id','=','schedules.subject_id')
            ->where('schedule_student.student_id',$user->id)
            ->whereBetween('schedules.schedule_start_time',[$monthStartDate,$monthEndDate])
            ->groupBy('schedules.id')
            ->get();

        return view('student.dashboard', compact('pageTitle', 'user', 'allSchedules','subjects'));
    }

    public function processDashboard(Request $request)
    {
        $subject = $request->subject_id;
        $request->session()->put('student_subject', $subject);
        return redirect('student/whiteboard');
    }

    /**
     * Method to get calender
     * @param Request $request
     */
    public function getCalender(Request $request){
        $user = Auth::guard('student')->user();
        $month = $request->month;
        if($month>12)
            $month = 12;
        if($month<1)
            $month = 1;
        $month = $month<10 ? '0'.$month : $month;
        $monthDate = date("Y-{$month}-d");
        $endOfTheMonthDate = Carbon::parse($monthDate)->endOfMonth();
        $monthName = $endOfTheMonthDate->format('F');
        $endOfTheMonth = $endOfTheMonthDate->format('d');
        $url = url('student/dashboard/get-schedule');
        $monthStartDate = Carbon::parse($monthDate)->startOfMonth()->format('Y-m-d 00:00:00');
        $monthEndDate = Carbon::parse($monthDate)->endOfMonth()->format('Y-m-d 23:59:59');
        $allSchedules = Schedule::selectRaw("schedules.*,tutors.name as tutor_name,subjects.name as subject_name ")
            ->join('schedule_student','schedule_student.schedule_id','=','schedules.id')
            ->join('tutors','tutors.id','=','schedules.tutor_id')
            ->join('subjects','subjects.id','=','schedules.subject_id')
            ->where('schedule_student.student_id',$user->id)
            ->whereBetween('schedules.schedule_start_time',[$monthStartDate,$monthEndDate])
            ->groupBy('schedules.id')
            ->get();
        $calender = View::make('admin.calender',compact('endOfTheMonth','url','month','allSchedules'))->render();
        return response()->json(compact('calender','monthName','month'));
    }

    public function getSchedule(Request $request){
        $date = $request->date;
        $user = Auth::guard('student')->user();
        $startDate = Carbon::parse($date)->format('Y-m-d 00:00:00');
        $endDate = Carbon::parse($date)->format('Y-m-d 23::59:59');
        $allSchedules = Schedule::selectRaw("schedules.*,tutors.name as tutor_name,subjects.name as subject_name ")
            ->join('schedule_student','schedule_student.schedule_id','=','schedules.id')
            ->join('tutors','tutors.id','=','schedules.tutor_id')
            ->join('subjects','subjects.id','=','schedules.subject_id')
            ->where('schedule_student.student_id',$user->id)
            ->whereBetween('schedules.schedule_start_time',[$startDate,$endDate])
            ->groupBy('schedules.id')
            ->get();
        $html =View::make('tutor.schedules',compact('allSchedules'))->render();
        return response()->json(['status'=>true,'html'=>$html]);

    }

}
