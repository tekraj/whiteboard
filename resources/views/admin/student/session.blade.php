@extends('admin.master-layout')
@section('content')
    <div class="container-fluid">
        <h4>Student Session Detail</h4>

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-6">
                        Name : <b>({{$student->name}})</b>
                    </div>
                    <div class="col-sm-6">
                        Email : <b>{{$student->email}}</b>
                    </div>

                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>SN</th>
                        <th>Subject</th>
                        <th>Tutor</th>
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
                            <td>{{$session->subject->name}}</td>
                            <td>{{$session->tutor->name}}</td>
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