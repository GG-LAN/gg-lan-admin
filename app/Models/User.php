<?php
namespace App\Models;

use App\Traits\HasFaceit;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser, HasAvatar
{
    use HasFactory, Notifiable, HasApiTokens, HasFaceit;

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

    public function getFilamentAvatarUrl(): ?string
    {
        return "https://api.dicebear.com/9.x/bottts-neutral/svg?seed={$this->pseudo}";
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

    public function participations(): HasMany
    {
        return $this->hasMany(Participation::class);
    }

    public function isAdmin()
    {
        return $this->admin ? true : false;
    }

    public function participationsQuery()
    {
        return Participation::where("user_id", $this->id);
    }
}
