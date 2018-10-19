<?php


namespace App\Util;

class Slugger implements SluggerInterface
{
    public static function slugify(string $string): string
    {
        $string = mb_strtolower($string);
        $string = trim($string);
        $string = str_replace(' ', '-', $string);

        return $string;
    }
}
