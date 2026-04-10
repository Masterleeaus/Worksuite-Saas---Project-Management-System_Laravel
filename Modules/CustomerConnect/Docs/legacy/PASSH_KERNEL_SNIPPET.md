Add these aliases to your app HTTP Kernel (app/Http/Kernel.php) or your module route provider if it supports aliases:

protected $routeMiddleware = [
  // ...
  'customerconnect.twilio.sig' => \Modules\CustomerConnect\Http\Middleware\VerifyTwilioSignature::class,
  'customerconnect.vonage.sig' => \Modules\CustomerConnect\Http\Middleware\VerifyVonageSignature::class,
];

