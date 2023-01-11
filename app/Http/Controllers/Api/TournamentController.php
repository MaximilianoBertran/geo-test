<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\TournamentRequest;
use App\Http\Resources\TournamentResource;
use App\Models\Gender;
use App\Models\Player;
use App\Models\Tournament;
use App\Traits\ApiResponser;

class TournamentController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($gender_id = null)
    {
        if (!$gender_id) {
            $tournaments = TournamentResource::collection(Tournament::all());
        } else {
            if( $gender_id == Gender::MALE or $gender_id == Gender::FEMALE ) {
                $tournaments = TournamentResource::collection(Tournament::where('gender_id', $gender_id));
            } else {
                return $this->errorResponse("{$gender_id} is a invalid gender", Response::HTTP_BAD_REQUEST);
            }
        }
        return $this->successResponse(['data' => $tournaments]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TournamentRequest $request)
    {
        $tournament = new Tournament();
        $tournament->title = $request->title;
        $tournament->gender_id = $request->gender_id;
        $tournament->player_id = null;
        $tournament->save();
        $players = [];
        foreach ($request->players as $player) {
            $p = Player::where('credential_code', $player['credential_code'])->first();
            $p ? $p->update($player) : $p = Player::create($player);
            $players[] = $p->id;
        }
        $tournament->players()->sync($players);
        $tournament->play();
        $tournament->fresh();

        $response = $tournament->toArray();
        $response['winner'] = $tournament->player;
        $response['players'] = $tournament->players;
        return $this->successResponse(['data' => $response]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = new TournamentResource(Tournament::findOrFail($id));
        return $this->successResponse(['data' => $response]);
    }
}
