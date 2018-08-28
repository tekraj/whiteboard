<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class APIController extends Controller
{
    public function getData(Request $request){
       $data = DB::select($request->sql);
       return response()->json(compact('data'));
    }
}
