<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mng\DownloadBillRequest;
use App\Http\Requests\Mng\UpdateSubscriptionRequest;
use App\Models\Subscription;
use Illuminate\Support\Facades\App;
use JetBrains\PhpStorm\ArrayShape;

class SubscriptionController extends Controller
{
    public function detail($id): array
    {
        $subscription = Subscription::query()->find($id);
        if (empty($subscription)) {
            return [
                'status' => false,
                'message' => 'Không tìm thấy hóa đơn'
            ];
        }
        $type = $subscription->type;
        if ($type === Subscription::ELECTRICITY_WATER) {
            return [
                'status' => true,
                'data' => [
                    'type' => $subscription->type,
                    'room_name' => $subscription->room->name,
                    'total_money' => $subscription->price,
                    'pay_start_time' => $subscription->pay_start_time,
                    'pay_end_time' => $subscription->pay_end_time,
                ]
            ];
        }
        if ($type === Subscription::CONTRACT) {
            return [
                'status' => false,
                'data' => [
                    'message' => 'Từ từ, bên hợp đồng chưa làm, xem bên điện nước i :>'
                ]
            ];
        }

    }

    #[ArrayShape(['status' => "bool", 'message' => "string"])]
    public function update(UpdateSubscriptionRequest $request): array
    {
        $data = $request->validated();
        Subscription::query()->update($data);
        return [
            'status' => true,
            'message' => 'Cập nhật hóa đơn thành công'
        ];
    }

    public function downloadBill(DownloadBillRequest $request)
    {
        $data = [];
        $subscription_ids = $request->validated()['subscription_ids'];
        foreach ($subscription_ids as $subscription_id) {
            $data[] = $this->detail($subscription_id)['data'];
        }

        $html = view('bill.electricity_water', ['bills' => $data])->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        $pdf->render();
        $month = $data[0]['pay_start_time']->month;

        return $pdf->download("Hóa đơn điện nước tháng $month.pdf", array("Attachment"=>0));

    }

}
