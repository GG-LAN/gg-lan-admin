<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                            => $this->id,
            "name"                          => $this->name,
            "description"                   => $this->description,
            "image"                         => $this->image,
            "tournament_id"                 => $this->tournament_id,
            "registration_state"            => $this->registration_state,
            "captain_id"                    => $this->captain_id,
            "captain"                       => new UserResource($this->captain),
            "team_slots"                    => $this->team_slots,
            "users_count"                   => $this->whenCounted("users"),
            "users"                         => UserResource::collection($this->users),

            "registration_state_updated_at" => $this->registration_state_updated_at,
            "updated_at"                    => $this->updated_at,
            "created_at"                    => $this->created_at,

        ];
    }
}
