<?php

namespace App\Http\Livewire\Frontend\Cart;

use App\Models\Cart;
use Livewire\Component;

class CartShow extends Component
{
    public $cart;

    public function render()
    {
        $userId = auth()->user()->id;
        $this->cart = Cart::with('product', 'productColor', 'productColor.Color')->where('user_id', $userId)->get();
        return view('livewire.frontend.cart.cart-show', [
            'cart' => $this->cart,
        ]);
    }
}
