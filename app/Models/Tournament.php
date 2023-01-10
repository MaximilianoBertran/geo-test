<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'gender_id',
        'player_id',
    ];

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function players() {
        return $this->belongsToMany(Player::class, 'list_players');
    }
}
