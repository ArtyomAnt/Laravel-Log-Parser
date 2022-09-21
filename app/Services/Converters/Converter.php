<?php

namespace App\Services\Converters;

interface Converter
{
    public static function convertToArray(string $data):array|bool;
}
