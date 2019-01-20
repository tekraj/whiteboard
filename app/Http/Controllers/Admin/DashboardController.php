<?php

namespace App\Http\Controllers\Admin;

use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Tutor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Validator;
use View;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $subjects = [''=>'Select Subject'] + Subject::get()->pluck('name','id')->toArray();
        $pageTitle = 'Dashboard';

        $monthStartDate = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
        $monthEndDate = Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59');
        $allSchedules = Schedule::whereBetween('schedule_start_time',[$monthStartDate,$monthEndDate])->with(['tutor'=>function($query){
            $query->select('id','name');
        },'subject'=>function($query){
            $query->select('id','name');
        }])->get();

        return view('admin.dashboard.index',compact('pageTitle','subjects','allSchedules'));
    }

    /**
     * Method to get calender
     * @param Request $request
     */
    public function getCalender(Request $request){
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
        $url = url('admin/dashboard/get-schedule');
        $monthStartDate = Carbon::parse($monthDate)->startOfMonth()->format('Y-m-d 00:00:00');
        $monthEndDate = Carbon::parse($monthDate)->endOfMonth()->format('Y-m-d 23:59:59');
        $allSchedules = Schedule::whereBetween('schedule_start_time',[$monthStartDate,$monthEndDate])->with(['tutor'=>function($query){
            $query->select('id','name');
        },'subject'=>function($query){
            $query->select('id','name');
        }])->get();
        $calender = View::make('admin.calender',compact('endOfTheMonth','url','month','allSchedules'))->render();
        return response()->json(compact('calender','monthName','month'));
    }

    public function addNewSchedule(Request $request){
        $validationRules = [
            'schedule_start_date' => 'required',
            'schedule_end_date' => 'required',
            'subject_id'=>'required',
            'tutor_id'=>'required'
        ];
        $validationMessages = [];
        $validator = Validator::make($request->all(), $validationRules, $validationMessages);
        if($validator->fails()){
            return response()->json(['status'=>false]);
        }
        $user = Auth::user();
        $schedule = new Schedule;
        $schedule->schedule_start_time = Carbon::parse($request->schedule_start_date,'Asia/Kolkata')->tz('UTC');
        $schedule->schedule_end_time =  Carbon::parse($request->schedule_end_date,'Asia/Kolkata')->tz('UTC');

        $schedule->subject_id = $request->subject_id;
        $schedule->tutor_id = $request->tutor_id;
        $schedule->created_by = $user->id;
        if($schedule->save()){
            $subject = Subject::find($request->subject_id);
            $students = $subject->students()->pluck('id')->toArray();
            $schedule->students()->sync($students);
            $endOfTheMonth = Carbon::now()->endOfMonth()->format('d');
            $url = url('admin/dashboard/get-schedule');
            $month = date('m');
            $monthStartDate = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
            $monthEndDate = Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59');
            $allSchedules = Schedule::whereBetween('schedule_start_time',[$monthStartDate,$monthEndDate])->with(['tutor'=>function($query){
                $query->select('id','name');
            },'subject'=>function($query){
                $query->select('id','name');
            }])->get();
            $calender = View::make('admin.calender',compact('endOfTheMonth','url','month','allSchedules'))->render();
            return response()->json(['status'=>true,'schedule'=>$schedule,'calender'=>$calender]);
        }
        return response()->json(['status'=>false]);
    }

    public function getTutors(Request $request){
        $subjectId = $request->subject_id;
        $tutors = Tutor::select('id','name')->where('subject_id',$subjectId)->get();
        $students = Student::join('student_subject','student_subject.student_id','=','students.id')->where('student_subject.subject_id',$subjectId)->select('students.id','students.name')->groupBy('students.id')->get();
        return response()->json(['status'=>true,'tutors'=>$tutors,'students'=>$students]);
    }

    public function getSchedule(Request $request){
        $date = $request->date;
        $startDate = Carbon::parse($date)->format('Y-m-d 00:00:00');
        $endDate = Carbon::parse($date)->format('Y-m-d 23::59:59');
        $allSchedules = Schedule::whereBetween('schedule_start_time',[$startDate,$endDate])->with(['tutor'=>function($query){
            $query->select('id','name');
        },'subject'=>function($query){
            $query->select('id','name');
        },'students'=>function($query){
            $query->select('id','name');
        }])->get();
        $html =View::make('admin.schedules',compact('allSchedules'))->render();
        return response()->json(['status'=>true,'html'=>$html]);

    }

    public function editSchedule(Request $request){

        $user = Auth::user();
        $schedule = Schedule::find($request->schedule_id);
        $schedule->schedule_start_time = Carbon::parse($request->schedule_start_date,'Asia/Kolkata')->tz('UTC');
        $schedule->schedule_end_time =  Carbon::parse($request->schedule_end_date,'Asia/Kolkata')->tz('UTC');

        $schedule->subject_id = $request->subject_id;
        $schedule->tutor_id = $request->tutor_id;
        $schedule->created_by = $user->id;
        if($schedule->save()){
            $schedule->students()->sync($request->students);

            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }

}
