<?php

namespace App\Http\Controllers\Tutor;

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
        $user = Auth::guard('tutor')->user();
        $type = 'tutor';
        $subject = $user->subject;
        $sessions = SessionRepository::getTutorSessions($user->id,$subject->id);
        $domain = $request->getHttpHost();
        $isPublic = false;
        if(env('APP_ENV')=='production'){
            $isPublic = strpos($domain,'megamindtutor.com')>=0 ? 0 : 1;
        }
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
            'userType'=>'tutor',
            'connected_at'=>date('Y-m-d H:i:s')
        ]);
        $payload = $factory->make();
        $token = JWTAuth::encode($payload)->get();
        $user->token = $token;
        $user->userType = 'tutor';
        $user->Name = $user->name;
        $user->ObjectID = $user->uuid;
        $sessionNotes = SessionNote::where('user_type',$type)->where('user_id',$user->id)->where('subject_id',$subject->id)->orderBy('id','desc')->get();
        $notifications = Notification::where('status',1)->where(function($query) use ($subject){
            $query->where('subject_id',$subject->id)->orWhereNull('subject_id');
        })->orderBy('id','desc')->get();
        return view('whiteboard',compact('pageTitle','user','type','subject','sessions','sessionNotes','notifications','isPublic'));
    }

    public function showPracticeBoard(){
        $pageTitle = 'Practice Board';
        $user = Auth::guard('tutor')->user();
        $type = 'practice';
        $subject = $user->subject;
        $sessions = SessionRepository::getTutorSessions($user->id,$subject->id);

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
        $notifications = Notification::where('status',1)->where(function($query) use ($subject){
            $query->where('subject_id',$subject->id)->orWhereNull('subject_id');
        })->orderBy('id','desc')->get();
        return view('whiteboard',compact('pageTitle','user','type','subject','sessions','sessionNotes','notifications'));
    }
}
