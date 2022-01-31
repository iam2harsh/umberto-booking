<?php

namespace App;

use Illuminate\Http\Client\Response as ClientResponse;

class Response
{
    public static function handle(ClientResponse $response, string $type): ClientResponse
    {
        $exception = '\App\Exceptions\FailedToGet'. $type;
        if (static::hasNoContent($response)) {
            throw new $exception;
        }

        if ($response->failed()) {
            throw new $exception;
        }

        return $response;
    }

    private static function hasNoContent(ClientResponse $response): bool
    {
        if ($response === false) {
            return false;
        }

        return (int) $response->getStatusCode() === 204;
    }
}
