<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Tournament;
use Illuminate\Http\Request;
use App\Models\PurchasedPlace;
use App\Http\Requests\Payments\StorePaymentRequest;

class PaymentController extends Controller
{
    public function index(Request $request) {
        $search = $request->search;
        $tournaments = Tournament::where("status", "open")->get();
        $payments = collect([]);

        foreach ($tournaments as $tournament) {
            $payments = $payments->merge($tournament->getPayments());
        }
        
        if ($search) {
            $payments = $payments->filter(function ($item) use ($search) {
                if (strpos(strtolower($item["pseudo"]), strtolower($search)) !== false) {
                    return true;
                }
                
                if (strpos(strtolower($item["tournament_name"]), strtolower($search)) !== false) {
                    return true;
                }
            });
        }
        
        $payments = $payments->sortBy([
            ['status', 'desc']
        ])->values();
        
        $rowsInfo = [
            "rows" => [
                "pseudo" => [
                    "type" => "text",
                    "label" => "Joueur",
                ],
                "tournament_name" => [
                    "type" => "text",
                    "label" => "Tournois",
                ],
                "status" => [
                    "type" => "badge",
                    "label" => "Statut",
                    "badges" => [
                        [
                            "value" => "paid",
                            "label" => "Payé",
                            "color" => "green"
                        ],
                        [
                            "value" => "not_paid",
                            "label" => "Pas payé",
                            "color" => "red"
                        ],
                    ]
                ],
                    
            ],
            "actions" => [
                "search" => true,
                "customActions" => [
                    [
                        "type" => "success",
                        "icon" => "money-bill",
                        "route" => "payments.store",
                        "condition" => "props.row.status == 'not_paid'"
                    ],  
                ],
                // "create" => true,
                // "update" => true,
                // "delete" => true,
                // "show" => [
                //     "route" => "tournaments.show"
                // ]
            ],
        ];

        $breadcrumbs = [
            [
                "label"   => "Paiements",
                "route"   => route('payments.index'),
                "active"  => true
            ]
        ];
        
        return Inertia::render('Payments/Index', [
            "tableData"     => $payments->paginate(5),
            "tableRowsInfo" => $rowsInfo,
            "filters" => [
                "search" => $request->search
            ],
            "breadcrumbs" => $breadcrumbs,
        ]);
    }
    
    public function store(StorePaymentRequest $request) {
        $player = User::find($request->id);
        $tournament = Tournament::find($request->tournament_id);
        
        if (!PurchasedPlace::checkExist($player, $tournament)) {
            $purchasedPlace = PurchasedPlace::create([
                'user_id' => $player->id,
                'tournament_price_id' => $tournament->currentPrice()->id
            ]);
                        
            $request->session()->flash('status', 'success');
            $request->session()->flash('message', __("responses.payment.registered"));

            return back();
        }
        
        $request->session()->flash('status', 'error');
        $request->session()->flash('message', __('responses.errors.something_went_wrong'));

        return back();
    }
}
