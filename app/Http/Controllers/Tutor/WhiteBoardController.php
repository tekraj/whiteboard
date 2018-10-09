<?php

namespace App\Http\Controllers\Tutor;

use App\Models\Subject;
use App\Repositories\SessionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Auth;
use JWTFactory;
use Carbon\Carbon;

class WhiteBoardController extends Controller
{
    public function index(){
        $pageTitle = 'Whiteboard';
        $user = Auth::guard('tutor')->user();
        $type = 'tutor';
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
            'userType'=>'tutor'
        ]);
        $payload = $factory->make();
        $token = JWTAuth::encode($payload)->get();
        $user->token = $token;
        $user->userType = 'tutor';
        $user->Name = $user->name;
        $user->ObjectID = $user->uuid;
        return view('whiteboard',compact('pageTitle','user','type','subject','sessions'));
    }
}
