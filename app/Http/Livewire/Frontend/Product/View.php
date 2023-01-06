<?php

namespace App\Http\Livewire\Frontend\Product;

use App\Models\Product;
use Livewire\Component;

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
