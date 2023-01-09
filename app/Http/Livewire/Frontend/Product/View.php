<?php

namespace App\Http\Livewire\Frontend\Product;

use App\Models\Product;
use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class View extends Component
{

    public $product, $category, $productColorSelectedQuantity;

    public function mount($category, $product) {
        $this->product = $product;
        $this->category = $category;
    }

    public function colorSelected($productColorId) {

        $productColor = $this
            ->product
            ->productColors
            ->where('id', $productColorId)
            ->first();

        $this->productColorSelectedQuantity = $productColor->quantity;

        if(0 >= $this->productColorSelectedQuantity) {
            $this->productColorSelectedQuantity = -1;
        }
    }

    public function addToWishlist($productId) {
        if(!Auth::check()) {
            session()->flash('message', 'Please login to continue');
            return false;
        }

        $userId = auth()->user()->id;
        if(Wishlist::where('user_id', $userId)->where('product_id', $productId)->exists()) {
            session()->flash('message', 'Already added to wishlist');
            return false;
        }


        $wishlist = Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        //Emit this event to update the Wishlist's count on the navbar
        $this->emit("wishlistCountUpdated");
        session()->flash('message', 'Wishlist added successfully');

    }

    public function render()
    {
        //Eager fetch the productColors, and the color from productColors,
        //  to avoid n + 1 problem
        $this->product =
            Product::with(['productColors', 'productColors.Color'])
            ->findOrFail($this->product->id);

        return view(
            'livewire.frontend.product.view', [
                'product' => $this->product,
                'category' => $this->category,
                'productColorSelectedQuantity' => $this->productColorSelectedQuantity,
            ]);
    }
}
