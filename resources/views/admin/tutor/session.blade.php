@extends('admin.master-layout')
@section('content')
    <div class="container-fluid">
        <h4>Tutor Session Detail <a href="{{url('admin/tutors')}}" class="btn btn-default pull-right"><b>Back</b></a></h4>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-4">
                    Name : <b>({{$tutor->name}})</b>
                </div>
                <div class="col-sm-4">
                    Email : <b>{{$tutor->email}}</b>
                </div>
                <div class="col-sm-4">
                    Subject <b>{{$tutor->subject->name}}</b>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>SN</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Total Time</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sessions as $key=>$session)
                    <tr>
                        <?php
                        $endDate = \Carbon\Carbon::parse($session->end_time);
                        $startDate = \Carbon\Carbon::parse($session->start_time);
                        ?>
                        <td>{{$key+1}}</td>
                        <td>{{$startDate->format('d M Y H:i')}}</td>
                        <td>{{$endDate->format('d M Y H:i')}}</td>
                        <td>{{$startDate->diffInMinutes($endDate)}} Minutes</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
@endsection