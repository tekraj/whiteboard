@foreach($sessions as $session)

    <tr>
        <td>{{$session->name}}</td>
        <td>{{$session->subject}}</td>
        @if($type=='student')
            <td>{{$session->tutor_name}}</td>
        @endif
        <td>{{$session->start_time}}</td>
        <td>{{$session->end_time}}</td>
        <td>{{\Carbon\Carbon::parse($session->start_time)->diffInMinutes($session->end_time)}}</td>
        <td>{{$session->session_id}}</td>
    </tr>
@endforeach