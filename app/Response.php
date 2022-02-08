<?php

namespace App;

use Illuminate\Http\Client\Response as ClientResponse;

class Response
{
    public static function handle(ClientResponse $response, string $type): ClientResponse
    {
        $exception = '\App\Exceptions\FailedToGet'. $type;
        if ((int) $response->getStatusCode() === 204) {
            throw new $exception;
        }

        if ($response->failed()) {
            throw new $exception;
        }

        return $response;
    }
}
