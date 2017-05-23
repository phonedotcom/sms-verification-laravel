<?php

namespace Phonedotcom\SmsVerification\Exceptions;

/**
 * This exception is being used for exceptions during SMS sending process.
 * Class SenderException
 * @package Phonedotcom\SmsVerification\Exceptions
 */
class SenderException extends SmsVerificationException {

    protected $httpResult;

    public function __construct($message = "", $httpResult = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->httpResult = $httpResult;
    }

    public function getHttpResult(){
        return $this->httpResult;
    }

    public function getErrorCode(){
        return 400 + min($this->getCode(), 99);
    }

}