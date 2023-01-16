<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index() {
        $setting = Setting::first();
        return view('admin.setting.index', compact('setting'));
    }

    private function buildSettingArrayFromRequest(Request $request) {
        return [
            'website_name' => $request->website_name,
            'website_url' => $request->website_url,
            'page_title' => $request->title,
            'meta_keyword' => $request->meta_keyword,
            'meta_description' => $request->meta_description,
            'address' => $request->address,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'email1' => $request->email1,
            'email2' => $request->email2,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
            'youtube' => $request->youtube,
        ];
    }


    public function store(Request $request) {

        $setting = Setting::first();

        //Check to save or update the setting
        if ($setting) {
            $setting->update($this->buildSettingArrayFromRequest($request));
        } else {
            Setting::create($this->buildSettingArrayFromRequest($request));
        }

        return redirect()->back()->with('message', 'Setting Saved Successfully');
    }
}
