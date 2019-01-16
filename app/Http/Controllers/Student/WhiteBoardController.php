<?php

namespace App\Http\Controllers\Student;

use App\Models\SessionNote;
use App\Models\Subject;
use App\Notification;
use App\Repositories\SessionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Auth;
use JWTFactory;
use Carbon\Carbon;
class WhiteBoardController extends Controller
{
    public function index(Request $request){
        $pageTitle = 'Whiteboard';
        $user = Auth::guard('student')->user();
        $subjetId = session('student_subject');
        $sessions = SessionRepository::getStudentSessions($user->id,$subjetId);
        $type = 'student';
        $domain = $request->getHttpHost();
        $isPublic = false;
        if(env('APP_ENV')=='production'){
            $isPublic = strpos($domain,'megamindtutor.com')>=0 ? 'false' : 'true';
        }
        $subject = Subject::find($subjetId);
        if(!$subject)
            return redirect('student/dashboard');
        $factory = JWTFactory::addClaims([
            'sub'   => $user->id,
            'iss'   => url('/'),
            'iat'   => Carbon::now()->timestamp,
            'exp'   => JWTFactory::getTTL(),
            'nbf'   => Carbon::now()->timestamp,
            'jti'   => uniqid(),
            'ObjectID'=>$user->uuid,
            'Name'=>$user->name,
            'subject'=>$subject->id,
            'subject_name'=>$subject->name,
            'userType'=>'student'
        ]);
        $payload = $factory->make();
        $token = JWTAuth::encode($payload)->get();
        $user->token = $token;
        $user->userType = 'student';
        $user->Name = $user->name;
        $user->ObjectID = $user->uuid;
        $sessionNotes = SessionNote::where('user_type',$type)->where('user_id',$user->id)->where('subject_id',$subject->id)->orderBy('id','desc')->get();
        $notifications = Notification::where('status',1)->where(function($query) use ($subjetId){
            $query->where('subject_id',$subjetId)->orWhereNull('subject_id');
        })->orderBy('id','desc')->get();
        return view('whiteboard',compact('pageTitle','user','type','subject','sessions','sessionNotes','notifications','isPublic'));
    }

    public function showPracticeBoard(){
        $pageTitle = 'Practice Board';
        $user = Auth::guard('student')->user();
        $subjetId = session('student_subject');
        $sessions = SessionRepository::getStudentSessions($user->id,$subjetId);
        $type = 'practice';

        $subject = Subject::find($subjetId);
        if(!$subject)
            return redirect('student/dashboard');
        $factory = JWTFactory::addClaims([
            'sub'   => $user->id,
            'iss'   => url('/'),
            'iat'   => Carbon::now()->timestamp,
            'exp'   => JWTFactory::getTTL(),
            'nbf'   => Carbon::now()->timestamp,
            'jti'   => uniqid(),
            'ObjectID'=>$user->uuid,
            'Name'=>$user->name,
            'subject'=>$subject->id,
            'subject_name'=>$subject->name,
            'userType'=>'practice'
        ]);
        $payload = $factory->make();
        $token = JWTAuth::encode($payload)->get();
        $user->token = $token;
        $user->userType = 'practice';
        $user->Name = $user->name;
        $user->ObjectID = $user->uuid;
        $sessionNotes = SessionNote::where('user_type',$type)->where('user_id',$user->id)->where('subject_id',$subject->id)->orderBy('id','desc')->get();
        $notifications = Notification::where('status',1)->where(function($query) use ($subjetId){
            $query->where('subject_id',$subjetId)->orWhereNull('subject_id');
        })->orderBy('id','desc')->get();
        return view('whiteboard',compact('pageTitle','user','type','subject','sessions','sessionNotes','notifications'));
    }

}
