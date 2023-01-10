<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'credential_code',
        'gender_id',
        'ability',
        'streng',
        'speed',
        'reaction',
        'won_games',
        'lost_games'
    ];

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function tournaments() {
        return $this->belongsToMany(Tournaments::class, 'list_players');
    }
}
