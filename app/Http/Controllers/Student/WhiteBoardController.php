<?php

namespace App\Http\Controllers\Student;

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
        $user = Auth::guard('student')->user();
        $sessions = SessionRepository::getStudentSessions($user->id);
        $type = 'student';
        $subjetId = session('student_subject');
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
        return view('whiteboard',compact('pageTitle','user','type','subject','sessions'));
    }
}
