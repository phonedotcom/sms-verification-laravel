<?php

namespace Phonedotcom\SmsVerification;

use Phonedotcom\SmsVerification\Exceptions\SenderException;

/**
 * Interface SenderInterface
 * @package Phonedotcom\SmsVerification
 */
interface SenderInterface
{

    /**
     * Singleton
     * @return Sender
     */
    public static function getInstance();

    /**
     * Send SMS via Phone.com API
     * @param string $to
     * @param string $text
     * @return bool
     * @throws SenderException
     */
    public function send($to, $text);

}