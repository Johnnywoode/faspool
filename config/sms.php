<?php

return [
    "providers" => [
        "sms-pool" => [
            "api_key" => env("SMSPOOL_API_KEY"),
            "api_url" => env("SMSPOOL_API_URL", "https://api.smspool.net/"),
            "adapter" => "App\Modules\Sms\Adapters\SmsPoolAdapter"
        ]
    ]
];