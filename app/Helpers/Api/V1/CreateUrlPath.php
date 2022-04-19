<?php

namespace App\Helpers\Api\V1;

class CreateUrlPath
{
    public static function getPath(int|null $extraNumber = null): string
    {
        $time = (int)(microtime(true) * 1000) . $extraNumber;
        $number = ltrim($time, 1);
        return self::convertToBase62($number);
    }

    // TODO Refactor this method
    public static function convertToBase62(int $number): string
    {
        $base = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $r = $number % 62;
        $res = $base[$r];
        $q = floor($number / 62);
        while ($q) {
            $r = $q % 62;
            $q = floor($q / 62);
            $res = $base[$r] . $res;
        }
        return $res;
    }
}
