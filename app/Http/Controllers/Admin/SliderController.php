<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
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

    public function edit(Slider $slider)
    {
        return view('admin.slider.edit', compact('slider'));
    }

    public function update(SliderFormRequest $request, Slider $slider) {
        $validatedData = $request->validated();

        //Save the new image to storage process
        if($request->hasFile('image')) {

            //Delete the old image
            $oldImage = $slider->image;
            if (File::exists($oldImage)) {
                File::delete($oldImage);
            }

            //Save the new image to storage
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;
            $file->move('uploads/slider', $filename);
            $validatedData['image'] = "uploads/slider/$filename";
        }

        Slider::findOrFail($slider->id)->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'image' => $validatedData['image'] ?? $slider->image,
            'status' => $request->status?'1':'0'
        ]);

        return redirect('admin/sliders')->with('message', 'Slider Updated Successfully');
    }

    public function destroy(Slider $slider) {

        if($slider->count() > 0) {

            //Delete the slider's image on storage
            $oldImage = $slider->image;
            if(File::exists($oldImage)) {
                File::delete($oldImage);
            }

            //Delete the slider's record on database
            Slider::findOrFail($slider->id)->delete();

            return redirect('admin/sliders')->with('message', 'Slider Deleted Successfully');

        }

        return redirect('admin/sliders')->with('message', 'Something Went Wrong');
    }
}
