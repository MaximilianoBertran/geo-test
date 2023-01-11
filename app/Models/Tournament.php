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

    protected $hidden = [
        'player_id',
    ];

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function players() {
        return $this->belongsToMany(Player::class, 'list_players');
    }

    public function play() {
        $round = $this->players->count() / 2;
        $round_players = $this->players;
        while ( count($round_players) > 1 ) {
            $players = [];
            for ($i=0; $i < $round; $i++) { 
                $player1_points = 0;
                $player2_points= 0;
                while($player1_points == $player2_points) {
                    $player1_points = $round_players[$i*2]->matchPoints();
                    $player2_points = $round_players[$i*2+1]->matchPoints();
                }
                if($player1_points > $player2_points) {
                    $players[] = $round_players[$i*2];
                    $round_players[$i*2]->win_game();
                    $round_players[$i*2+1]->lose_game();
                } else {
                    $players[] = $round_players[$i*2+1];
                    $round_players[$i*2+1]->win_game();
                    $round_players[$i*2]->lose_game();
                }
            }
            $round_players = $players;
            $round = $round / 2;
        }
        $this->player_id = $round_players[0]->id;
        $this->save();
    }
}
