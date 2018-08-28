<?php

namespace App\Http\Controllers\Tutor;

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
        $user = Auth::guard('tutor')->user();
        $monthStartDate = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
        $monthEndDate = Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59');
        $allSchedules = Schedule::where('tutor_id',$user->id)->whereBetween('schedule_start_time',[$monthStartDate,$monthEndDate])->with(['subject'=>function($query){
            $query->select('id','name');
        }])->get();
        return view('tutor.dashboard', compact('pageTitle', 'user','allSchedules'));
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
        $user = Auth::guard('tutor')->user();
        $month = $month<10 ? '0'.$month : $month;
        $monthDate = date("Y-{$month}-d");
        $endOfTheMonthDate = Carbon::parse($monthDate)->endOfMonth();
        $monthName = $endOfTheMonthDate->format('F');
        $endOfTheMonth = $endOfTheMonthDate->format('d');
        $url = url('student/dashboard/get-schedule');
        $monthStartDate = Carbon::parse($monthDate)->startOfMonth()->format('Y-m-d 00:00:00');
        $monthEndDate = Carbon::parse($monthDate)->endOfMonth()->format('Y-m-d 23:59:59');
        $allSchedules = Schedule::where('tutor_id',$user->id)->whereBetween('schedule_start_time',[$monthStartDate,$monthEndDate])->with(['subject'=>function($query){
            $query->select('id','name');
        }])->get();
        $calender = View::make('tutor.calender',compact('endOfTheMonth','url','month','allSchedules'))->render();
        return response()->json(compact('calender','monthName','month'));
    }

    public function getSchedule(Request $request){
        $user = Auth::guard('tutor')->user();
        $date = $request->date;
        $startDate = Carbon::parse($date)->format('Y-m-d 00:00:00');
        $endDate = Carbon::parse($date)->format('Y-m-d 23::59:59');
        $allSchedules = Schedule::where('tutor_id',$user->id)->whereBetween('schedule_start_time',[$startDate,$endDate])->with(['subject'=>function($query){
            $query->select('id','name');
        },'students'=>function($query){
            $query->select('id','name');
        }])->get();
        $html =View::make('tutor.schedules',compact('allSchedules'))->render();
        return response()->json(['status'=>true,'html'=>$html]);

    }
}
