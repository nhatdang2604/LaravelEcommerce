<?php

namespace App\Http\Livewire\Frontend\Checkout;

use App\Models\Cart;
use App\Models\Order;
use Livewire\Component;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class CheckoutShow extends Component
{

    public $carts;
    public $totalProductAmount;

    //Field to form in the form
    public $fullname,
        $email,
        $phone,
        $pincode,
        $address,
        $payment_mode = NULL,
        $payment_id = NULL;

    public function rules() {
        return [
            'fullname' => 'required|string|max:121',
            'email' => 'required|email|max:121',
            'phone' => 'required|string|max:11|min:10',
            'pincode' => 'required|string|max:6|min:6',
            'address' => 'required|string|max:500',
        ];
    }

    public function placeOrder() {
        $validatedData = $this->validate();
        $order = Order::create([
            'user_id' => auth()->user()->id,
            'tracking_no' => "ORD.".Str::random(10),
            'fullname' => $this->fullname,
            'email' => $this->email,
            'phone' => $this->phone,
            'pincode'=> $this->pincode,
            'address'=> $this->address,
            'status_message' => "in progress",
            'payment_mode' => $this->payment_mode,
            'payment_id' => $this->payment_id,
        ]);

        $cartItems = array();
        foreach($this->carts as $cartItem) {
            $cartItems[] = [
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'product_color_id' => $cartItem->product_color_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->selling_price
            ];
        }

        //Using bulk insertion for better optimization
        OrderItem::insert($cartItems);

        return $order;
    }

    public function codOrder() {
        $this->payment_mode = "Cash on Delivery";
        $codOrder = $this->placeOrder();
        if($codOrder) {

            $userId = auth()->user()->id;

            //Clear out the cart
            Cart::where('user_id', $userId)->delete();

            //Set the Cart Counter (on the navigation bar) to 0
            $this->emit('cartClearUpdated');

            session()->flash('success-message', 'Order Place Successfully');
            session()->flash('message', 'Order Place Successfully');

            return redirect()->to('thank-you');
        }

        session()->flash('failed-message', 'Something Went Wrong');

        //TODO:
    }

    public function calculateTotalProductAmount(){
        $this->totalProductAmount = 0;
        $userId = auth()->user()->id;
        $this->carts = Cart::with('product')->where('user_id', $userId)->get();
        foreach($this->carts as $cartItem) {
            $this->totalProductAmount +=
                $cartItem->product->selling_price * $cartItem->quantity;
        }
    }

    public function render() {

        $this->fullname = auth()->user()->name;
        $this->email = auth()->user()->email;

        $this->calculateTotalProductAmount();
        return view('livewire.frontend.checkout.checkout-show', [
            'totalProductAmount' => $this->totalProductAmount,
        ]);
    }
}
