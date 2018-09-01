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
            <?php
            $startDate = \Carbon\Carbon::parse($schedule->schedule_start_time,'UTC')->tz('Asia/Kolkata')->format('d M Y H:i');
            $endDate = \Carbon\Carbon::parse($schedule->schedule_end_time,'UTC')->tz('Asia/Kolkata')->format('d M Y H:i')
            ?>
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$startDate}}</td>
                <td>{{$endDate}}</td>
                <td>{{$schedule->subject_name}}</td>
                <td>{{$schedule->tutor_name}}</td>

            </tr>
        @endforeach

        </tbody>
    </table>
@else
    <h4 class="text-center">No Schedules for this day.</h4>
@endif