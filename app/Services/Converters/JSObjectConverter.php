<?php

namespace App\Services\Converters;

use Illuminate\Support\Facades\Log;

class JSObjectConverter implements Converter
{
    public const START_REQUEST_PATTERN = "(\[request ([0-9a-z\-]+)] ([A-Z]{2}:: Incoming request:))";
    public const END_REQUEST_PATTERN = "(} {2}\+\d{0,2}ms)";
    public const QUOTES_PATTERN = '/([^\\\])"/';
    public const JS_OBJECTS_PATTERN = "/\[Object.+] {}|\[Object]/";
    public const KEYS_PATTERN = "/([^{}\[\]#,]+): /";

    public static function convertToArray(string $data): array|bool
    {
        $convertedString = $data;

        $convertedString = self::removeObjects($convertedString);

        $convertedString = self::fixQuoteEscaping($convertedString);

        $convertedString = self::addQuotesForKeysAndValues($convertedString);
        try {
            return json_decode($convertedString, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public static function addQuotesForKeysAndValues(string $data): array|string|null
    {
        $data = preg_replace_callback(
            self::KEYS_PATTERN,
            static fn($matches) => '"' . trim($matches[1], "'") . '": ',
            $data
        );

        return str_replace("'", '"', $data);
    }

    public static function fixQuoteEscaping(string $data): array|string|null
    {
        return preg_replace(self::QUOTES_PATTERN, '$1\"', $data);
    }

    public static function removeObjects(string $data): array|string|null
    {
        $data = preg_replace(self::START_REQUEST_PATTERN, "", $data);
        return preg_replace(self::JS_OBJECTS_PATTERN, "{}", $data);
    }
}
