<?php
namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Tournament;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DownloadController extends Controller
{
    public function parentalPermission(Tournament $tournament)
    {
        $start = new Carbon($tournament->start_date);
        $end   = new Carbon($tournament->end_date);

        $dates = "{$start->format('d')} et {$end->format('d')} {$start->getTranslatedMonthName()} {$start->format('Y')}";

        $filename = "Autorisation Parentale " . $tournament->name . ".pdf";
        $filename = preg_replace('/[^\p{L}\p{N}\s\-\_\.]/u', '', $filename);

        return Pdf::loadView("pdfs.parental_permission", [
            "game"    => $tournament->game->name,
            "address" => Location::first()->address,
            "dates"   => $dates,
        ])
            ->set_option("enable_php", true)
            ->download($filename);
    }
}
