<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ProductFormRequest;

class ProductController extends Controller
{
    public function index() {

        //Using this approach makes n + 1 query
        // $products = Product::all();

        //Using this approach instead
        $products = Product::with('category')->get();

        return view('admin.products.index', compact('products'));
    }

    public function create() {
        $categories = Category::all();
        $brands = Brand::all();
        $colors = Color::where('status', '0')->get();
        return view('admin.products.create', compact('categories', 'brands', 'colors'));
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


        //Add colors for the product
        if($request->colors) {
            foreach($request->colors as $key => $color) {
                $product->productColors()->create([
                    'product_id' => $product->id,
                    'color_id' => $color,
                    'quantity' => $request->quantity[$key] ?? 0
                ]);
            }
        }

        return redirect('/admin/products')->with('message', 'Product added successfully');

    }

    public function edit(int $product_id) {
        $categories = Category::all();
        $brands = Brand::all();
        $product = Product::findOrFail($product_id);
        return view('admin.products.edit', compact('categories', 'brands', 'product'));
    }

    public function update(ProductFormRequest $request, int $product_id) {
        $validatedData = $request->validated();
        $product =
            Category::findOrFail($validatedData['category-id'])
            ->products()
            ->where('id', $product_id)
            ->first();

        if (!$product) {
            return redirect('admin/products')->with('message', 'No such product id found');
        }


        //Delete the old images of the product in uploads/products folder
        // $queryBuilder = $product->productImages()->where('product_id', $product_id);
        // $images = $queryBuilder->get();
        // foreach($images as $image) {
        //     $imagePath = $image->image;

        //     if(File::exists($imagePath)) {
        //         File::delete($imagePath);
        //     }
        // }

        // //Delete the old images record in database
        // $queryBuilder->delete();

        //Update the information of the product, excluding the images
        $product->update([
            'category_id' => $validatedData['category-id'],
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['slug']),
            'brand' => $validatedData['brand'],
            'small_description' => $validatedData['small-description'],
            'description' => $validatedData['description'],
            'original_price' => $validatedData['original-price'],
            'selling_price' => $validatedData['selling-price'],
            'quantity' => $validatedData['quantity'],
            'trending' => true == $request->trending?'1':'0',
            'status' => true == $request->status?'1':'0',
            'meta_title' => $validatedData['meta-title'],
            'meta_keyword' => $validatedData['meta-keyword'],
            'meta_description' => $validatedData['meta-description'],
        ]);


        //Save the new images after the product's updation
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

                //appending the array
                $images[] = [
                    'product_id' => $product->id,
                    'image' => $imagePath,
                ];

            }

            //using bulk insertion for better optimization
            $product->productImages()->createMany($images);
        }

        return redirect('/admin/products')->with('message', 'Product  updated successfully');
    }

    public function destroyImage(int $product_image_id) {
        $productImage = ProductImage::findOrFail($product_image_id);

        //Delete the file in directory
        if(File::exists($productImage->image)) {
            File::delete($productImage->image);
        }

        //Delete the image's record in database
        $productImage->delete();

        return redirect()->back()->with('message', 'Product image deleted');
    }

    public function destroy(int $product_id) {
        $product = Product::findOrFail($product_id);
        $queryBuilder = $product->productImages();
        $images = $queryBuilder->get();

        if ($images) {

            //Delete the images in the directory
            foreach($images as $image) {
                if (File::exists($image->image)) {
                    File::delete($image->image);
                }
            }

            //Delete all product images in database
            $queryBuilder->delete();
        }

        $product->delete();

        return redirect()->back()->with('message', 'Product deleted with all its images');

    }
}
