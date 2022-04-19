<?php

namespace App\Http\Middleware\Api\V1\Auth;

use App\Helpers\Api\V1\JsonResponse;
use App\Helpers\JwtToken;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenValidationDataMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
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
        return $next($request);
    }
}
