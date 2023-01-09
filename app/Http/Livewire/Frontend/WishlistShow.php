<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Wishlist;

class WishlistShow extends Component {

    public function removeWishlistItem($wishlistId) {
        $user = auth()->user();

        //Predifined for the failed message
        $messageKey = 'message';
        $messageContent = 'There is no item for you to remove';
        $messageType = 'error';
        $status = 404;

        //Check if the user is login
        if(!is_null($user)) {
            $userId = $user->id;
            $wishlist = Wishlist::where('user_id', $userId)
                        ->where('id', $wishlistId)
                        ->first()
                        ->delete();

            $messageContent = "Wishlist Item Removed Successfully";
            $messageType = "success";
            $status = 200;
        }

        $this->dispatchBrowserEvent($messageKey, [
            "text" => $messageContent,
            "type" => $messageType,
            "status" => $status,
        ]);

    }

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
