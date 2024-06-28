<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Tournament;
use Illuminate\Http\Request;

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
                    "title" => "Joueur",
                ],
                "tournament_name" => [
                    "type" => "text",
                    "title" => "Tournois",
                ],
                "status" => [
                    "type" => "status",
                    "title" => "Statut",
                    "status" => [
                        [
                            "id" => "paid",
                            "text" => "Payé",
                            "color" => "green"
                        ],
                        [
                            "id" => "not_paid",
                            "text" => "Pas payé",
                            "color" => "red"
                        ],
                    ]
                ],
                    
            ],
            "actions" => [
                "search" => true,
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
    
    public function store(Request $request) {
        //
    }
}
