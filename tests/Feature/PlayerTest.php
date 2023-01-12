<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\User;
use Tests\TestCase;

class PlayerTest extends TestCase
{

    public function test_player_list_success () {
        $user = User::factory()->create();
        $response = $this->json('post', '/api/user/login', [
            'username' => $user->username,
            'password' => "holamundo"
        ])->decodeResponseJson();
        Player::factory()->create();
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$response['access_token'] ])->json('get', '/api/player');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                [
                    "id",
                    "name",
                    "credential_code",
                    "gender_id",
                    "ability",
                    "streng",
                    "speed",
                    "reaction",
                    "won_games",
                    "lost_games",
                    "played_tournaments",
                    "win_tournaments"
                ]
            ]
        ]);
    }

    public function test_player_list_auth_fail () {
        $response = $this->json('get', '/api/player');

        $response->assertStatus(401);
        $response->assertJsonStructure([
            "error",
            "code"
        ]);
    }

    public function test_player_show_success () {
        $user = User::factory()->create();
        $response = $this->json('post', '/api/user/login', [
            'username' => $user->username,
            'password' => "holamundo"
        ])->decodeResponseJson();
        $player = Player::factory()->create();
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$response['access_token'] ])->json('get', '/api/player/show/'.$player->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                "id",
                "name",
                "credential_code",
                "gender_id",
                "ability",
                "streng",
                "speed",
                "reaction",
                "won_games",
                "lost_games",
                "played_tournaments",
                "win_tournaments"
            ]
        ]);
    }

    public function test_player_show_not_found () {
        $user = User::factory()->create();
        $response = $this->json('post', '/api/user/login', [
            'username' => $user->username,
            'password' => "holamundo"
        ])->decodeResponseJson();
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$response['access_token'] ])->json('get', '/api/player/show/9999999999');

        $response->assertStatus(404);
        $response->assertJsonStructure([
            "error",
            "code"
        ]);
    }

    public function test_player_show_auth_fail () {
        $response = $this->json('get', '/api/player');

        $response->assertStatus(401);
        $response->assertJsonStructure([
            "error",
            "code"
        ]);
    }
}
