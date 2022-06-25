<?php

namespace App\Http\Controllers\Mng;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mng\UpdateSubscriptionRequest;
use App\Models\Subscription;
use JetBrains\PhpStorm\ArrayShape;

class SubscriptionController extends Controller
{
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
}
