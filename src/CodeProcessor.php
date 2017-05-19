<?php

namespace Phonedotcom\SmsVerification;

use Illuminate\Support\Facades\Cache;
use Phonedotcom\SmsVerification\Exceptions\GenerateCodeException;
use Phonedotcom\SmsVerification\Exceptions\ValidateCodeException;

/**
 * Class CodeProcessor
 * @package Phonedotcom\SmsVerification
 */
class CodeProcessor
{

    /**
     * Prefix for cache keys
     * @var string
     */
    private $cachePrefix = '';

    /**
     * Code length
     * @var int
     */
    private $codeLength = 4;

    /**
     * Lifetime of codes in minutes
     * @var int
     */
    private $minutesLifetime = 10;

    /**
     * Singleton instance
     * @var
     */
    private static $instance;

    /**
     * Singleton
     * @return CodeProcessor
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * CodeProcessor constructor
     * @throws ConfigException
     */
    private function __construct()
    {
        $this->cachePrefix = (string) config('sms-verification.cache-prefix', $this->cachePrefix);
        $this->codeLength = config('sms-verification.code-length', $this->codeLength);
        if (empty($this->codeLength) || !is_int($this->codeLength)) {
            throw new ConfigException('Incorrect code length was specified in config/sms-verification.php');
        }
        $this->minutesLifetime = config('sms-verification.code-lifetime', $this->minutesLifetime);
        if (empty($this->minutesLifetime) || !is_int($this->minutesLifetime)) {
            throw new ConfigException('Incorrect code lifetime was specified in config/sms-verification.php');
        }
    }

    /**
     * Generate code, save it in Cache, return it
     * TODO Add Cache tags support
     * @param string $phoneNumber
     * @return string
     * @throws GenerateCodeException
     */
    public function generateCode($phoneNumber)
    {
        try {
            $randomFunction = 'random_int'; // This function is better, but it was added in PHP7
            if (!function_exists($randomFunction)){
                $randomFunction = 'mt_rand';
            }
            $code = $randomFunction(pow(10, $this->codeLength - 1), pow(10, $this->codeLength) - 1);
            Cache::put($this->cachePrefix . $code, $this->trimPhoneNumber($phoneNumber), $this->minutesLifetime);
        } catch (\Exception $e){
            throw new GenerateCodeException('Code generation failed', 0, $e);
        }
        return $code;
    }

    /**
     * Check code in Cache
     * @param string $code
     * @param string $phoneNumber
     * @return bool
     * @throws ValidateCodeException
     */
    public function validateCode($code, $phoneNumber)
    {
        try {
            $codeValue = Cache::get($this->cachePrefix . $code);
            if ($codeValue && ($codeValue == $this->trimPhoneNumber($phoneNumber))){
                Cache::forget($this->cachePrefix . $code);
                return true;
            }
        } catch (\Exception $e){
            throw new ValidateCodeException('Code validation failed', 0, $e);
        }
        return false;
    }

    /**
     * @param $phoneNumber
     * @return string
     */
    private function trimPhoneNumber($phoneNumber){
        return trim(ltrim($phoneNumber, '+'));
    }

    /**
     * @return int Seconds
     */
    public function getLifetime(){
        return $this->minutesLifetime * 60;
    }

}
