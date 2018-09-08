@extends('admin.master-layout')
@section('content')
    <div class="container-fluid">
        <div class="au-card recent-report">
            <div class="au-card-inner">
                <h3 class="title-2">View Details About {{$student->name}} <a href="{{url('admin/students')}}" class="btn btn-default pull-right"><b>Back</b></a></h3>
                <table class="table table-hover table-striped">
                    <tbody>
                    <tr>
                        <th>Name</th>
                        <td>{{$student->name}}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{$student->email}}</td>
                    </tr>
                    <tr>
                        <th>Contact No</th>
                        <td>{{$student->contact_no}}</td>
                    </tr>
                    <tr>
                        <th>School</th>
                        <td>{{$student->school_name}}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{$student->address}}</td>
                    </tr>
                    <tr>
                        <th>Date Of Birth</th>
                        <td>{{\Carbon\Carbon::parse($student->dob)->format('d M Y')}}</td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td>{{ucfirst($student->gender)}}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{$student->status==1 ? 'Active': 'In-active'}}</td>
                    </tr>
                    <tr>
                        <th>Photo</th>
                        <td>
                            <img src="{{url("storage/students-profiles-{$student->profile_pic}")}}" alt="" class="avatar">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection