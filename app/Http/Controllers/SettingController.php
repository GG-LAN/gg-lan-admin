<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Setting;
use App\Helpers\ImageUpload;
use Illuminate\Http\Request;
use App\Http\Requests\Settings\UpdateSettingRequest;
use App\Http\Requests\Settings\UpdateSettingImageRequest;

class SettingController extends Controller {
    public function index() {
        $settings = Setting::all()->map(function($setting) {
            return [
                "id" => $setting->id,
                "key" => $setting->key,
                "value" => decrypt($setting->value)
            ];
        });

        $breadcrumbs = [
            [
                "label"   => "Paramètres",
                "route"   => route('settings.index'),
                "active"  => true
            ]
        ];
        
        return Inertia::render('Settings/Index', [
            "settings" => $settings,
            "breadcrumbs" => $breadcrumbs
        ]);
    }

    public function update(UpdateSettingRequest $request) {        
        Setting::set($request->key, $request->value);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.setting.updated'));

        return back();
    }

    public function updateImage(Request $request) {
        $path = ImageUpload::storeOrUpdate(
            $request->file('image'),
            Setting::get('image_cover'),
            "storage",
            $request->value
        );
        
        Setting::set($request->key, $path);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.setting.updated'));

        return back();
    }
}
