<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Api\V1\JsonResponse;
use App\Helpers\JwtToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Requests\Api\V1\Auth\GetTokenRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = $request->only(['name', 'email', 'password']);

        $user['password'] = Hash::make($user['password']);

        User::create($user);

        return jsonResponse::successResponse
        (
            $request->only(['name', 'email']),
            __('Api/V1/auth.register.successfully.message'),
            Response::HTTP_CREATED
        );
    }

    public function getToken(GetTokenRequest $request)
    {
        $credential = $request->only(['email', 'password']);
        if (!Auth::attempt($credential, false)) {
            return JsonResponse::failedResponse(
                ['credential' => 'Email or Password not correct'],
                'User credential not correct',
                Response::HTTP_UNAUTHORIZED
            );
        }
        $user = User::where('email', $request->input('email'))->first();
        $token = JwtToken::generate([
            'user_id' => $user->id,
            'created_at' => time()
        ]);
        return JsonResponse::successResponse(
            ['token' => $token],
            'You\'r token is ready',
            Response::HTTP_OK
        );
    }

    public function whoAmI(Request $request)
    {
        $token = $request->header('Authorization');
        $user_id = (JwtToken::decode($token))->user_id;
        $user = User::find($user_id);
        if (is_null($user))
            return JsonResponse::failedResponse(
                ['token' => 'The user was not found with this token.'],
                'The user was not found with this token.',
                Response::HTTP_UNAUTHORIZED
            );
        return JsonResponse::successResponse(
            [
                'name' => $user->name,
                'email' => $user->email
            ],
            'Your data is ready.',
            Response::HTTP_OK
        );
    }
}
