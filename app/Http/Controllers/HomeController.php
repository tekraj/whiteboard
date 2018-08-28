<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return '<h1>Home Page</h1>';
    }

    public function saveCanvasImage(Request $request){

    }
}
