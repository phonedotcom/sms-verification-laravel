![alt text](https://avatars0.githubusercontent.com/u/13040900?v=3&s=100)

This library contains a set of two simple server endpoints for doing phone number verification via SMS.

SMS is done via phone.com API and in fact this code is intended to demonstrate real world use-case for using the phone.com API. All available Phone.com API's are defined at https://apidocs.phone.com.

Server is written in PHP using the Laravel framework.


Once this library is installed you need to register the service provider. Open `config/app.php` and find the `providers` key.

```
'providers' => [
    ...
    \Phonedotcom\SmsVerification\SmsVerificationProvider::class,
    ...
]
```

Run the command:

```
php artisan vendor:publish --provider="Phonedotcom\SmsVerification\SmsVerificationProvider" --tag=config
```

Add SMS Verification endpoints to your routing file:

```
\Phonedotcom\SmsVerification\SmsVerificationProvider::registerRoutes($router);
```

The process for SMS verification is as follow:

```
1.  Use the Post /sms-verification to send the code to a mobile device
2.  Use the Get /sms-verification/{code}/{mobilePhoneNumber} to verify the code
```

For example, if an app wants to send an authorization code to a cell phone 855-123-8765

```
1.  Send a Post /sms-verification API to URL https://api.example.com/sms-verification

         with JSON body { "phone_number" : "+18551238765" }

2.  The API returns {"success":true,"description":"OK"} if the code is sent

3.  The cell phone will receive a 6-digit code (for example: 782025)

4.  In order to verify the code, send a Get /sms-verification API to

        https://api.example.com/sms-verification/782025/+18551238765

5.  The API returns:

        On success: {"success":true,"description":"OK"}
        On failure   : {"success":false,"description":"Wrong code"}
```
NOTE:

```
1.  The authorization code sent is only valid for 10 minutes
2.  The code can be verified only once.  After the first success, it will be invalidated
```
