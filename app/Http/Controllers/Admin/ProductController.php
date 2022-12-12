<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFormRequest;

class ProductController extends Controller
{
    public function index() {
        return view('admin.products.index');
    }

    public function create() {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(ProductFormRequest $request) {
        $validatedData = $request->validated();

        $category = Category::findOrFail($validatedData['category-id']);

        //Create the product without the image
        $product = $category->products()->create([
            'category_id' => $validatedData['category-id'],
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['slug']),
            'brand' => $validatedData['brand'],
            'small_description' => $validatedData['small-description'],
            'description' => $validatedData['description'],
            'original_price' => $validatedData['original-price'],
            'selling_price' => $validatedData['selling-price'],
            'quantity' => $validatedData['quantity'],
            'trending' => $request->trending?'1':'0',
            'status' => $request->status?'1':'0',
            'meta_title' => $validatedData['meta-title'],
            'meta_keyword' => $validatedData['meta-keyword'],
            'meta_description' => $validatedData['meta-description'],
        ]);

        //Create the images after the product's creation
        if($request->hasFile('image')) {

            $uploadPath = 'uploads/products';
            $images = array();
            $index = 0;

            foreach($request->file('image') as $imageFile) {
                $index++;
                $extension = $imageFile->getClientOriginalExtension();
                $filename = time().'_'.$index.'.'.$extension;
                $imageFile->move($uploadPath, $filename);
                $imagePath = $uploadPath.'/'.$filename;

                // $product->productImages()->create([
                //     'product_id' => $product->id,
                //     'image' => $imagePath,
                // ]);

                //appending the array
                $images[] = [
                    'product_id' => $product->id,
                    'image' => $imagePath,
                ];

            }

            //using bulk insertion for better optimization
            $product->productImages()->createMany($images);
        }

        return redirect('/admin/products')->with('message', 'Product added successfully');

    }
}
