<?php

namespace Tests\Feature;

use App\Models\Gender;
use App\Models\Player;
use App\Models\User;
use Tests\TestCase;

class TournamentTest extends TestCase
{
    public function test_new_tournament_success () {
        $user = User::factory()->create();
        $response = $this->json('post', '/api/user/login', [
            'username' => $user->username,
            'password' => "holamundo"
        ])->decodeResponseJson();
        $players = [];
        foreach(Player::factory()->count(4)->create() as $player) {
            $players[] = [
                "name" => $player->name,
                "credential_code" => $player->credential_code,
                "gender_id" => $player->gender_id,
                "ability" => $player->ability,
                "streng" => $player->streng,
                "speed" => $player->speed,
                "reaction" => $player->reaction
            ];
        }
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$response['access_token'] ])->json('post', '/api/tournament/store', [
            "title" => fake()->company(),
            "gender_id" => Gender::MALE,
            "players" => $players
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                "id",
                "title",
                "gender_id",
                "updated_at",
                "created_at",
                "players" ,
                "winner"
            ]
        ]);
    }

    public function test_new_tournament_gender_fail () {
        $user = User::factory()->create();
        $response = $this->json('post', '/api/user/login', [
            'username' => $user->username,
            'password' => "holamundo"
        ])->decodeResponseJson();
        $players = [];
        foreach(Player::factory()->count(4)->create() as $player) {
            $players[] = [
                "name" => $player->name,
                "credential_code" => $player->credential_code,
                "gender_id" => $player->gender_id,
                "ability" => $player->ability,
                "streng" => $player->streng,
                "speed" => $player->speed,
                "reaction" => $player->reaction
            ];
        }
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$response['access_token'] ])->json('post', '/api/tournament/store', [
            "title" => fake()->company(),
            "gender_id" => Gender::FEMALE,
            "players" => $players
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            "error",
            "code"
        ]);
    }

    public function test_tournament_auth_fail () {
        $response = $this->json('post', '/api/tournament/store');

        $response->assertStatus(401);
        $response->assertJsonStructure([
            "error",
            "code"
        ]);
    }

    public function test_tournament_list_success () {
        $user = User::factory()->create();
        $response = $this->json('post', '/api/user/login', [
            'username' => $user->username,
            'password' => "holamundo"
        ])->decodeResponseJson();
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$response['access_token'] ])->json('get', '/api/tournament/');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                [
                    "id",
                    "title",
                    "gender_id",
                    "winner",
                    "players"
                ]
            ]
        ]);
    }

    public function test_tournament_list_by_gender_success () {
        $user = User::factory()->create();
        $response = $this->json('post', '/api/user/login', [
            'username' => $user->username,
            'password' => "holamundo"
        ])->decodeResponseJson();
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$response['access_token'] ])->json('get', '/api/tournament/gender/'.Gender::MALE);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                [
                    "id",
                    "title",
                    "gender_id",
                    "winner",
                    "players"
                ]
            ]
        ]);
    }

    public function test_tournament_list_auth_fail () {
        $response = $this->json('get', '/api/tournament');

        $response->assertStatus(401);
        $response->assertJsonStructure([
            "error",
            "code"
        ]);
    }

    public function test_tournament_show_success () {
        $user = User::factory()->create();
        $response = $this->json('post', '/api/user/login', [
            'username' => $user->username,
            'password' => "holamundo"
        ])->decodeResponseJson();
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$response['access_token'] ])->json('get', '/api/tournament/show/1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                "id",
                "title",
                "gender_id",
                "winner",
                "players"
            ]
        ]);
    }

    public function test_tournament_show_not_found () {
        $user = User::factory()->create();
        $response = $this->json('post', '/api/user/login', [
            'username' => $user->username,
            'password' => "holamundo"
        ])->decodeResponseJson();
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$response['access_token'] ])->json('get', '/api/tournament/show/9999999999');

        $response->assertStatus(404);
        $response->assertJsonStructure([
            "error",
            "code"
        ]);
    }

    public function test_tournament_show_auth_fail () {
        $response = $this->json('get', '/api/tournament/show/1');

        $response->assertStatus(401);
        $response->assertJsonStructure([
            "error",
            "code"
        ]);
    }
}
