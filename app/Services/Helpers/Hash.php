<?php

namespace App\Services\Helpers;

class Hash
{
    /**
     * @param string $string
     * @return string
     */
    public static function make(string $string): string
    {
        return md5(md5(md5($string)));
    }
}
