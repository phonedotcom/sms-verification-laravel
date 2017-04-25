<?php

namespace Phonedotcom\SmsVerification;

use Illuminate\Http\Request;

/**
 * Class SmsVerification
 * @package Phonedotcom\SmsVerification
 */
class SmsVerification
{

    /**
     * Register endpoints in routing
     * @param $router
     */
    public static function registerRoutes($router)
    {
        $router->post('/sms-verification', function (Request $request) {
            return response()->json([
                'success' => self::sendCode($request->input('phone_number'))
            ]);
        });
        $router->get('/sms-verification/{code}/{number}', function ($code, $phoneNumber) {
            return response()->json([
                'success' => self::checkCode($code, $phoneNumber)
            ]);
        });
    }

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
            $result = Sender::getInstance()->send($phoneNumber, $text);
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