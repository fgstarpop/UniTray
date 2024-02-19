<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\OrderDataTable;
use App\Domain\Admin\Models\Order;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class OrderController
{
    use AuthorizesRequests;

    public function index(OrderDataTable $dataTable)
    {
        $this->authorize('view', Order::class);

        return $dataTable->render('admin.orders.index');
    }

    public function changeStatus(Order $order, Request $request)
    {
        $this->authorize('update', $order);

        $order->update(['status' => $request->status]);

        logActivity($order, 'update'); // log activity

        return response()->json([
            'status' => true,
            'message' => __('Cập nhật trạng thái thành công !'),
        ]);
    }
}
