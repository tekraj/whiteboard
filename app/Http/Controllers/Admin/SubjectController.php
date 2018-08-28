<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $pageSize = 20;
    public function index()
    {
        $pageTitle = 'Subjects';
        $subjects = Subject::paginate($this->pageSize);
        return view('admin.subject.index',compact('pageTitle','subjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $subject = new Subject();
        $pageTitle = 'Subjects';
        return view('admin.subject.form',compact('pageTitle','subject'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:subjects',
            'status' => 'required'
        ]);

        $subject = new Subject();
        $subject->name = $request->name;
        $subject->description = $request->description;
        $subject->status = $request->status;
        if($subject->save())
            return redirect('admin/subjects')->with('success','New Subject Created Successfully');
        return redirect('admin/subjects/create')->withErrors(['Unable to create new subject'])->with('subject',$subject);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $subject =Subject::find($id);
        $pageTitle = 'Subjects';
        return view('admin.subject.form',compact('pageTitle','subject'));
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
            'status' => 'required'
        ]);

        $subject = Subject::find($id);
        $subject->name = $request->name;
        $subject->description = $request->description;
        $subject->status = $request->status;
        if($subject->save())
            return redirect('admin/subjects')->with('success','Subject Updated Successfully');
        return redirect('admin/subjects/create')->withErrors(['Unable to update subject'])->with('subject',$subject);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {
        $subject = Subject::find($id);
        if($subject->delete())
            return redirect('admin/subjects')->with('success','Subject Deleted');
        return redirect('admin/subjects')->withErrors(['Unable to delete subject']);
    }

    public function search(Request $request){
        $pageTitle = 'Subjects';
        $search_data = $request->search_data;
        if(empty($search_data)){
            return redirect('admin/subjects');
        }
        $subjects = Subject::where(function($query) use ($search_data){
            $query->where('name','like',"%{$search_data}%")
                ->orWhere('description','like',"%{$search_data}%");
        })->paginate($this->pageSize);
        return view('admin.subject.index',compact('pageTitle','subjects','search_data'));
    }
}
