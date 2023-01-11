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

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function tournaments_won() {
        return $this->hasMany(Tournament::class, 'player_id');
    }

    public function tournaments() {
        return $this->belongsToMany(Tournament::class, 'list_players');
    }

    public function matchPoints() {
        $luck = rand(75, 125) / 100;
        if($this->gender_id == Gender::MALE) {
            return ($this->ability + $this->streng + $this->speed) * $luck;
        } else {
            return ($this->ability + $this->reaction) * $luck;
        }
    }

    public function win_game() {
        $this->won_games ++;
        $this->save();
    }

    public function lose_game() {
        $this->lost_games ++;
        $this->save();
    }
}
