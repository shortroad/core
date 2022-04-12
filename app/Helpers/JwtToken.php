<?php

namespace App\Helpers;

use Firebase\JWT\JWT;

class JwtToken
{
    public static function generate(array $data): string
    {
        return JWT::encode($data, env('JWT_KEY'), env('JWT_ALGORITHM'));
    }
}
