<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TournamentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"             => $this->id,
            "name"           => $this->name,
            "description"    => $this->description,
            "start_date"     => $this->start_date,
            "end_date"       => $this->end_date,
            "game_id"        => $this->game_id,
            "places"         => $this->places,
            "cashprize"      => $this->cashprize,
            "status"         => $this->status,
            "image"          => $this->image,
            "type"           => $this->type,
            "register_count" => $this->register_count,
            "isFull"         => $this->isFull,
            "price"          => $this->price,
            "external_url"   => $this->external_url,

            "players"        => $this->when(
                $this->type == "solo",
                UserResource::collection($this->players)
            ),

            "teams"          => $this->when(
                $this->type == "team",
                TeamResource::collection($this->teams)
            ),

            "updated_at"     => $this->updated_at,
            "created_at"     => $this->created_at,
        ];
    }
}
