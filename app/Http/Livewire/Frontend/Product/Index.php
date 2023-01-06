<?php

namespace App\Http\Livewire\Frontend\Product;

use App\Models\Product;
use Livewire\Component;

class Index extends Component {

    protected $queryString = [
        'brandInputs' => ['except' => '', 'as' => 'brand'],
        'priceInput' => ['except' => '', 'as' => 'price']
    ];

    public $products, $category;
    public $brandInputs = [];
    public $priceInput;

    public function mount($category) {
        $this->category = $category;
    }
    public function render() {

        //Get all the products with the 0 status, in the same given category
        //  Also eager fetching the product colors table
        //  to avoid n + 1 problem
        $this->products =
            Product::where('category_id', $this->category->id)
            ->when($this->brandInputs, function($query) {
                $query->whereIn('brand', $this->brandInputs);
            })
            ->when('high-to-low' == $this->priceInput, function($query) {
                $query->orderBy('selling_price', 'DESC');
            })
            ->when('low-to-high' == $this->priceInput, function($query) {
                $query->orderBy('selling_price', 'ASC');
            })
            ->with('productColors')
            ->where('status', '0')
            ->get();

        return view('livewire.frontend.product.index', [
            "products" => $this->products,
            "category" => $this->category,
        ]);
    }
}
