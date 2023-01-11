<?php

namespace App\Http\Livewire\Frontend\Cart;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use App\Models\ProductColor;
use Illuminate\Support\Facades\DB;
use App\Rules\NotEnoughProductToAddMoreRule;

class CartShow extends Component
{

    public $cart;
    public $quantityMap; //holder the current quantity in the cart with format (cartId => quantity)
    public $totalPrice;
    public $errorTraceback;

    public function rules() {
        return [
            'errorTraceback.*.*' => [new NotEnoughProductToAddMoreRule],
        ];
    }

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

    //fecth the ('column name' => value) of the cart into array
    private function buildCart($cart) {

        //Return the array of collumn in cart
        return [
            'id' => $cart->id,
            'user_id' => $cart->user_id,
            'product_id' => $cart->product_id,
            'product_color_id' => $cart->product_color_id,
            'quantity' => $cart->quantity,
        ];
    }

    //fecth the ('column name' => value) of the cart's product into array
    private function buildProduct($cart) {

        //Get the product from cart
        $product = $cart->product;

        //Return the array of collumn in product
        return [
            'id' => $product->id,
            'category_id' => $product->category_id,
            'name' => $product->name,
            'slug' => $product->slug,
            'brand' => $product->brand,
            'small_description' => $product->small_description,
            'description' => $product->description,
            'original_price' => $product->original_price,
            'selling_price' => $product->selling_price,
            'trending' => $product->trending,
            'status' => $product->status,
            'meta_title' => $product->meta_title,
            'meta_keyword' => $product->meta_keyword,
            'meta_description' => $product->meta_description,
            'quantity' => $product->quantity,
        ];
    }

    //fecth the ('column name' => value) of the cart's product's color into array
    private function buildProductColor($cart) {

        //Get the productColor from cart
        $productColor = $cart->productColor;

        //Return the array of collumn in productColor
        return [
            'id' => $cart->productColor->id,
            'product_id' => $productColor->product_id,
            'color_id' => $productColor->color_id,
            'quantity' => $productColor->quantity,
        ];

    }

    //Remove `$quantity` product from from cart, then return those to storage
    public function removeSomeExistItemFromCart($cart, $quantity) {

        $queryBatchs = array();

        //Decrease the quantity in cart
        $cart->quantity -= $quantity;
        $queryBatchs[0][] = $this->buildCart($cart);

        //Increase the quantity in storage
        //Case 1: The product has multiple color
        if ($cart->productColor) {
            $cart->productColor->quantity += $quantity;
            $queryBatchs[1][] = $this->buildProductColor($cart);

        } else {
            //Case 2: The product has single color
            $cart->product->quantity += $quantity;
            $queryBatchs[2][] = $this->buildProduct($cart);
        }

        return $queryBatchs;
    }

    //Add `$quantity` $cart->product to cart
    //This function could check if there are enough product to add more to cart
    public function addMoreExistItemToCart($cart, $quantity) {

        $queryBatchs = array();

        //Decrease the quantity in cart
        $cart->quantity += $quantity;
        $queryBatchs[0][] = $this->buildCart($cart);

        //Increase the quantity in storage
        //Case 1: The product has multiple color
        if ($cart->productColor) {
            $cart->productColor->quantity -= $quantity;
            $queryBatchs[1][] = $this->buildProductColor($cart);

        } else {
            //Case 2: The product has single color
            $cart->product->quantity -= $quantity;
            $queryBatchs[2][] = $this->buildProduct($cart);
        }

        return $queryBatchs;
    }

    public function validateBeforeCheckout() {

        $this->errorTraceback = array();

        //Calculate the difference of quantity, after checkout the cart vs before going to the cart
        $negativeDifferenceQuantities = array();    //quantity of item which is decrease
        $positiveDifferenceQuantities = array();    //quantity of item which is increase

        //Building the difference quantities arrays
        foreach($this->cart as $cartItem) {
            $buffer = $this->quantityMap[$cartItem->id] - $cartItem->quantity;
            if($buffer < 0) {$negativeDifferenceQuantities[$cartItem->id] = $buffer;}
            else if($buffer > 0) {$positiveDifferenceQuantities[$cartItem->id] = $buffer;}
        }

        //Buffer to batch update queyr, for better performance
        $decreaseQueryBatchs = array();
        $increaseQueryBatchs = array();

        // //Resolve the product which is decrease the quantity
        // foreach($negativeDifferenceQuantities as $cartId => $differenceQuantity) {

        //     //Get the cart from cartId
        //     $cart = $this->cart->first(function($value, $key) use($cartId) {
        //         return $value->id == $cartId;
        //     });

        //     $result = $this->removeSomeExistItemFromCart($cart, -$differenceQuantity);
        //     $decreaseQueryBatchs[0][] = $result[0][0];
        //     if(array_key_exists(1, $result)) {$decreaseQueryBatchs[1][] = $result[1][0];}
        //     if(array_key_exists(2, $result)) {$decreaseQueryBatchs[2][] = $result[2][0];}
        // }

        //Get the current quantity in stock of item, which is decreasing quantity in cart
        $decreaseQuantityCartIds = array_keys($negativeDifferenceQuantities);
        $decreaseQuantityCarts =
            Cart::with('product', 'productColor', 'productColor.Color')
            ->whereIn('id', $decreaseQuantityCartIds)
            ->get();

        foreach($decreaseQuantityCarts as $decreaseQuantityCart) {

            $differenceQuantity = $negativeDifferenceQuantities[$decreaseQuantityCart->id];

            //Case 1: The product has multiple colors
            if($decreaseQuantityCart->productColor) {

                //There are enough quantity to add more
                if($differenceQuantity <= $decreaseQuantityCart->productColor->quantity) {

                    //Get the cart from cartId
                    $result = $this->removeSomeExistItemFromCart($decreaseQuantityCart, -$differenceQuantity);
                    $decreaseQueryBatchs[0][] = $result[0][0];
                    $decreaseQueryBatchs[1][] = $result[1][0];
                } else {

                    //Else, there is not enough item to add more
                    $decreaseQueryBatchs[3][] = $decreaseQuantityCart;   //mark as not enought to increase
                }

            } else {

                //Case 2: The product has single color
                if($differenceQuantity <= $decreaseQuantityCart->product->quantity) {

                    //Get the cart from cartId
                    $result = $this->removeSomeExistItemFromCart($decreaseQuantityCart, -$differenceQuantity);
                    $decreaseQueryBatchs[0][] = $result[0][0];
                    $decreaseQueryBatchs[2][] = $result[2][0];
                } else {

                    //Else, there is not enough item to add more
                    $decreaseQueryBatchs[3][] = $decreaseQuantityCart;   //mark as not enought to increase
                }
            }

        }

        //Get the current quantity in stock of item, which is increasing quantity in cart
        $increaseQuantityCartIds = array_keys($positiveDifferenceQuantities);
        $increaseQuantityCarts =
            Cart::with('product', 'productColor', 'productColor.Color')
            ->whereIn('id', $increaseQuantityCartIds)
            ->get();


        foreach($increaseQuantityCarts as $increaseQuantityCart) {

            $differenceQuantity = $positiveDifferenceQuantities[$increaseQuantityCart->id];

            //Case 1: The product has multiple colors
            if($increaseQuantityCart->productColor) {

                //There are enough quantity to add more
                if($differenceQuantity <= $increaseQuantityCart->productColor->quantity) {

                    //Get the cart from cartId
                    $result = $this->addMoreExistItemToCart($increaseQuantityCart, $differenceQuantity);
                    $increaseQueryBatchs[0][] = $result[0][0];
                    $increaseQueryBatchs[1][] = $result[1][0];
                } else {

                    //Else, there is not enough item to add more
                    $increaseQueryBatchs[3][] = $increaseQuantityCart;   //mark as not enought to increase
                }

            } else {

                //Case 2: The product has single color
                if($differenceQuantity <= $increaseQuantityCart->product->quantity) {

                    //Get the cart from cartId
                    $result = $this->addMoreExistItemToCart($increaseQuantityCart, $differenceQuantity);
                    $increaseQueryBatchs[0][] = $result[0][0];
                    $increaseQueryBatchs[2][] = $result[2][0];
                } else {

                    //Else, there is not enough item to add more
                    $increaseQueryBatchs[3][] = $increaseQuantityCart;   //mark as not enought to increase
                }
            }

        }

        //Make the livewire controller hold the mapper ['cartId' => 'not enough quantity to add']
        if(array_key_exists(3, $decreaseQueryBatchs)){
            $this->errorTraceback[] = $decreaseQueryBatchs[3];
        }
        if(array_key_exists(3, $increaseQueryBatchs)){
            $this->errorTraceback[] = $increaseQueryBatchs[3];
        }

        $this->validate();

        //Batch updating for the increase and decrease quantity
        DB::transaction(function() use($increaseQueryBatchs, $decreaseQueryBatchs) {

            //Increasing process
            if(array_key_exists(0, $increaseQueryBatchs)) {Cart::upsert($increaseQueryBatchs[0], ['id'], ['quantity']);}
            if(array_key_exists(1, $increaseQueryBatchs)) {ProductColor::lockForUpdate()->upsert($increaseQueryBatchs[1], ['id'], ['quantity']);}
            if(array_key_exists(2, $increaseQueryBatchs)) {Product::lockForUpdate()->upsert($increaseQueryBatchs[2], ['id'], ['quantity']);}

            //Decreasing process
            if(array_key_exists(0, $decreaseQueryBatchs)) {Cart::upsert($decreaseQueryBatchs[0], ['id'], ['quantity']);}
            if(array_key_exists(1, $decreaseQueryBatchs)) {ProductColor::upsert($decreaseQueryBatchs[1], ['id'], ['quantity']);}
            if(array_key_exists(2, $decreaseQueryBatchs)) {Product::upsert($decreaseQueryBatchs[2], ['id'], ['quantity']);}
        });

        return redirect()->to('/checkout');
    }

    public function render()
    {
        return view('livewire.frontend.cart.cart-show', [
            'cart' => $this->cart,
        ]);
    }
}
