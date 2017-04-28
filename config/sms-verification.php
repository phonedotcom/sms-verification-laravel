<?php

return [

    /*
     * Register on Phone.com and fill this config with data you've got there:
     */
    'phone-com-phone-number' => '', // Phone number you want to use for SMS sending (+1XXXXXXXXXX)
    'phone-com-access-token' => '', // Access Token for Phone.com API
    'phone-com-account-id' => '', // Phone.com account ID (number)

    /*
     * Change this configuration if you need
     */
    'message-translation-code' => null, // You can use translation string for message with :code instead of code itself.
    'cache-prefix' => 'PSVL:', // Prefix for Cache
    'code-length' => 6, // Length of verification code
    'code-lifetime' => 10, // Lifetime of verification code (in minutes)

    /*
     * Other configurations
     */

    // API URL
    //'phone-com-api-url' => 'https://api.phone.com/v4',

    // Class should implement Phonedotcom\SmsVerification\SenderInterface
    //'sender-class' => '\Phonedotcom\SmsVerification\Sender'

];