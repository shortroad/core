<?php

namespace App\Http\Middleware\Api\V1;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JsonErrorResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // get response
        $response = $next($request);

        // if response is JSON
        if($response instanceof JsonResponse){
            $responseData = $response->getData();
            if(isset($responseData->errors)){
                $responseData->success = false;
                $response->setData($responseData);
            }
        }

        return $response;
    }
}
