<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CloudFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $pageTitle = 'Cloud Files';
        return view('admin.cloud-file.index',compact('pageTitle'));
    }


}
