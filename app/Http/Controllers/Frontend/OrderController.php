<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function index() {
        $userId =  Auth::user()->id;
        $orders =
            Order::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('frontend.orders.index', compact('orders'));
    }

    public function show($orderId) {
        $userId =  Auth::user()->id;
        $order =
            Order::with(['orderItems', 'orderItems.product', 'orderItems.productColor', 'orderItems.productColor.Color'])
            ->where('user_id', $userId)
            ->where('id', $orderId)
            ->first();

        //Calculate total price
        $totalPrice = 0;
        foreach($order->orderItems as $item) {
            $totalPrice += $item->price * $item->quantity;
        }

        if(!$order) {
            return redirect()->back()->with('message', 'No Order Found');
        }

        return view('frontend.orders.view', compact('order', 'totalPrice'));
    }
}
