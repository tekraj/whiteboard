@extends('admin.master-layout')
@section('content')
    <div class="container-fluid">
        <div class="au-card recent-report">
            <div class="au-card-inner">
                <h3 class="title-2">View Details About {{$tutor->name}} <a href="{{url('admin/tutors')}}" class="btn btn-default pull-right">Back</a></h3>
                <table class="table table-hover table-striped">
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <td>{{$tutor->name}}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{$tutor->email}}</td>
                        </tr>
                        <tr>
                            <th>
                                Subject
                            </th>
                            <td>{{$tutor->subject->name}}</td>
                        </tr>
                        <tr>
                            <th>Contact No</th>
                            <td>{{$tutor->contact_no}}</td>
                        </tr>
                        <tr>
                            <th>School</th>
                            <td>{{$tutor->school_name}}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{$tutor->address}}</td>
                        </tr>
                        <tr>
                            <th>Date Of Birth</th>
                            <td>{{\Carbon\Carbon::parse($tutor->dob)->format('d M Y')}}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>{{ucfirst($tutor->gender)}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{$tutor->status==1 ? 'Active': 'In-active'}}</td>
                        </tr>
                        <tr>
                            <th>Photo</th>
                            <td>
                                <img src="{{url("storage/tutors-profiles-{$tutor->profile_pic}")}}" alt="" class="avatar">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection