<?php

namespace Phonedotcom\SmsVerification;

/**
 * Class SmsVerification
 * @package Phonedotcom\SmsVerification
 */
class SmsVerification
{

    /**
     * Send code
     * @param $phoneNumber
     * @return bool
     */
    public static function sendCode($phoneNumber)
    {
        try {
            $code = CodeProcessor::getInstance()->generateCode($phoneNumber);
            $translationCode = config('sms-verification.message-translation-code');
            $text = $translationCode
                ? trans($translationCode, ['code' => $code])
                : 'SMS verification code: ' . $code;
            $senderClassName = config('sms-verification.sender-class', Sender::class);
            $sender = $senderClassName::getInstance();
            if (!($sender instanceof SenderInterface)){
                throw new \Exception('Sender class ' . $senderClassName . ' doesn\'t implement SenderInterface');
            }
            $result = $sender->send($phoneNumber, $text);
        } catch (\Exception $e) {
            Log::error('SMS Verification code sending was failed: ' . $e->getMessage());
            $result = false;
        }
        return $result;
    }

    /**
     * Check code
     * @param $code
     * @param $phoneNumber
     * @return bool
     */
    public static function checkCode($code, $phoneNumber)
    {
        try {
            $result = CodeProcessor::getInstance()->validateCode($code, $phoneNumber);
        } catch (\Exception $e) {
            Log::error('SMS Verification check was failed: ' . $e->getMessage());
            $result = false;
        }
        return $result;
    }

}