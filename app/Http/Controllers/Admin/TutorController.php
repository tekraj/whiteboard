<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use App\Models\Tutor;
use App\Models\TutorSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;
class TutorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $pageSize = 20;
    public function index()
    {
        $pageTitle = 'Tutors';
        $tutors = Tutor::with('subject')->paginate($this->pageSize);
        return view('admin.tutor.index',compact('pageTitle','tutors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $tutor = new Tutor();
        $pageTitle = 'Tutors';
        $subjects = Subject::where('status',1)->pluck('name','id')->toArray();
        return view('admin.tutor.form',compact('pageTitle','tutor','subjects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:tutors',
            'password'=>'required|min:6',
            'status' => 'required',
            'contact_no' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'subject_id' => 'required'
        ]);

        $tutor = new Tutor();
        $tutor->name = $request->name;
        $tutor->email = $request->email;
        $tutor->password = bcrypt($request->password);
        $tutor->status = $request->status;
        $tutor->school_name = $request->school_name;
        $tutor->contact_no = $request->contact_no;
        $tutor->subject_id = $request->subject_id;
        $tutor->address = $request->address;
        $tutor->gender = $request->gender;
        if($request->dob && !empty($request->dob)){
            $tutor->dob = Carbon::parse($request->dob);
        }

        if( $profile_pic = $request->file('profile_pic')){
            $ext = $profile_pic->getClientOriginalExtension();
            $profile_pic_name = 'tutors'.time().'profile_pic.'.$ext;
            $dest = storage_path('uploads/tutors/profiles/');
            if($profile_pic->move($dest, $profile_pic_name)){
                $tutor->profile_pic = $profile_pic_name;

            }
        }
        if($tutor->save()){
            return redirect('admin/tutors')->with('success','New Tutor Created Successfully');
        }
        return redirect('admin/tutor/create')->withErrors(['Unable to create new tutor'])->with('tutor',$tutor);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $pageTitle = 'Tutors';
        $tutor = Tutor::find($id);
        return view('admin.tutor.view',compact('pageTitle','tutor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $tutor =Tutor::find($id);
        $subjects = Subject::where('status',1)->pluck('name','id')->toArray();
        $pageTitle = 'Tutors';
        return view('admin.tutor.form',compact('pageTitle','tutor','subjects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password'=>'nullable|min:6',
            'status' => 'required',
            'contact_no' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'subject_id' => 'required'
        ]);

        $tutor = Tutor::find($id);
        $tutor->name = $request->name;
        $tutor->email = $request->email;
        $tutor->password = bcrypt($request->password);
        $tutor->status = $request->status;
        $tutor->school_name = $request->school_name;
        $tutor->contact_no = $request->contact_no;
        $tutor->subject_id = $request->subject_id;
        $tutor->address = $request->address;
        $tutor->gender = $request->gender;
        if($request->dob && !empty($request->dob)){
            $tutor->dob = Carbon::parse($request->dob);
        }
        if( $profile_pic = $request->file('profile_pic')){
            $ext = $profile_pic->getClientOriginalExtension();
            $profile_pic_name = 'tutors_'.time().'_profile_pic.'.$ext;
            $dest = storage_path('uploads/tutors/profiles/');
            if($profile_pic->move($dest, $profile_pic_name)){
                File::delete($dest.$tutor->profile_pic);
                $tutor->profile_pic = $profile_pic_name;
            }
        }
        if($tutor->save())
            return redirect('admin/tutors')->with('success','Tutor UpdatedSuccessfully');
        return redirect('admin/tutors/create')->withErrors(['Unable to update tutor'])->with('tutor',$tutor);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {
        $tutor = Tutor::find($id);
        if($tutor->delete())
            return redirect('admin/tutors')->with('success','Tutor Deleted');
        return redirect('admin/tutors')->withErrors(['Unable to delete tutor']);
    }

    public function search(Request $request){

        $pageTitle = 'Tutors';
        $search_data = $request->search_data;
        if(empty($search_data)){
            return redirect('admin/tutors');
        }
        $tutors = Tutor::where(function($query) use ($search_data){
            $query->where('name','like',"%{$search_data}%")
                ->orWhere('email','like',"%{$search_data}%");
        })->paginate($this->pageSize);
        return view('admin.tutor.index',compact('pageTitle','tutors','search_data'));
    }

    public function payments(){
        $pageTitle = 'Tutors';
        return view('admin.tutor.payment',compact('pageTitle'));
    }

    public function sessions($id){
        $pageTitle = 'Tutors';
        $tutor = Tutor::find($id);
        $sessions = TutorSession::where('tutor_id',$id)->orderBy('id','desc')->get();
        return view('admin.tutor.session',compact('pageTitle','tutor','sessions'));
    }
}
