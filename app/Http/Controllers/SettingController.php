<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Requests\Settings\UpdateSettingRequest;

class SettingController extends Controller {
    public function index() {
        $settings = Setting::all()->map(function($setting) {
            return [
                "id" => $setting->id,
                "key" => $setting->key,
                "value" => decrypt($setting->value)
            ];
        });
        
        return Inertia::render('Settings/Index', [
            "settings" => $settings,
            "test" => Setting::get('test')
        ]);
    }

    public function update(UpdateSettingRequest $request) {        
        Setting::set($request->key, $request->value);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.setting.updated'));

        return back();
    }
}
