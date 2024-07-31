<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\Faqs\StoreFaqRequest;
use App\Http\Requests\Faqs\UpdateFaqRequest;

class FaqController extends Controller
{
    public function index(Request $request) {
        $faqs = Faq::getFaqs(5, $request->search);

        $rowsInfo = [
            "rows" => [
                "question" =>  [
                    "type" => "text",
                    "title" => "Question"
                ],
                "response" => [
                    "type" => "text",
                    "title" => "Réponse"
                ]
            ],
            "actions" => [
                "search" => true,
                "create" => true,
                "update" => true,
                "delete" => true,
            ]
        ];

        $breadcrumbs = [
            [
                "label"   => "FAQ",
                "route"   => route('faqs.index'),
                "active"  => true
            ]
        ];

        return Inertia::render('Faqs/Index', [
            "tableData"     => $faqs,
            "tableRowsInfo" => $rowsInfo,
            "filters" => [
                "search" => $request->search
            ],
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
