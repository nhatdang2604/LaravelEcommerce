<?php

namespace App\Http\Livewire\Frontend\Cart;

use App\Models\Cart;
use Livewire\Component;

class CartShow extends Component
{

    public $cart;
    public $quantityMap = array(); //holder the current quantity in the cart with format (cartId => quantity)

    public function mount() {
        $userId = auth()->user()->id;
        $this->cart = Cart::with('product', 'productColor', 'productColor.Color')->where('user_id', $userId)->get();

        //load the cartId with the quantity in the map
        foreach($this->cart as $item) {
            $this->quantityMap[$item->id] = $item->quantity;
        }
    }

    public function decrementQuantity(int $cartId) {
        if (1 == $this->quantityMap[$cartId]) {
            return;
        }

        --$this->quantityMap[$cartId];

    }

    public function incrementQuantity(int $cartId) {
        ++$this->quantityMap[$cartId];
    }

    public function render()
    {
        return view('livewire.frontend.cart.cart-show', [
            'cart' => $this->cart,
        ]);
    }
}
