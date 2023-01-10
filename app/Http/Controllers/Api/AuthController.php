<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponser;

    /**
     * Create an instance of User.
     * @return Illuminate\Http\Response
     */
    public function register(StoreUserRequest $request)
    {
        $fields = $request->all();
        $fields['password'] = Hash::make($request->password);

        $user = User::create($fields);
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer'], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return $this->successResponse(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer']);
        } else {
            return $this->errorResponse("Invalid credentials", Response::HTTP_UNAUTHORIZED);
        }
    }

    public function userProfile(Request $request)
    {
        return $this->successResponse(['data' => auth()->user()]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->successResponse(['message' => 'You have successfully out and the token was successfully deleted']);
    }
}
