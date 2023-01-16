<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Slider;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class FrontendController extends Controller
{
    public function index() {

        $sliders = Slider::where('status', '0')->get();
        $trendingProducts = Product::with('productImages', 'category')
                                ->where('trending', '1')
                                ->latest()
                                ->take(15)
                                ->get();

        $newArrivalProducts = Product::with(['productImages', 'category'])
                                ->latest()
                                ->take(16)
                                ->get();

        $featuredProducts = Product::where('feature', '1')
                                ->with(['productImages', 'category'])
                                ->latest()
                                ->take(14)
                                ->get();

        return view('frontend.index', compact(
            'sliders',
            'trendingProducts',
            'newArrivalProducts',
            'featuredProducts'));
    }

    public function categories() {

        $categories = Category::where('status', '0')->get();

        return view('frontend.collections.category.index', compact('categories'));
    }

    public function products($category_slug) {

        $category = Category::where('slug', $category_slug)->first();

        if($category) {
            // $products = $category->products()->with('productColors')->get();
            // return view('frontend.collections.products.index', compact('category', 'products'));

            return view('frontend.collections.products.index', compact('category'));

        }

        return redirect()->back();

    }

    public function productView(string $category_slug, string $product_slug) {
        $category = Category::where('slug', $category_slug)->first();

        if($category) {

            $product = $category
                ->products()
                ->where('slug', $product_slug)
                ->where('status', '0')
                ->first();

            if($product) {
                return view('frontend.collections.products.view', compact('category', 'product'));
            }
        }

        return redirect()->back();
    }

    public function newArrival(){
        $newArrivalProducts = Product::with(['productImages', 'category'])
                                ->latest()
                                ->take(16)
                                ->get();
        return view('frontend.pages.new-arrival', compact('newArrivalProducts'));
    }

    public function featuredProducts() {
        $featuredProducts = Product::where('feature', '1')
                                ->with(['productImages', 'category'])
                                ->latest()
                                ->take(16)
                                ->get();
        return view('frontend.pages.featured-products', compact('featuredProducts'));
    }
}


