<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'value'
    ];

    public static function get($key) {
        $setting = (new static)->where('key', $key)->first();

        if (!$setting) {
            return null;
        }
        
        return decrypt($setting->value);
    }

    public static function set($key, $value) {
        $setting = (new static)->firstOrCreate([
           'key' => $key,
        ]);

        $setting->update([
            "value" => encrypt($value)
        ]);

        return $setting;
    }
}
