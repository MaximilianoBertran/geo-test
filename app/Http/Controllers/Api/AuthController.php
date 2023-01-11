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
     * @OA\Post(
     * path="/api/user/register",
     * summary="Register",
     * description="Register a new user",
     * tags={"User"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Register new user",
     *    @OA\JsonContent(
     *       required={"username","email","password","password_confirmation"},
     *       @OA\Property(property="username", type="string", format="string", example="example"),
     *       @OA\Property(property="email", type="email", format="email", example="example@test.com"),
     *       @OA\Property(property="password", type="string", format="password", example="holamundo"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", example="holamundo")
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
    public function register(StoreUserRequest $request)
    {
        $fields = $request->all();
        $fields['password'] = Hash::make($request->password);

        $user = User::create($fields);
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer'], Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     * path="/api/user/login",
     * summary="Login",
     * description="Login by username and password",
     * tags={"User"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Login with user credentials",
     *    @OA\JsonContent(
     *       required={"username","password"},
     *       @OA\Property(property="username", type="string", format="string", example="example"),
     *       @OA\Property(property="password", type="string", format="password", example="holamundo"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success"
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Invalid credentials"
     * )
     * )
     */
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

    /**
     * @OA\Get(
     * security={{"bearerAuth":{}}},
     * path="/api/user/user-profile",
     * summary="User Profile",
     * description="Get user profile",
     * tags={"User"},
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
    public function userProfile(Request $request)
    {
        return $this->successResponse(['data' => auth()->user()]);
    }

    /**
     * @OA\Post(
     * security={{"bearerAuth":{}}},
     * path="/api/user/logout",
     * summary="Logout",
     * description="Close user session",
     * tags={"User"},
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
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->successResponse(['message' => 'You have successfully out and the token was successfully deleted']);
    }
}
