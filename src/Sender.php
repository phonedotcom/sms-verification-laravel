<?php

namespace Phonedotcom\SmsVerification;

use Phonedotcom\SmsVerification\Exceptions\ConfigException;
use Phonedotcom\SmsVerification\Exceptions\SenderException;

/**
 * Class Sender
 * @package Phonedotcom\SmsVerification
 */
class Sender
{

    /**
     * Expected HTTP status for successful SMS sending request
     */
    const EXPECTED_HTTP_STATUS = 201;

    /**
     * Singleton instance
     * @var Sender
     */
    private static $instance;

    /**
     * Access-token for Phone.com API
     * @var string
     */
    private $accessToken;

    /**
     * API root URL
     * @var string
     */
    private $url;

    /**
     * User's Phone.com number which will be used for SMS sending
     * @var string
     */
    private $phoneNumber;

    /**
     * Phone.com account ID
     * @var int
     */
    private $accountId;

    /**
     * Phone.com extension ID
     * @var int|null
     */
    private $extensionId;

    /**
     * Singleton
     * @return Sender
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Sender constructor
     * @throws ConfigException
     */
    private function __construct()
    {
        $this->accessToken = config('sms-verification.phone-com-access-token');
        if (empty($this->accessToken)) {
            throw new ConfigException('Phone.com Access Token is not specified in config/sms-verification.php');
        }
        $this->url = rtrim(config('sms-verification.phone-com-api-url'), '/');
        if (empty($this->url)) {
            throw new ConfigException('Phone.com API URL is not specified in config/sms-verification.php');
        }
        $this->phoneNumber = config('sms-verification.phone-com-phone-number');
        if (empty($this->phoneNumber)) {
            throw new ConfigException('Phone.com Phone Number is not specified in config/sms-verification.php');
        }
        $this->accountId = config('sms-verification.phone-com-account-id');
        if (empty($this->accountId)) {
            throw new ConfigException('Phone.com Account ID is not specified in config/sms-verification.php');
        }
        // $this->extensionId = config('sms-verification.phone-com-extension-id'); // Phone.com doesn't support it yet
    }

    /**
     * Send SMS via Phone.com API
     * @return bool
     * @throws SenderException
     */
    public function send($to, $text)
    {
        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('POST', $this->buildApiCallUrl(), [
                'headers' => [
                    'User-Agent' => 'Phone.com/SMS-Verification-Laravel',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->accessToken,
                ],
                'json' => [
                    'text' => $text,
                    'from' => $this->phoneNumber,
                    'to' => $to,
                ],
            ]);
        } catch (\Exception $e){
            throw new SenderException('SMS sending was failed', null, 0, $e);
        }
        if ($res->getStatusCode() != self::EXPECTED_HTTP_STATUS){
            throw new SenderException('SMS was not sent', $res);
        }
        return true;
    }

    /**
     * Build SMS endpoint URL
     * @return string
     */
    private function buildApiCallUrl(){
        $url = $this->url . '/accounts/' . $this->accountId;
        if ($this->extensionId){
            $url .= '/extensions/' . $this->extensionId;
        }
        $url .= '/sms';
        return $url;
    }

}