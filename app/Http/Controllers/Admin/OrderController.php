<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function show($orderId) {
        $order =
            Order::with(['orderItems', 'orderItems.product', 'orderItems.productColor', 'orderItems.productColor.Color'])
            ->where('id', $orderId)
            ->first();


        if(!$order) {
            return redirect()->back()->with('message', 'No Order Found');
        }

        //Calculate total price
        $totalPrice = 0;
        foreach($order->orderItems as $item) {
            $totalPrice += $item->price * $item->quantity;
        }

        return view('admin.orders.view', compact('order', 'totalPrice'));
    }

    public function index(Request $request) {
        $orders = Order::when($request->status != "", function($query) use($request) {
            return $query->where('status_message', $request->status);
        })
        -> when($request->date != null, function($query) use($request) {
            return $query->whereDate('created_at', $request->date);
        })
        ->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }
}
