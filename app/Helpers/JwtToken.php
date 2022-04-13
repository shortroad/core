<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtToken
{
    public static function generate(array $data): string
    {
        return JWT::encode($data, env('JWT_KEY'), env('JWT_ALGORITHM'));
    }

    public static function validate(string $token): bool
    {
        $decodedToken = self::decode($token);
        if (!$decodedToken instanceof \stdClass) {
            return false;
        }
        return true;
    }

    public static function decode(string $token): object|bool
    {
        if (!preg_match('/Bearer\s(\S+)/', $token, $matches))
            return false;

        $token = $matches[1];

        try {
            return JWT::decode($token, new Key(env('JWT_KEY'), env('JWT_ALGORITHM')));
        } catch (\Exception $e) {
            // TODO log error
        }

        return false;
    }
}
