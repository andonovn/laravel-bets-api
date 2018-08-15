<?php

namespace Andonovn\LaravelBetsApi\Exceptions;

use Psr\Http\Message\ResponseInterface;

class CallFailedException extends BetsApiException
{
    /**
     * Raise a CallFailedException
     * 
     * @param  string  $endpoint
     * @return CallFailedException
     */
    public static function whenAttemptedToReach(string $endpoint) : self
    {
        return new static('BetsApi call has failed. Endpoint: ' . $endpoint);
    }
}