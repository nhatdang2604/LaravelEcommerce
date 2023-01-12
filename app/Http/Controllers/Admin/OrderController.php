<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
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

    public function updateOrderStatus(int $orderId, Request $request) {
        $order =
            Order::with(['orderItems', 'orderItems.product', 'orderItems.productColor', 'orderItems.productColor.Color'])
            ->where('id', $orderId)
            ->first();

        if(!$order) {
            return redirect('admin/orders/'.$order->id)->with([
                'order' => $order,
                'totalPrice' => $totalPrice,
                'message' => "No Order Found",
            ]);
        }

        $order->update([
            'status_message' => $request->order_status,
        ]);

        //Calculate total price
        $totalPrice = 0;
        foreach($order->orderItems as $item) {
            $totalPrice += $item->price * $item->quantity;
        }

        return redirect('admin/orders/'.$order->id)->with([
            'order' => $order,
            'totalPrice' => $totalPrice,
            'message' => "Order Status Updated",
        ]);
    }

    public function viewInvoice(int $orderId) {
        $order = Order::with(['orderItems', 'orderItems.product', 'orderItems.productColor', 'orderItems.productColor.Color'])
                ->findOrFail($orderId);

        //Calculate total price
        $totalPrice = 0;
        foreach($order->orderItems as $item) {
            $totalPrice += $item->price * $item->quantity;
        }

        return view('admin.invoice.generate-invoice', compact('order', 'totalPrice'));
    }

    public function generateInvoice(int $orderId) {
        $order = Order::with(['orderItems', 'orderItems.product', 'orderItems.productColor', 'orderItems.productColor.Color'])
                ->findOrFail($orderId);

         //Calculate total price
         $totalPrice = 0;
         foreach($order->orderItems as $item) {
             $totalPrice += $item->price * $item->quantity;
         }

        $data = [
            'order' => $order,
            'totalPrice' => $totalPrice,
        ];

        $pdf = Pdf::loadView('admin.invoice.generate-invoice', $data);
        $todayDate = Carbon::now()->format('d-m-Y');

        return $pdf->download('invoice-'.$order->id.'-'.$todayDate.'.pdf');
    }
}
