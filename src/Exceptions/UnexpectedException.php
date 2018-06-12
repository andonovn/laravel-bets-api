<?php

namespace Andonovn\LaravelBetsApi\Exceptions;

class UnexpectedException extends BetsApiException
{
    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * Set the unexpected exception
     *
     * @param  \Exception  $exception
     */
    public function setException(\Exception $exception)
    {
        $this->exception = $exception;
    }
}