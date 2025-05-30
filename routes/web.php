<?php

use App\Http\Controllers\Api\DownloadController;
use Illuminate\Support\Facades\Route;

Route::get("/download/parental-permission/{tournament}", [DownloadController::class, "parentalPermission"])->name("download.parental-permission");
