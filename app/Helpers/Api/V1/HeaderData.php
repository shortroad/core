<?php

namespace App\Helpers\Api\V1;

use App\Helpers\JwtToken;
use Illuminate\Http\Request;

class HeaderData
{
    public static function getAuthTokenDecodedData(Request $request, string $field = null): object|string
    {
        $token = $request->header('Authorization');
        $jwtTokenData = JwtToken::decode($token);
        return $jwtTokenData->{$field} ?? $jwtTokenData;
    }
}

