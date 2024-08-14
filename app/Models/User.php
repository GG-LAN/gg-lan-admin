<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'pseudo',
        'birth_date',
        'image',
        'admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'admin' => 'bool'
        ];
    }

    public function teams() {
        return $this->belongsToMany('App\Models\Team')->withPivot('captain');
    }

    public function tournaments() {
        return $this->belongsToMany('App\Models\Tournament');
    }

    public function purchasedPlaces() {
        return $this->hasMany('App\Models\PurchasedPlace');
    }

    public function isAdmin() {
        return $this->admin ? true : false;
    }
}
