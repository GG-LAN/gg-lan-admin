<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LogController extends Controller {
    public function index() {
        $storage = Storage::disk("logs");
        $logs = [];
        
        foreach ($storage->files() as $file) {
            if (str_contains($file, ".log")) {
                array_push($logs, [
                    "id" => str_replace(".log", "", $file),
                    "label" => $file,
                    "icon" => "",
                    "labelPlus" => Number::fileSize($storage->size($file), precision: 1),
                    "content" => $storage->get($file),
                ]);
            }
        }
        
        $breadcrumbs = [
            [
                "label"   => "Logs",
                "route"   => route('logs.index'),
                "active"  => true
            ]
        ];
        
        return Inertia::render('Logs/Index', [
            "logs" => $logs,
            "breadcrumbs" => $breadcrumbs
        ]);
    }
}
