<?php

namespace App\Http\Resources;

use App\Models\Gender;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'credential_code' => $this->credential_code,
            'gender_id' => Gender::find($this->gender_id)->title,
            'ability' => $this->ability,
            'streng' => $this->streng,
            'speed' => $this->speed,
            'reaction' => $this->reaction,
            'won_games' => $this->won_games,
            'lost_games' => $this->lost_games,
            'played_tournaments' => $this->tournaments,
            'win_tournaments' => $this->tournaments_won
        ];
    }
}
