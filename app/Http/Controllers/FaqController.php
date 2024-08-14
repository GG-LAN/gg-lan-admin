<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Tables\Faqs;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\Faqs\StoreFaqRequest;
use App\Http\Requests\Faqs\UpdateFaqRequest;

class FaqController extends Controller
{
    public function index(Request $request) {
        $breadcrumbs = [
            [
                "label"   => "FAQ",
                "route"   => route('faqs.index'),
                "active"  => true
            ]
        ];

        return Inertia::render('Faqs/Index', [
            "table"       => Faqs::table($request),
            "breadcrumbs" => $breadcrumbs,
        ]);
    }

    public function store(StoreFaqRequest $request) {
        Faq::create([
            "question" => $request->question,
            "response" => $request->response,
        ]);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.faqs.created'));

        return back();
    }

    public function showApi(Faq $faq) {
        return $faq;
    }

    public function update(UpdateFaqRequest $request, Faq $faq) {
        $faq->update([
            "question" => $request->question,
            "response" => $request->response
        ]);

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.faqs.updated'));

        return back();
    }

    public function destroy(Request $request, Faq $faq) {
        $faq->delete();

        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __('responses.faqs.deleted'));

        return back();
    }
}
