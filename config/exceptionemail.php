<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sends an email on Exception or be silent
    |--------------------------------------------------------------------------
    |
    | Should we email error traces?
    |
    */
    'silent' => env('IS_EXCEPTION_EMAIL_SILENT', false),

    /*
    |--------------------------------------------------------------------------
    | Sends with details logs or not
    |--------------------------------------------------------------------------
    |
    | Should we email error traces with all logs then please set it to false?
    |
    */
    'notify_only' => env('IS_EXCEPTION_EMAIL_NOTIFY', true),

    /*
    |--------------------------------------------------------------------------
    | A list of the exception types that should be captured.
    |--------------------------------------------------------------------------
    |
    | For which exception class emails should be sent?
    |
    | You can also use '*' in the array which will in turn captures every
    | exception.
    |
    */
    'capture' => [
        \Symfony\Component\ErrorHandler\Error\FatalError::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | A list of the exception types that should be captured.
    |--------------------------------------------------------------------------
    |
    | For which exception class emails should not be sent?
    |
    */

    'ignored_exception' => [
        // Webmonks\ExceptionEmail\Exceptions\DummyException::class,
    ],
    /*
    |--------------------------------------------------------------------------
    | Error email recipients
    |--------------------------------------------------------------------------
    |
    | Email stack traces to these addresses.
    |
    */

    'to' => [
        'hello@example.com',
    ],

    /*
    |--------------------------------------------------------------------------
    | Ignore Crawler Bots
    |--------------------------------------------------------------------------
    |
    | For which bots should we NOT send error emails?
    |
    */
    'ignored_bots' => [
        'yandexbot',// YandexBot
        'googlebot',// Googlebot
        'bingbot',// Microsoft Bingbot
        'slurp',// Yahoo! Slurp
        'ia_archiver',// Alexa
    ],
];
