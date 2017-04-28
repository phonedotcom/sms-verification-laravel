Once installed you need to register the service provider. Open `config/app.php` and find the `providers` key.

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