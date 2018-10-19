<?php

namespace App\Util;

interface SluggerInterface
{
    public static function slugify(string $string): string;
}
