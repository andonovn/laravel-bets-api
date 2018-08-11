<?php

namespace Andonovn\LaravelBetsApi\Events;

use GuzzleHttp\Exception\TransferException;
use Illuminate\Foundation\Events\Dispatchable;

class RequestFailed
{
    use Dispatchable;

    /**
     * @var TransferException
     */
    protected $exception;

    /**
     * Create a new event instance.
     *
     * @param  TransferException  $exception
     */
    public function __construct(TransferException $exception)
    {
        $this->exception = $exception;
    }

    /**
     * Getter for the exception
     * 
     * @return TransferException
     */
    public function getException() : TransferException
    {
        return $this->exception;
    }
}
