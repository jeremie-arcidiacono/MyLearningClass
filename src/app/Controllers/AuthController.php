<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class is a controller used to manage the authentication of the users.
 *                  It is used to display the login and register views and to handle the login and register actions, as well as the logout action.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Controllers;

use App\App;
use App\Contracts\ISession;
use App\Controllers\Traits\UseRecaptcha;
use App\Models\Role;
use App\Models\User;
use App\Services\RoleService;
use App\Validator;
use Doctrine\ORM\Exception\ORMException;
use Pecee\SimpleRouter\RouterUtils;

/**
 * Manage the authentication of the users.
 */
class AuthController
{
    use RouterUtils;
    use UseRecaptcha;

    /**
     * Render the login view.
     * @return string
     * @throws \Exception
     */
    public function login_view(): string
    {
        return App::$templateEngine->run('auth.login');
    }

    /**
     * Handle the login form submission.
     * If the login is successful, redirect to the home page. Otherwise, redirect to the login page with errors.
     * @return string
     * @throws ORMException
     */
    public function login(): string
    {
        $inputs = getAllInputs();
        $rules = [
            'email' => ['required', 'email'],
            'motDePasse' => ['required'],
        ];

        $validator = new Validator($inputs, $rules);

        if (!$validator->isValid()) {
            // Invalid inputs
            App::$session->setFlash(ISession::ERROR_KEY, $validator->getErrors());
        }
        elseif (!$this->verifyRecaptcha()) {
            // Invalid captcha
            App::$session->setFlash(ISession::ERROR_KEY, ['Le captcha est invalide, veuillez réessayer']);
        }
        elseif (App::$auth->attemptLogin($inputs['email'], $inputs['motDePasse'])) {
            redirect(url('home')); // Success
        }
        else {
            // Invalid credentials
            App::$session->setFlash(ISession::ERROR_KEY, ['Identifiants incorrects']);
        }

        flashInputsExcept(['password']);

        return App::$templateEngine->run('auth.login');
    }

    /**
     * Render the register view.
     * @return string
     * @throws \Exception
     */
    public function register_view(): string
    {
        return App::$templateEngine->run('auth.register');
    }

    /**
     * Handle the register form submission.
     * If the register is successful, redirect to the home page. Otherwise, render the register view with the errors.
     * @return string
     * @throws \Exception
     */
    public function register(): string
    {
        $inputs = getAllInputs();
        $rules = [
            'email' => ['required', 'email', 'lenmax:50', 'unique:' . User::class . ':email'],
            'prenom' => ['required', 'lenmin:2', 'lenmax:30'],
            'nom' => ['required', 'lenmin:2', 'lenmax:30'],
            'motDePasse' => ['required', 'lenmin:8', 'containUpper', 'containLower', 'containNumber', 'containSpecial'],
            'motDePasseConfirmation' => ['required', 'same:motDePasse'],
            'typeDeCompte' => ['required', 'exists:'. Role::class],
        ];

        $validator = new Validator($inputs, $rules, App::$db);

        if (!$validator->isValid()) {
            App::$session->setFlash(ISession::ERROR_KEY, $validator->getErrors());
        }
        elseif ($inputs['typeDeCompte'] !== '1' && $inputs['typeDeCompte'] !== '2') {
            App::$session->setFlash(ISession::ERROR_KEY, ['Le role est invalide, veuillez réessayer']);
        }
        elseif (!$this->verifyRecaptcha()) {
            App::$session->setFlash(ISession::ERROR_KEY, ['Le captcha est invalide, veuillez réessayer']);
        }
        elseif (App::$auth->register($inputs['prenom'], $inputs['nom'], $inputs['email'], $inputs['motDePasse'], RoleService::Find((int)$inputs['typeDeCompte']) )) {
            redirect(url('home'), 302);
        }
        else {
            App::$session->setFlash(ISession::ERROR_KEY, "Une erreur s'est produite lors de l'inscription");
        }

        flashInputsExcept(['motDePasse', 'motDePasseConfirmation']);

        return App::$templateEngine->run('auth.register');
    }

    /**
     * Handle the logout action and redirect to the home page.
     * @return never
     */
    public function destroy(): never
    {
        App::$auth->logout();
        redirect(url('home'), 302);
    }

}
