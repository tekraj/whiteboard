@if(count($allSchedules)>0)
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>SN</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Subject</th>
            <th>Tutor</th>
        </tr>
        </thead>
        <tbody>

        @foreach($allSchedules as $key=>$schedule)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{\Carbon\Carbon::parse($schedule->schedule_start_time)->format('d M Y H:i')}}</td>
                <td>{{\Carbon\Carbon::parse($schedule->schedule_end_time)->format('d M Y H:i')}}</td>
                <td>{{$schedule->subject_name}}</td>
                <td>{{$schedule->tutor_name}}</td>

            </tr>
        @endforeach

        </tbody>
    </table>
@else
    <h4 class="text-center">No Schedules for this day.</h4>
@endif