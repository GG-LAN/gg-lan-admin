<?php

namespace App\Models;

use App\Models\Team;
use App\Models\User;
use App\Observers\TeamUserObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[ObservedBy([TeamUserObserver::class])]
class TeamUser extends Pivot
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
