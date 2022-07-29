<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
    </style>
</head>

@foreach($bills as $key => $bill)
    <div style="width: 724px; padding: 40px; text-align: center" id="review">
        <div style="font-weight: bold">TRƯỜNG ĐẠI HỌC TÔN ĐỨC THẮNG</div>
        <div style="font-weight: bold">KÝ TÚC XÁ</div>
        <div style="width: 100%; height: 20px"></div>

        <div style="font-weight: bold">THÔNG BÁO</div>
        <div style="font-weight: bold">Thu tiền phí sử dụng điện; nước ở ký túc xá - Tháng {{$bill['pay_start_time']->month}}</div>
        <div style="width: 100%; height: 20px"></div>

        <div>
            <span style="font-weight: bold">Phòng: {{$bill['room_name']}}</span>
        </div>
        <div style="width: 100%; height: 20px"></div>

        <div style="width: 400px;padding: 8px;margin: 0px auto;border: solid #000000 1px;">
            <div style="display: flex;">
                <div style="width: 70%; font-weight: bold;">Tổng cộng</div>
                <div style="width: 30%">{{$bill['total_money']}}</div>
            </div>

            <div style="height: .8px; margin: 12px 0px; background-color: #000000"></div>

            <div style="display: flex; flex-direction: row-reverse">
                <div style="display: grid; grid-template-Areas: "a b" "c d"; gap: 8px 16px; text-align: left">
                <div>Hạn chót thanh toán</div>
                <div>{{$bill['pay_end_time']}}</div>
                <div>Ngày xuất hoá đơn</div>
                <div>{{$bill['pay_start_time']}}</div>
            </div>
        </div>
    </div>
    <div style="width: 100%; height: 20px"></div>
    </div>

    @if (count($bills) !== $key + 1)
        <div style="page-break-after: always;"></div>
    @endif
@endforeach


