<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Wishlist;

class WishlistShow extends Component
{
    public function render()
    {
        //Get current user's id
        $user = auth()->user();
        $wishlist = [];

        if(!is_null($user)) {

            $userId = $user->id;

            //Fetch Wishtlist with product, product's images and product's category
            $wishlist = Wishlist::with(['product', 'product.productImages', 'product.category'])
                        ->where('user_id', $userId)
                        ->get();
        }

        return view('livewire.frontend.wishlist-show', [
            'wishlist' => $wishlist,
        ]);
    }
}
