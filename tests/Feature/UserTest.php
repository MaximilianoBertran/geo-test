<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{

    public function test_register_success () {
        $response = $this->json('post', '/api/user/register', [
            'username' => fake()->unique()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => "holamundo",
            'password_confirmation' => "holamundo"
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            "data" => [
                "username",
                "email",
                "id"
            ],
            "access_token",
            "token_type"
        ]);
    }

    public function test_register_fail () {
        $response = $this->json('post', '/api/user/register', [
            'username' => "example",
            'email' => "example@test.com",
            'password' => "holamundo",
            'password_confirmation' => "holamundo2"
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            "error",
            "code"
        ]);
    }

    public function test_login_success () {
        $user = User::factory()->create();
        $response = $this->json('post', '/api/user/login', [
            'username' => $user->username,
            'password' => "holamundo"
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                "id",
                "username",
                "email"
            ],
            "access_token",
            "token_type"
        ]);
    }

    public function test_login_auth_fail () {
        $user = User::factory()->create();
        $response = $this->json('post', '/api/user/login', [
            'username' => $user->username,
            'password' => "kjagsfkasf"
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure([
            "error",
            "code"
        ]);
    }

    public function test_user_profile_success () {
        $user = User::factory()->create();
        $response = $this->json('post', '/api/user/login', [
            'username' => $user->username,
            'password' => "holamundo"
        ])->decodeResponseJson();
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$response['access_token'] ])->json('get', '/api/user/user-profile');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                "id",
                "username",
                "email"
            ]
        ]);
    }

    public function test_user_profile_auth_fail () {
        $response = $this->json('get', '/api/user/user-profile');

        $response->assertStatus(401);
        $response->assertJsonStructure([
            "error",
            "code"
        ]);
    }

    public function test_logout_success () {
        $user = User::factory()->create();
        $response = $this->json('post', '/api/user/login', [
            'username' => $user->username,
            'password' => "holamundo"
        ])->decodeResponseJson();
        $response = $this->withHeaders(['Authorization'=>'Bearer '.$response['access_token'] ])->json('post', '/api/user/logout');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "message"
        ]);
    }

    public function test_logout_auth_fail () {
        $response = $this->json('post', '/api/user/logout');

        $response->assertStatus(401);
        $response->assertJsonStructure([
            "error",
            "code"
        ]);
    }


}
