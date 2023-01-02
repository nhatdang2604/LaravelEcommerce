<?php

namespace App\Http\Controllers\Admin;

use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ColorFormRequest;

class ColorController extends Controller
{
    public function index() {
        return view('admin.colors.index');
    }

    public function create() {
        return view('admin.colors.create');
    }

    public function store(ColorFormRequest $request) {
        $validatedData = $request->validated();
        $color = new Color;
        $color->name = $validatedData['name'];
        $color->code = $validatedData['code'];
        $color->status = (true == $request->status?'1':'0');
        $color->save();
        return redirect('admin/colors')->with('message', 'Color Added Successfully');
    }
}
