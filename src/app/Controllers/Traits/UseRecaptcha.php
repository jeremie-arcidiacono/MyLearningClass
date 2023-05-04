<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    April 2023
 * Description :    This trait is used by controllers to verify if the user has filled the captcha.
 *                  It provides a method to verify the recaptcha.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Controllers\Traits;

use App\App;
use App\Contracts\ISession;
use App\Recaptcha;
use App\Validator;

/**
 * Used by controllers to verify if the user has filled the captcha
 */
trait UseRecaptcha
{
    /**
     * @var string The key of the recaptcha token in the request inputs
     */
    private const RECAPTCHA_TOKEN_KEY = 'g-recaptcha-response';

    /**
     * Verify if the recaptcha is enabled and if the user has filled the captcha
     * @return bool True if the recaptcha is disabled or if the user has filled the captcha
     */
    private function verifyRecaptcha(): bool
    {
        if (!Recaptcha::isEnabled()) {
            return true;
        }

        $inputs = getAllInputs();
        $ruleCaptcha[self::RECAPTCHA_TOKEN_KEY] = ['required'];
        $validatorCaptcha = new Validator($inputs, $ruleCaptcha);

        if (!$validatorCaptcha->isValid() || !(new Recaptcha($inputs[self::RECAPTCHA_TOKEN_KEY], App::$config))->verify()) {
            App::$session->setFlash(ISession::ERROR_KEY, ['Le captcha est invalide, veuillez réessayer']);
            return false;
        }

        return true;
    }
}
