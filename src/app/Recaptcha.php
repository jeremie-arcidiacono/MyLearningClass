<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    April 2023
 * Description :    This class is used to verify the recaptcha of the forms.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App;

/**
 * Used to verify the recaptcha of the forms.
 */
readonly class Recaptcha
{
    /**
     * Check if the recaptcha is enabled in the config of the app
     * @return bool
     */
    public static function isEnabled(): bool
    {
        return App::$config->get('security.recaptcha.enabled', false);
    }

    /**
     * Get the site key of the recaptcha
     * @return string
     */
    public static function getSiteKey(): string
    {
        return App::$config->get('security.recaptcha.site_key', '');
    }

    /**
     * Get the secret key of the recaptcha
     * @return string
     */
    public static function getSecretKey(): string
    {
        return App::$config->get('security.recaptcha.secret_key', '');
    }

    /**
     * @param string $responseToken The token of the recaptcha response from the client
     */
    public function __construct(private string $responseToken, private Config $config)
    {
    }

    /**
     * Verify if the recaptcha is valid.
     * It uses the Google API to verify the token
     * @return bool True if the recaptcha is disabled in app config or if the user has filled correctly the captcha
     */
    public function verify(): bool
    {
        if (!self::isEnabled()) {
            return true;
        }

        $url = $this->config->get('security.recaptcha.verify_url', 'https://www.google.com/recaptcha/api/siteverify');
        $data = [
            'secret' => self::getSecretKey(),
            'response' => $this->responseToken,
        ];
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r",
                'method' => 'POST',
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        $result = json_decode($result, true);

        return $result['success'] ?? false;
    }
}
