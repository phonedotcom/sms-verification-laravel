<?php

namespace Phonedotcom\SmsVerification\Exceptions;

/**
 * This exception is being used for exceptions during Code generating process.
 * Class GenerateCodeException
 * @package Phonedotcom\SmsVerification\Exceptions
 */
class GenerateCodeException extends SmsVerificationException {

    public function getErrorCode(){
        return 500 + min($this->getCode(), 99);
    }

}