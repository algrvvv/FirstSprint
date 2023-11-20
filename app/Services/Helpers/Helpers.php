<?php

namespace App\Services\Helpers;

class Helpers
{
    public static array $sql_functions = [
        'count('
    ];

    /**
     * @param array $array
     * @param string $separator
     */
    public static function implode_sql(array $array, string $separator = "'")
    {
        switch ($separator) {
            case "'":
                return implode(", ", array_map(function ($string) {
                    if ($string == '*' || (str_contains($string, '(') && str_contains($string, ')'))) {
                        return $string;
                    }
                    return "'" . $string . "'";
                }, $array));
            case "`":
                return implode(", ", array_map(function ($string) {
                    if ($string == '*' || (str_contains($string, '(') && str_contains($string, ')'))) {
                        return $string;
                    }
                    return "`" . $string . "`";
                }, $array));
        }
    }
}
