<?php

namespace Andonovn\LaravelBetsApi\Events;

use Psr\Http\Message\ResponseInterface;
use Illuminate\Foundation\Events\Dispatchable;

class ResponseReceived
{
    use Dispatchable;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var string
     */
    protected $jsonResponse;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * Create a new event instance.
     *
     * @param  ResponseInterface  $response
     * @param  string  $jsonResponse
     * @param  string  $endpoint
     */
    public function __construct(ResponseInterface $response, string $jsonResponse, string $endpoint)
    {
        $this->response = $response;
        $this->jsonResponse = $jsonResponse;
        $this->endpoint = $endpoint;
    }

    /**
     * Getter for the response
     * 
     * @return ResponseInterface
     */
    public function response() : ResponseInterface
    {
        return $this->response;
    }

    /**
     * Getter for the JSON response
     * 
     * @return string
     */
    public function jsonResponse() : string
    {
        return $this->jsonResponse;
    }
    
    /**
     * Getter for the endpoint
     * 
     * @return string
     */
    public function endpoint() : string
    {
        return $this->endpoint;
    }
}
