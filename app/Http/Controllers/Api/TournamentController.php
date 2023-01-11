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
     * @OA\Get(
     * security={{"bearerAuth":{}}},
     * path="/api/tournament",
     * summary="Tournaments",
     * description="Get a list of tournaments. ",
     * tags={"Tournament"},
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
     * @OA\Post(
     * path="/api/tournament/store",
     * summary="Create tournament",
     * description="Register and play a new tournament",
     * tags={"Tournament"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Register and play a new tournament. IMPORTANT ability min 0 max 100| streng-speed-reaction min 1 max 10 | gender_id need values 1=Male 2=Female | all players and tournament needs to be a same gender_id | the number of players needs to be a power of 2",
     *    @OA\JsonContent(
     *       required={"title","gender_id","players"},
     *       @OA\Property(property="title", type="string", example="Grand Slime"),
     *       @OA\Property(property="gender_id", type="integer", example="1"),
     *       @OA\Property(property="players", type="array",
     *          @OA\Items(
     *              @OA\Property(property="name", type="string", example="Pepe"),
     *              @OA\Property(property="credential_code", type="string", example="pp25"),
     *              @OA\Property(property="gender_id", type="integer", example="1"),
     *              @OA\Property(property="ability", type="integer", example="50"),
     *              @OA\Property(property="streng", type="integer", example="3"),
     *              @OA\Property(property="speed", type="integer", example="3"),
     *              @OA\Property(property="reaction", type="integer", example="6"),
     *          ),
     *       )
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success"
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Incorrect data in body"
     * )
     * )
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
     * @OA\Get(
     * security={{"bearerAuth":{}}},
     * path="/api/tournament/show/{id}",
     * summary="Get Tournament",
     * description="Get tournament detail",
     * tags={"Tournament"},
     * @OA\Parameter(
     *  name="id",
     *  in="path",
     *  description="Find by ID",
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
        $response = new TournamentResource(Tournament::findOrFail($id));
        return $this->successResponse(['data' => $response]);
    }
}
