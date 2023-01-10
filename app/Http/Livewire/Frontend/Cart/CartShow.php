<?php

namespace App\Http\Livewire\Frontend\Cart;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;

class CartShow extends Component
{

    public $cart;
    public $quantityMap; //holder the current quantity in the cart with format (cartId => quantity)
    public $totalPrice;

    public function mount() {
        $this->quantityMap = array();
        $this->totalPrice = 0;

        $userId = auth()->user()->id;
        $this->cart = Cart::with('product', 'productColor', 'productColor.Color')->where('user_id', $userId)->get();

        //Calculate the total price
        foreach($this->cart as $cart) {
            $this->totalPrice += $cart->quantity * $cart->product->selling_price;
        }

        //load the cartId with the quantity in the map
        foreach($this->cart as $item) {
            $this->quantityMap[$item->id] = $item->quantity;
        }
    }

    public function decrementQuantity(int $cartId) {
        if (1 == $this->quantityMap[$cartId]) {
            return;
        }

        //Decrease the total price
        $quantityPrice = $this->cart->first(function($value, $key) use($cartId) {
            return $value->id == $cartId;
        })->product->selling_price;
        $this->totalPrice -= $quantityPrice;

        //Decrease the quantity
        --$this->quantityMap[$cartId];

    }

    public function incrementQuantity(int $cartId) {

        //Increase the total price
        $quantityPrice = $this->cart->first(function($value, $key) use($cartId) {
            return $value->id == $cartId;
        })->product->selling_price;
        $this->totalPrice += $quantityPrice;

        ++$this->quantityMap[$cartId];
    }

    public function removeCartItem(int $cartId) {
        $userId = auth()->user()->id;
        $cartRemoved = Cart::where('user_id', $userId)
            ->with('productColor', 'product')
            ->where('id', $cartId)
            ->first();

        if($cartRemoved) {

            //Update the current quantity of the item
            //Case 1: The item has multiple color
            if($cartRemoved->productColor) {

                //We need to increase the quantity of the product color
                $productColor = $cartRemoved->productColor;
                $productColor->quantity += $cartRemoved->quantity;
                $productColor->save();

            } else {
                //Case 2: The item has single color

                //We need to increase the quantity of the product
                $product = $cartRemoved->product;
                $product->quantity += $cartRemoved->quantity;
                $product->save();
            }


            //Update the total price
            $cart = $this->cart->first(function($value, $key) use($cartId) {
                return $value->id == $cartId;
            });
            $this->totalPrice -= $cart->product->selling_price * $cart->quantity;

            //Update the current cart which appeared in view
            //Remove the deleted cart from the view
            $this->cart = $this->cart->filter(function ($value, $key) use($cartId) {
                return $value->id != $cartId;
            });

            //Finally, delete the cart
            $cartRemoved->delete();

            $this->emit('cartRemovedUpdated');
            session()->flash('message', 'Cart is removed successfully');
        }
    }

    public function render()
    {
        return view('livewire.frontend.cart.cart-show', [
            'cart' => $this->cart,
        ]);
    }
}
