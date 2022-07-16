<table border="1px solid black" width="100%">
    <tr>
        <td>Ca\Thứ</td>
        <td>Thứ 2</td>
        <td>Thứ 3</td>
        <td>Thứ 4</td>
        <td>Thứ 5</td>
        <td>Thứ 6</td>
        <td>Thứ 7</td>
        <td>Chủ nhật</td>
    </tr>

    @foreach($periods as $period)
        <tr>
            <td>{{$period['period_detail']}}</td>
            @foreach($period['schedules'] as $schedule)
                <td>
                    @foreach($schedule['students'] as $student)
                    {{$student['name']}}  <br>
                    @endforeach
                </td>
            @endforeach
        </tr>

    @endforeach
</table>
