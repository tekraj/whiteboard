<table class="table table-bordered" id="calender">
    <thead class="week-days">
    <tr>
        <?php $weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat'];?>
        @foreach($weekDays as $day)
            <th>
                {{$day}}
            </th>
        @endforeach
    </tr>
    </thead>
    <?php
    $year = date('Y');
    $dayDiff = \Carbon\Carbon::parse("{$year}-{$month}-01")->dayOfWeek - 1;
    $dayDiff = $dayDiff == -1 ? 6 : $dayDiff;
    $prevMonth = \Carbon\Carbon::parse($year . '-' . $month . '-01')->subMonth();
    $endOfPrevMonth = $prevMonth->endOfMonth()->format('d');
    $startFromPrevMonth = $endOfPrevMonth - $dayDiff;
    $count = 1;
    $today = date('d');
    ?>
    <tbody class="">
    <tr>
        @for($i=$startFromPrevMonth;$i<=$endOfPrevMonth;$i++)
            <?php
            $date = ($year . '-' . $prevMonth->format('m') . '-' . ($i < 10 ? '0' . $i : $i));
            $weekDay = \Carbon\Carbon::parse($date)->dayOfWeek;
            ?>
            <td>
                <a href="#" data-date="{{$date}}" data-url="{{$url}}" disabled="true"
                   class="disabled js-calender-date ">{{$i<10 ? '0'.$i:$i}}

                </a>
            </td>
            @if($count%7==0)
    </tr>
    <tr>
        @endif
        <?php $count++;?>
        @endfor
        @for($i=1;$i<=$endOfTheMonth;$i++)
            <?php
            $date = $year . '-' . $month . '-' . ($i < 10 ? '0' . $i : $i);
            $weekDay = \Carbon\Carbon::parse($date)->dayOfWeek;
            ?>

            <td class="{{$today==$i ? 'today':''}}">
                <a href="#" data-date="{{$date}}" data-url="{{$url}}" class="js-calender-date">
                    <p>{{$i<10 ? '0'.$i:$i}}</p>
                    <p>
                        @foreach($allSchedules as $schedule)
                            @if(Carbon\Carbon::parse($schedule->schedule_start_date)->format('d')==$i)
                                <span class="text-danger" data-toggle="tooltip" data-placement="top"
                                      title="Schedule for {{$schedule->subject_name}} with {{$schedule->tutor_name}} betwwn {{\Carbon\Carbon::parse($schedule->schedule_start_time)->format('d M Y H:i:s').'-'.\Carbon\Carbon::parse($schedule->schedule_end_time)->format('d M Y H:i:s')}}"><i
                                            class="fa fa-circle"></i></span>
                            @endif
                        @endforeach
                    </p>
                </a>
            </td>
            @if($count%7==0)
    </tr>
    <tr>
        @endif
        <?php $count++;?>
        @endfor
    </tr>
    </tbody>
</table>