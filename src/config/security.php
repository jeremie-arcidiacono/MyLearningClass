<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Date        :    April 2023
 * Description :    This config file defines some config values related to security of the application.
 ** * * * * * * * * * * * * * * * * * * * * * * */

return [
    /*
     *---------------------------------------------------------------
     * Recaptcha
     *---------------------------------------------------------------
     * This section defines the config values related to the recaptcha service.
     * You must specify if the captcha is enabled or not, and the public and private keys.
     * The verify_url is the url that will be used to verify the captcha with the Google API.
     */
    'recaptcha' => [
        'enabled' => filter_var($_ENV['RECAPTCHA_ENABLED'] ?? false, FILTER_VALIDATE_BOOLEAN),
        'site_key' => $_ENV['RECAPTCHA_PUBLIC'],
        'secret_key' => $_ENV['RECAPTCHA_PRIVATE'],
        'verify_url' => 'https://www.google.com/recaptcha/api/siteverify',
    ],

    /*
     *---------------------------------------------------------------
     * CSRF
     *---------------------------------------------------------------
     * This section defines the config values related to the anti-CSRF protection with tokens.
     * You must specify if the CSRF protection is enabled or not, the name of the token and its length.
     * The length should be at least 32.
     */
    'csrf' => [
        'enabled' => true,
        'token_name' => 'csrf_token',
        'token_length' => 32,
    ],
];
