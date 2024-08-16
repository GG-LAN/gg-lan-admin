<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Tournament;
use Illuminate\Http\Request;
use App\Models\PurchasedPlace;
use App\Tables\PurchasedPlaces;
use App\Http\Requests\Payments\StorePaymentRequest;

class PaymentController extends Controller
{
    public function index(Request $request) {
        $breadcrumbs = [
            [
                "label"   => "Paiements",
                "route"   => route('payments.index'),
                "active"  => true
            ]
        ];
        
        return Inertia::render('Payments/Index', [
            "table" => PurchasedPlaces::table($request),
            "breadcrumbs" => $breadcrumbs,
        ]);
    }
    
    public function store(Request $request) {
        $purchasedPlace = PurchasedPlace::find($request->id);

        $purchasedPlace->update([
            "paid" => true,
        ]);
        
        $request->session()->flash('status', 'success');
        $request->session()->flash('message', __("responses.payment.registered"));
        
        return back();
    }
}
