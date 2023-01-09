<?php

namespace App\Http\Livewire\Frontend\Product;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class View extends Component
{

    public $product, $category, $productColorSelectedQuantity, $quantityCount = 1, $productColorId;

    public function mount($category, $product) {
        $this->product = $product;
        $this->category = $category;
    }

    public function colorSelected($productColorId) {

        //Update this variable to store the current selected color
        $this->productColorId = $productColorId;

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

    public function decrementQuantity() {
        if(1 == $this->quantityCount) {
            return;
        }
        --$this->quantityCount;
    }

    public function incrementQuantity() {
        ++$this->quantityCount;
    }

    public function addToCart(int $productId) {


        //Check if the user is login
        if(!Auth::check()) {
            session()->flash('message', 'Please login to continue');
            return false;
        }

        //Check if the given product is exists
        //Get the latest product information, espeacially about the quantity
        $this->product = Product::with('productColors')->where('id', $productId)->where('status', '0')->first();
        if(!$this->product) {
            session()->flash('message', 'Product does not exists');
            return false;
        }

        //Check if the product's color is selected
        if(!$this->productColorId) {
            session()->flash('message', "Please select the product's color");
            return false;
        }

        //Check if there is enough product to buy
        //Case 1: The product have single color
        if(!$this->product->productColors) {
            if ($this->quantityCount > $this->product->quantity) {
                session()->flash('message', 'There is no enough product to buy');
                return false;
            } else {

                $userId = auth()->user()->id;

                //Check if in the cart, are there any the same item
                if(Cart::where('user_id', $userId)->where('product_id', $this->product->id)->exists()) {
                    session()->flash('message', 'Product already added');
                    return false;
                }

                //if enough quantity to buy
                //Save `quantityCount` product, then update it
                $this->product->quantity = $this->product->quantity - $this->quantityCount;
                $this->product->save();

                //Insert product to cart
                Cart::create([
                    'user_id' => $userId,
                    'product_id' => $this->product->id,
                    'quantity' => $this->quantityCount,
                ]);

                session()->flash('message', 'Product add to cart successfully');
                return true;
            }
        }

        //Case 2: The product have multiple colors
        $selectedProductColor = $this->product->productColors->first(function ($value, $key) {
            return $value->id == $this->productColorId;
        });

        if($this->quantityCount > $selectedProductColor->quantity) {
            session()->flash('message', 'There is no enough product to buy');
            return false;
        }

        //Save the user's id
        $userId = auth()->user()->id;

        //if enough quantity to buy
        //Check if in the cart, are there any the same item
        if(Cart::where('user_id', $userId)->where('product_id', $this->product->id)->where('product_color_id', $this->productColorId)->exists()) {
            session()->flash('message', 'Product already added');
            return false;
        }


        //Save `quantityCount` product, then update it
        $selectedProductColor->quantity = $selectedProductColor->quantity - $this->quantityCount;
        $selectedProductColor->save();

        //Insert product to cart
        Cart::create([
            'user_id' => auth()->user()->id,
            'product_id' => $this->product->id,
            'product_color_id' => $this->productColorId,
            'quantity' => $this->quantityCount,
        ]);

        session()->flash('message', 'Product add to cart successfully');
        return true;
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
