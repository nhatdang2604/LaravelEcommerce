<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\CategoryFormRequest;

class CategoryController extends Controller
{

    private const UPLOAD_PATH = "uploads/category";

    public function index() {
        return view("admin.category.index");
    }

    public function create() {
        return view("admin.category.create");
    }

    public function store(CategoryFormRequest $request) {
        $validateData = $request->validated();

        $category = new Category;
        $category->name = $validateData['name'];
        $category->slug = Str::slug($validateData['slug']);
        $category->description = $validateData['description'];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time().'.'.$extension;
            $filepath = CategoryController::UPLOAD_PATH.'/'.$filename;

            $file->move('uploads/category', $filename);
            $category->image = $filepath;
        }

        $category->meta_title = $validateData['meta-title'];
        $category->meta_keyword = $validateData['meta-keyword'];
        $category->meta_description = $validateData['meta-description'];

        $category->status = (true == $request->status)?'1':'0';
        $category->save();

        return redirect('admin/category')->with('message', 'Category added successfully');
    }

    public function edit(Category $category) {
        return view('admin.category.edit', compact('category'));
    }

    public function update(CategoryFormRequest $request, $category) {

        $validateData = $request->validated();

        $category = Category::findOrFail($category);

        $category->name = $validateData['name'];
        $category->slug = Str::slug($validateData['slug']);
        $category->description = $validateData['description'];

        if ($request->hasFile('image')) {

            $path = CategoryController::UPLOAD_PATH.'/'.$category->image;
            if(File::exists($path)) {
                File::delete($path);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time().'.'.$extension;

            $file->move('uploads/category', $filename);
            $category->image = $filename;
        }

        $category->meta_title = $validateData['meta-title'];
        $category->meta_keyword = $validateData['meta-keyword'];
        $category->meta_description = $validateData['meta-description'];

        $category->status = (true == $request->status)?'1':'0';
        $category->update();

        return redirect('admin/category')->with('message', 'Category updated successfully');
    }
}
