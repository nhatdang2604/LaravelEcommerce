<?php

namespace App\Http\Livewire\Frontend\Cart;

use App\Models\Cart;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CartCount extends Component
{

    public $cartCounter;

    protected $listeners = [
        'cartAddedUpdated' => 'increaseCartCount',
        'cartRemovedUpdated' => 'decreaseCartCount',
    ];

    public function mount() {
        if(Auth::check()) {
            $userId = auth()->user()->id;
            $this->cartCounter = Cart::where('user_id', $userId)->count();
            return;
        }

        $this->cartCounter = 0;
    }

    public function increaseCartCount() {
        if(Auth::check()) {
           ++$this->cartCounter;
        }
    }

    public function decreaseCartCount() {
        if(Auth::check()) {
           --$this->cartCounter;
        }
    }

    public function render()
    {
        return view('livewire.frontend.cart.cart-count', [
            'cartCounter' => $this->cartCounter,
        ]);
    }
}
