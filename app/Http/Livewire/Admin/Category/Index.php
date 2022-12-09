<?php

namespace App\Http\Livewire\Admin\Category;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;

class Index extends Component {

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $category_id;

    public function deleteCategory($category_id) {
        $this->category_id = $category_id;
    }

    public function destroyCategory() {
        $category = Category::find($this->category_id);
        $image = $category->image;
        if (null != $image) {
            $path = 'uploads/category/'.$image;
            if(File::exists($path)) {
                File::delete($path);
            }
        }
        $category->delete();
        session()->flash("message", "Category is deleted successfully");
        $this->dispatchBrowserEvent('close-modal');
    }

    public function render()
    {
        $categories = Category::orderBy('id','DESC')->paginate(2);
        return
            view('livewire.admin.category.index', ['categories' => $categories]);
    }
}
