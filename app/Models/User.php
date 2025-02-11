<?php
namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'admin'             => 'bool',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->admin;
    }

    public function teams()
    {
        return $this->belongsToMany('App\Models\Team')
            ->withPivot('captain')
            ->withTimestamps();
    }

    public function tournaments()
    {
        return $this->belongsToMany('App\Models\Tournament');
    }

    public function purchasedPlaces()
    {
        return $this->hasMany('App\Models\PurchasedPlace');
    }

    public function isAdmin()
    {
        return $this->admin ? true : false;
    }
}
