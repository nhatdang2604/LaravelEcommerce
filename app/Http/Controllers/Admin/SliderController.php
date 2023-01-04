<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SliderFormRequest;

class SliderController extends Controller
{

    public function index() {
        $sliders = Slider::all();
        return view('admin.slider.index', compact('sliders'));
    }

    public function create() {
        return view('admin.slider.create');
    }

    public function store(SliderFormRequest $request) {
        $validatedData = $request->validated();

        //Save the image to storage
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;
            $file->move('uploads/slider', $filename);
            $validatedData['image'] = "uploads/slider/$filename";
        }

        Slider::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'image' => $validatedData['image'],
            'status' => $request->status?'1':'0'
        ]);

        return redirect('admin/sliders')->with('message', 'Slider Added Successfully');
    }
}
