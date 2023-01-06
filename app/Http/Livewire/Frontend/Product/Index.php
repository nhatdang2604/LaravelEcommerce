<?php

namespace App\Http\Livewire\Frontend\Product;

use App\Models\Product;
use Livewire\Component;

class Index extends Component {

    protected $queryString = [
        'brandInputs' => ['except' => '', 'as' => 'brand']
    ];

    public $products, $category;
    public $brandInputs = [];

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
            ->with('productColors')
            ->where('status', '0')
            ->get();

        return view('livewire.frontend.product.index', [
            "products" => $this->products,
            "category" => $this->category,
        ]);
    }
}
