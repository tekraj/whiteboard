<?php

namespace App\Http\Controllers\Admin;

use App\Models\Schedule;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Auth;
use JWTFactory;
use Carbon\Carbon;
class MappingScreenController extends Controller
{
    public function index(){
        $user = Auth::user();
        $user->ObjectID = $user->uuid;
        $user->Name = $user->name;
        $user->subject = 'all';
        $user->userType = 'admin';
        $factory = JWTFactory::addClaims([
            'sub'   => $user->id,
            'iss'   => url('/'),
            'iat'   => Carbon::now()->timestamp,
            'exp'   => JWTFactory::getTTL(),
            'nbf'   => Carbon::now()->timestamp,
            'jti'   => uniqid(),
            'ObjectID'=>$user->uuid,
            'Name'=>$user->name,
            'subject'=>'all',
            'subject_name'=>'all',
            'userType'=>'admin'
        ]);
        $payload = $factory->make();
        $token = JWTAuth::encode($payload)->get();
        $user->token = $token;
        $subjects = Subject::where('status',1)->get();
        return view('admin.mapping-screen.index',compact('user','subjects'));
    }
}
