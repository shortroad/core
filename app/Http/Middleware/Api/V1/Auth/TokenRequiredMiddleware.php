<?php

namespace App\Http\Middleware\Api\V1\Auth;

use App\Helpers\Api\V1\JsonResponse;
use App\Helpers\JwtToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenRequiredMiddleware
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

        if (empty($token))
            return JsonResponse::failedResponse(
                ['token' => 'Token is required.'],
                'Token is required.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );

        if (!JwtToken::validate($token))
            return JsonResponse::failedResponse(
                ['token' => 'Token is invalid.'],
                'Token is invalid.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );

        return $next($request);
    }
}
