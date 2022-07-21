<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
    </style>
</head>

@foreach($bills as $key => $bill)

    <h1 align="center">Trường Đại học Tôn Đức Thắng</h1>

    <h3 align="center">Hóa đơn điện nước tháng {{$bill['pay_start_time']->month}}</h3>

    <b>Phòng:</b> {{$bill['room_name']}}
    <br>
    <b>Tổng tiền:</b> {{$bill['total_money']}}
    <br>
    <b>Ngày xuất hóa đơn:</b> {{$bill['pay_start_time']}}
    <br>
    <b>Hạn nộp:</b> {{$bill['pay_end_time']}}

    @if (count($bills) !== $key + 1)
        <div style="page-break-after: always;"></div>
    @endif
@endforeach
