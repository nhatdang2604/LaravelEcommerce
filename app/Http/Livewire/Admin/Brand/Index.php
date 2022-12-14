<?php

namespace App\Http\Livewire\Admin\Brand;

use App\Models\Brand;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class Index extends Component {

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $name, $slug, $status, $brand_id, $category_id;

    public function rules() {
        return [
            'name' => 'required|string',
            'slug' => 'required|string',
            'category_id' => 'required|integer',
            'status' => 'nullable',
        ];
    }

    public function resetInput() {
        $this->name = null;
        $this->slug = null;
        $this->status = null;
        $this->brand_id = null;
        $this->category_id = null;
    }

    public function editBrand(int $brand_id) {
        $this->brand_id = $brand_id;
        $brand = Brand::findOrFail($brand_id);
        [$this->name, $this->slug, $this->status, $this->category_id] =
            [$brand->name, $brand->slug, $brand->status, $brand->category_id];
    }

    public function closeModal() {
        $this->resetInput();
    }

    public function openModal() {
        $this->resetInput();
    }

    public function updateBrand() {
        $validateData = $this->validate();

        Brand::findOrFail($this->brand_id)->update([
            'name' => $this->name,
            'slug' => Str::slug($this->slug),
            'status' => true == $this->status?'1':'0',
            'category_id' => $this->category_id,
        ]);

        session()->flash('message', 'Brand updated successfully');
        $this->dispatchBrowserEvent('close-modal');
        $this->resetInput();
    }

    public function storeBrand() {
        $validateData = $this->validate();

        Brand::create([
            'name' => $this->name,
            'slug' => Str::slug($this->slug),
            'status' => true == $this->status?'1':'0',
            'category_id' => $this->category_id,
        ]);

        session()->flash('message', 'Brand added successfully');
        $this->dispatchBrowserEvent('close-modal');
        $this->resetInput();
    }

    public function deleteBrand(int $brand_id) {
        $this->brand_id = $brand_id;
    }

    public function destroyBrand() {
        Brand::findOrFail($this->brand_id)->delete();
        session()->flash('message', 'Brand deleted successfully');
        $this->dispatchBrowserEvent('close-modal');
        $this->resetInput();
    }

    public function render() {

        $categories = Category::where('status', '0')->get();
        $brands = Brand::orderBy('id', 'DESC')->with('category')->paginate(10);

        return
            view('livewire.admin.brand.index', ['brands' => $brands, 'categories' => $categories])
            ->extends('layouts.admin')
            ->section('content');
    }
}
