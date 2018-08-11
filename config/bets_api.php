<?php

return [
    'token' => env('BETS_API_TOKEN'),
    'endpoint' => env('BETS_API_ENDPOINT', 'https://api.b365api.com/v2/'),
    'failed_calls' => [
        'retries' => env('BETS_API_NUMBER_OF_FAILED_CALL_RETRIES', 5),
        'seconds_to_sleep' => env('BETS_API_SECONDS_TO_SLEEP_AFTER_FAILED_CALL', 1),
    ],
];