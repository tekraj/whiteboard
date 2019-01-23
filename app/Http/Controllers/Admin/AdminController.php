<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Role;
use App\Models\TechSupportMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $pageSize = 20;
    public function index()
    {
        $pageTitle = 'Admins';
        $admins = Admin::paginate($this->pageSize);
        return view('admin.admin.index',compact('pageTitle','admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $admin = new Admin();
        $pageTitle = 'Admins';
        $roles = Role::get();
        return view('admin.admin.form',compact('pageTitle','admin','roles'));
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
            'email' => 'required|unique:admins',
            'role' => 'required',
            'password'=>'required|min:6',
            'status' => 'required'
        ]);

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = bcrypt($request->password);

        $admin->status = $request->status;
        if($admin->save()){
            $admin->roles()->attach($request->role);
            return redirect('admin/admins')->with('success','New Admin Created Successfully');
        }
        return redirect('admin/admins/create')->withErrors(['Unable to create new admin'])->with('admin',$admin);
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
        $admin =Admin::find($id);
        $pageTitle = 'Admins';
        $roles = Role::get();
        return view('admin.admin.form',compact('pageTitle','admin','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id)
    { $this->validate($request, [
        'name' => 'required',
        'email' => 'required',
        'role' => 'required',
        'status' => 'required',
        'password'=>'nullable|min:6'
        ]);

        $admin = Admin::find($id);
        $admin->name = $request->name;
        $admin->email = $request->email;
        if(!empty($request->password) ){
            $admin->password = bcrypt($request->password);
        }

        $admin->status = $request->status;
        if($admin->save()){
            $admin->roles()->sync($request->role);
            return redirect('admin/admins')->with('success','Admin UpdatedSuccessfully');
        }
        return redirect('admin/admins/create')->withErrors(['Unable to update admin'])->with('admin',$admin);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {
        $admin = Admin::find($id);
        if($admin->delete())
            return redirect('admin/admins')->with('success','Admin Deleted');
        return redirect('admin/admins')->withErrors(['Unable to delete admin']);
    }

    public function search(Request $request){
        $pageTitle = 'Admins';
        $search_data = $request->search_data;
        if(empty($search_data)){
            return redirect('admin/admins');
        }
        $admins = Admin::where(function($query) use ($search_data){
            $query->where('name','like',"%{$search_data}%")
                ->orWhere('email','like',"%{$search_data}%");
        })->paginate($this->pageSize);
        return view('admin.admin.index',compact('pageTitle','admins','search_data'));
    }


}
