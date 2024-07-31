<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        "question", "response"
    ];

    public static function getFaqs($numberOfItemsPerPage = 5, $search = null) {
        $query = (new static);

        // If search parameter is given
        if ($search) {
            $query = $query->where(function ($queryWhere) use ($search) {
                $queryWhere->orWhere("question",   "like", "%{$search}%")
                      ->orWhere("response", "like", "%{$search}%");
            });
        }
        
        return $query
        ->paginate($numberOfItemsPerPage)
        ->withQueryString()
        ->through(function($faq) {
            return [
                "id"        => $faq->id,
                "question"  => $faq->question,
                "response"  => $faq->response,
            ];
        });
    }
}
