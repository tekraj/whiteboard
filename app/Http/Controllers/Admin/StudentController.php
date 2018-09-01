<?php

namespace App\Http\Controllers\Admin;

use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentSession;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $pageSize = 20;
    public function index()
    {
        $pageTitle = 'Students';
        $students = Student::with('studentClass')->paginate($this->pageSize);
        return view('admin.student.index',compact('pageTitle','students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $student = new Student();
        $pageTitle = 'Students';
        $subjects = Subject::select('id','name')->where('status',1)->get();
        $classes = StudentClass::get()->pluck('class_name','id')->toArray();
        return view('admin.student.form',compact('pageTitle','student','classes','subjects'));
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
            'class_id' => 'required'
        ]);

        $student = new Student();
        $student->name = $request->name;
        $student->email = $request->email;
        $student->password = bcrypt($request->password);
        $student->password_plain = $request->password;
        $student->status = $request->status;
        $student->school_name = $request->school_name;
        $student->contact_no = $request->contact_no;
        $student->class_id = $request->class_id;
        $student->address = $request->address;
        $student->gender = $request->gender;
        if($request->dob && !empty($request->dob)){
            $student->dob = Carbon::parse($request->dob);
        }

        if( $profile_pic = $request->file('profile_pic')){
            $ext = $profile_pic->getClientOriginalExtension();
            $profile_pic_name = 'student_'.time().'profile_pic.'.$ext;
            $dest = storage_path('uploads/students/profiles/');
            if($profile_pic->move($dest, $profile_pic_name)){
                $student->profile_pic = $profile_pic_name;
            }
        }
        if($student->save()){
            $student->subjects()->sync($request->subjects);
            return redirect('admin/students')->with('success','New Student Created Successfully');
        }
        return redirect('admin/student/create')->withErrors(['Unable to create new student'])->with('student',$student);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $pageTitle = 'Students';
        $student = Student::find($id);
        return view('admin.student.view',compact('pageTitle','student'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $subjects = Subject::select('id','name')->where('status',1)->get();
        $classes = StudentClass::get()->pluck('class_name','id')->toArray();
        $student =Student::find($id);
        $pageTitle = 'Students';
        return view('admin.student.form',compact('pageTitle','student','classes','subjects'));
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
            'password'=>'min:6',
            'status' => 'required',
            'contact_no' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'class_id' => 'required'
        ]);

        $student = Student::find($id);
        $student->name = $request->name;
        $student->email = $request->email;
        $student->password = bcrypt($request->password);
        $student->password_plain = $request->password;
        $student->status = $request->status;
        $student->school_name = $request->school_name;
        $student->contact_no = $request->contact_no;
        $student->class_id = $request->class_id;
        $student->address = $request->address;
        $student->gender = $request->gender;
        if($request->dob && !empty($request->dob)){
            $student->dob = Carbon::parse($request->dob);
        }

        if( $profile_pic = $request->file('profile_pic')){
            $ext = $profile_pic->getClientOriginalExtension();
            $profile_pic_name = 'student_'.time().'profile_pic.'.$ext;
            $dest = storage_path('uploads/students/profiles/');
            if($profile_pic->move($dest, $profile_pic_name)){
                File::delete($dest.$student->profile_pic);
                $student->profile_pic = $profile_pic_name;
            }
        }
        if($student->save()){
            $student->subjects()->sync($request->subjects);
            return redirect('admin/students')->with('success','Student UpdatedSuccessfully');
        }
        return redirect('admin/students/create')->withErrors(['Unable to update student'])->with('student',$student);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {
        $student = Student::find($id);
        if($student->delete())
            return redirect('admin/students')->with('success','Student Deleted');
        return redirect('admin/students')->withErrors(['Unable to delete student']);
    }

    public function search(Request $request){
        $pageTitle = 'Students';
        $search_data = $request->search_data;
        if(empty($search_data)){
            return redirect('admin/students');
        }
        $students = Student::where(function($query) use ($search_data){
            $query->where('name','like',"%{$search_data}%")
                ->orWhere('email','like',"%{$search_data}%");
        })->paginate($this->pageSize);
        return view('admin.student.index',compact('pageTitle','students','search_data'));
    }

    public function payments(){
        $pageTitle = 'Students';
       return view('admin.student.payments',compact('pageTitle'));
    }

    public function sessions($id){
        $pageTitle = 'Students';
        $student = Student::find($id);
        $sessions = StudentSession::where('student_id',$id)->with(['tutor'=>function($query){$query->select('id','name');},'subject'=>function($query){$query->select('id','name');}])->orderBy('id','desc')->get();
        return view('admin.student.session',compact('pageTitle','student','sessions'));
    }
}
