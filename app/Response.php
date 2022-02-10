<?php

namespace App;

use Illuminate\Http\Client\Response as ClientResponse;

class Response
{
    public static function handle(ClientResponse $response, string $type): ClientResponse
    {
        $exception = '\App\Exceptions\FailedToGet'. $type;

        throw_if($response->failed() || $response->getStatusCode() === 204, $exception);

        return $response;
    }
}
