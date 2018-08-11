<?php

namespace Andonovn\LaravelBetsApi\Exceptions;

use Psr\Http\Message\ResponseInterface;

class CallFailedException extends BetsApiException
{
    /**
     * Raise a CallFailedException
     * 
     * @param  ResponseInterface  $response
     * @param  string  $endpoint
     * @return CallFailedException
     */
    public static function raise(ResponseInterface $response, string $endpoint) : self
    {
        $responseAsString = $response->getBody()->getContents();

        if (! is_string($responseAsString)) {
            $responseAsString = json_encode($responseAsString);
        }

        return new static('BetsApi call has failed. Endpoint: ' . $endpoint . ' Response: ' . $responseAsString);
    }
}