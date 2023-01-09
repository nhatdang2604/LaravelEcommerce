<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistCount extends Component
{

    public $wishlistCount = 0;

    protected $listeners = [
        "wishlistCountUpdated" => "checkWishlistCount",
    ];

    public function checkWishlistCount() {

        //Always return 0 when the user is not authenticated
        if(!Auth::check()) {
            return 0;
        }

        return $this->wishlistCount =
                Wishlist::where('user_id', auth()->user()->id)
                ->count();
    }

    public function render()
    {
        $this->wishlistCount = $this->checkWishlistCount();
        return view('livewire.frontend.wishlist-count', [
            'wishlistCount' => $this->wishlistCount,
        ]);
    }
}
