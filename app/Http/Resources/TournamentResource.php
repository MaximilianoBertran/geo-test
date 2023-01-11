<?php

namespace App\Http\Resources;

use App\Models\Gender;
use Illuminate\Http\Resources\Json\JsonResource;

class TournamentResource extends JsonResource
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
            'title' => $this->title,
            'gender_id' => Gender::find($this->gender_id)->title,
            'winner' => $this->player,
            'players' => $this->players
        ];
    }
}
