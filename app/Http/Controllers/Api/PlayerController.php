<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Traits\ApiResponser;

class PlayerController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = PlayerResource::collection(Player::orderBy('name', 'DESC')->get());
        return $this->successResponse(['data' => $players]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = new PlayerResource(Player::findOrFail($id));
        return $this->successResponse(['data' => $response]);
    }
}
