<?php

namespace App\Http\Livewire\Frontend\Cart;

use App\Models\Cart;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CartCount extends Component
{

    public $cartCounter;

    protected $listeners = [
        'cartAddedUpdated' => 'checkCartCount',
    ];

    public function checkCartCount() {
        $userId = auth()->user()->id;
        if(Auth::check()) {
            return $this->cartCounter = Cart::where('user_id', $userId)->count();
        }

        return $this->cartCounter = 0;
    }

    public function render()
    {
        $this->cartCount = $this->checkCartCount();
        return view('livewire.frontend.cart.cart-count', [
            'cartCounter' => $this->cartCounter,
        ]);
    }
}
