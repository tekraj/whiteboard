@if(count($allSchedules)>0)
<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>SN</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Subject</th>
        <th>Tutor</th>
        <th>Students</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>

    @foreach($allSchedules as $key=>$schedule)
        <?php
            $startDate = \Carbon\Carbon::parse($schedule->schedule_start_time,'UTC')->tz('Asia/Kolkata')->format('d M Y H:i');
            $endDate = \Carbon\Carbon::parse($schedule->schedule_end_time,'UTC')->tz('Asia/Kolkata')->format('d M Y H:i')
        ?>
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$startDate}}</td>
            <td>{{$endDate}}</td>
            <td>{{$schedule->subject->name}}</td>
            <td>{{$schedule->tutor->name}}</td>
            <td>
                @foreach($schedule->students as $student)
                    <label class="label label-primary">{{$student->name}}</label>
                @endforeach
            </td>
            <td>
                @if(\Carbon\Carbon::parse($schedule->schedule_start_time,'UTC')->tz('Asia/Kolkata') > \Carbon\Carbon::now())
                    <a href="#" class="js-edit-schedule" data-tutorurl="{{url('admin/dashboard/get-tutors')}}" data-id="{{$schedule->id}}" data-subjectid="{{$schedule->subject->id}}" data-tutorid="{{$schedule->tutor->id}}" data-students="{!! $schedule->students->pluck('id') !!}" data-startdate="{{ \Carbon\Carbon::parse($schedule->schedule_start_time,'UTC')->tz('Asia/Kolkata')->format('Y-m-d H:i')}}" data-enddate="{{ \Carbon\Carbon::parse($schedule->schedule_end_time,'UTC')->tz('Asia/Kolkata')->format('Y-m-d H:i')}}"><i class="fa fa-edit"></i></a>
                @endif
            </td>
        </tr>
    @endforeach

    </tbody>
</table>
@else
    <h4 class="text-center">No Schedules for this day.</h4>
@endif