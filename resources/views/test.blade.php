<h1 align="center">Đăng kí lịch gác cổng kí túc xá</h1>
<table border="1px solid black" width="100%">
    <tr>
        <td><b>Ca\Thứ</b></td>
        <th>Thứ 2</th>
        <th>Thứ 3</th>
        <th>Thứ 4</th>
        <th>Thứ 5</th>
        <th>Thứ 6</th>
        <th>Thứ 7</th>
        <th>Chủ nhật</th>
    </tr>

    @foreach($periods as $period)
        <tr>
            <td>{{$period['period_detail']}}</td>
            @foreach($period['schedules'] as $schedule)
                <td>
                    @if (isset($schedule['students']))
                        @foreach($schedule['students'] as $key => $student)
                            {{$key + 1}} . {{$student['name']}}  <br>
                        @endforeach
                        @if($check === 1 && $schedule['count_students'] === 1)
                            <input type="checkbox"> Đăng ký
                            <br>
                            <input type="checkbox"> Đăng ký
                        @endif
                        @if($check === 2 && $schedule['count_students'] === 2)
                            <input type="checkbox"> Đăng ký
                        @endif
                    @else
                        <input type="checkbox"> Đăng ký
                        <br>
                        <input type="checkbox"> Đăng ký
                        <br>
                        <input type="checkbox"> Đăng ký
                    @endif

                </td>
            @endforeach
        </tr>

    @endforeach
</table>
<button style="background-color:red;position:relative;left: 50%">
    <h1 style="color:yellow;">Đăng ký</h1>
</button>
