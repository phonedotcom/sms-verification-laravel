<?php

namespace Phonedotcom\SmsVerification\Exceptions;

/**
 * This exception is being used for exceptions during Code validation process.
 * It is NOT used for negative result of validation.
 * Class ValidateCodeException
 * @package Phonedotcom\SmsVerification\Exceptions
 */
class ValidateCodeException extends SmsVerificationException {}