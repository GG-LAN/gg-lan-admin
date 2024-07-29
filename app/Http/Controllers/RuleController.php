<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;

class RuleController extends Controller {
    public function showApi() {
        return Rule::firstOrCreate();
    }

    public function update(Request $request) {
        $rules = Rule::firstOrCreate();

        $rules->update([
            "content" => $request->content
        ]);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.rules.updated'));

        return back();
    }
}
