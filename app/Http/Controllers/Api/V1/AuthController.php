<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Api\V1\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = $request->only(['name','email','password']);

        $user['password'] = Hash::make($user['password']);

        User::create($user);

        return jsonResponse::successResponse
        (
            $request->only(['name','email']),
            __('Api/V1/auth.register.successfully.message'),
            Response::HTTP_CREATED
        );
    }
}
