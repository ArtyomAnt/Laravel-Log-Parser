<?php

namespace App\DTO;

use Illuminate\Support\Facades\Log;

class ConsentDTO
{
    public string $path;
    public string $request_id;
    public string $apiKey;
    public string $action;
    public string $visitor_id;
    public string $url;
    public string $config_version;
    public string $granular_metadata;
    public string $headers;

    public static function createFromArray(array $data): array|bool
    {
        try {
            return [
                'request_id' => $data['request_id'],
                'path' => $data['path'],
                'apiKey' => $data['body']['apiKey'],
                'action' => $data['body']['action'],
                'visitor_id' => $data['body']['visitor_id'],
                'url' => $data['body']['url'],
                'config_version' => $data['body']['config_version'],
                'granular_metadata' => json_encode($data['body']['granular_metadata'], JSON_THROW_ON_ERROR),
                'headers' => json_encode($data['headers'], JSON_THROW_ON_ERROR)
            ];
        } catch (\JsonException $exception) {
            Log::error($exception->getMessage());
            return false;
        }
    }

}
