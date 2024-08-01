<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUpload {
    
    public static function storeOrUpdate($file, $existingFilepath, $filepath = "storage") {
        if ($existingFilepath != "") {
            $path = str_replace("/storage", "public", $existingFilepath);
            // dd($path);
            Storage::delete($path);
        }

        $manager = new ImageManager(new Driver());

        $image = $manager->read($file);
        
        $path = $filepath . "/" . Str::uuid() . ".webp";
        
        $image->toWebp()->save($path);
        
        return "/" . $path;
    }
}