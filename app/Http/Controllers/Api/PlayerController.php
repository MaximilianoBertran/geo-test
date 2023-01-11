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
     * @OA\Get(
     * security={{"bearerAuth":{}}},
     * path="/api/player",
     * summary="Players",
     * description="Get a list of players. ",
     * tags={"Player"},
     * @OA\Response(
     *    response=200,
     *    description="Success"
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthenticated"
     * )
     * )
     */
    public function index()
    {
        $players = PlayerResource::collection(Player::orderBy('name', 'DESC')->get());
        return $this->successResponse(['data' => $players]);
    }

    /**
     * @OA\Get(
     * security={{"bearerAuth":{}}},
     * path="/api/player/show/{id}",
     * summary="Get Player",
     * description="Get player detail",
     * tags={"Player"},
     * @OA\Parameter(
     *  name="id",
     *  in="path",
     *  description="Find player by ID",
     *  required=true,
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success"
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthenticated"
     * )
     * )
     */
    public function show($id)
    {
        $response = new PlayerResource(Player::findOrFail($id));
        return $this->successResponse(['data' => $response]);
    }
}
