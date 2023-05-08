<?php

declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    January 2023
 * Description :    This class is used to manage the authentication of the users.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App;

use App\Contracts\IModel;
use App\Contracts\ISession;
use App\Enums\Action;
use App\Models\Role;
use App\Models\Traits\HasAnOwner;
use App\Models\User;
use App\Services\RoleService;
use App\Services\UserService;

/**
 * Stateful authentication class that manages the authentication of the users.
 * It uses the session to store the authenticated user.
 */
class Auth
{
    /**
     * Key used to store the user id in the session.
     */
    private const SESSION_USER_ID_KEY = 'authenticateUserId';

    private ?User $user = null;

    /**
     * @param ISession $session The session to use to store the authenticated user.
     */
    public function __construct(private readonly ISession $session)
    {
    }

    /**
     * Return true if a user is authenticated, false otherwise.
     * @return bool
     */
    public function check(): bool
    {
        return $this->getUser() !== null;
    }

    /**
     * Return the authenticated user or null if no user is authenticated.
     * @return User|null
     */
    public function getUser(): ?User
    {
        // If the user is already set, return it
        if ($this->user !== null) {
            return $this->user;
        }

        // If the user is not set, try to get it from the session
        if ($this->session->has(self::SESSION_USER_ID_KEY)) {
            $this->user = UserService::Find($this->session->get(self::SESSION_USER_ID_KEY));
            return $this->user;
        }

        // No user found
        return null;
    }

    /**
     * Try to authenticate the user with the given credentials.
     * If the authentication is successful, the user is now considered as authenticated and the method returns true.
     * Returns false otherwise.
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function attemptLogin(string $email, string $password): bool
    {
        $user = UserService::FindByEmail($email);

        if ($user === null) { // User not found
            return false;
        }

        if (!$this->checkCredentials($user, $password)) { // Password is incorrect
            return false;
        }

        $this->login($user);

        return true;
    }

    /**
     * Return true if the given password match the password of the given user.
     * @param User $user
     * @param string $password The password to check
     * @return bool
     */
    public function checkCredentials(User $user, string $password): bool
    {
        return password_verify($password, $user->getPassword());
    }

    /**
     * Authenticate the given user.
     * @param User $user
     * @return void
     */
    private function login(User $user): void
    {
        $this->user = $user;

        $this->session->regenerate();
        $this->session->set(self::SESSION_USER_ID_KEY, $user->getId());
    }

    /**
     * Logout the user.
     * @return void
     */
    public function logout(): void
    {
        $this->session->remove(self::SESSION_USER_ID_KEY);
        $this->session->regenerate();
        $this->user = null;
    }

    /**
     * Register a new user with the given credentials (store it in the database).
     * If the registration is successful, the user is now considered as authenticated and the method returns true.
     * Returns false otherwise.
     * @param string $firstname
     * @param string $lastname
     * @param string $email The email of the user (will be checked for uniqueness)
     * @param string $password The password of the user (will be hashed)
     * @param Role $role The role of the user
     * @return bool|null
     */
    public function register(string $firstname, string $lastname, string $email, string $password, Role $role): ?bool
    {
        $user = UserService::FindByEmail($email);

        // If the email is already used, abort the registration
        if ($user !== null) {
            return false;
        }

        $user = new User();
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRole($role);

        if (!UserService::Create($user)) {
            return false;
        }

        $this->login($user);

        return true;
    }


    /**
     * Check if the user can do an action on a ressource
     * @param Action $action    The action to check (e.g. 'update')
     * @param IModel $ressource The entity to check (e.g. a Post)
     * @param User $user        The user to check
     * @return bool True if the user has the permission, false otherwise
     * @throws \Exception If the action is not valid
     */
    public static function userCan(Action $action, IModel $ressource, User $user): bool
    {
        $ressourceClass = get_class($ressource);

        if ($action === Action::Read || $action === Action::Update || $action === Action::Delete) {
            // Check if the ressource can be owned by a user
            if (in_array(HasAnOwner::class, class_uses($ressource))) {
                /** @var HasAnOwner $ressource */

                // Check if the user has the permission for every ressource
                if (self::userHasPerm($action->value . '_any', $ressourceClass, $user) || self::userHasPerm(
                        $action->value,
                        $ressourceClass,
                        $user
                    )) {
                    return true;
                }
                // Check if the user has the permission for his own ressource
                elseif (self::userHasPerm($action->value . '_own', $ressourceClass, $user)) {
                    return $ressource->getOwner() === $user;
                }
            }
            else { //We don't have to check for specific permissions like "only owner can delete his own ressource"
                return self::userHasPerm($action->value, $ressourceClass, $user) || self::userHasPerm(
                        $action->value . '_any',
                        $ressourceClass,
                        $user
                    );
            }
        }
        elseif ($action === Action::Create) {
            // Check if the user has the permission to create ressource
            return self::userHasPerm($action->value, $ressourceClass, $user);
        }
        else {
            throw new \Exception("L'action n'est pas valide.");
        }
        return false;
    }

    /**
     * Check if the user got the permission of a specific action on a type of ressource
     * @param string $action         The action to check (ex: 'create'). Must be the exact value which is in the database.
     * @param string $ressourceClass The ressource to check (ex: 'App\Model\User')
     * @param User $user             The user to check
     * @return bool True if the user has the permission, false otherwise
     */
    public static function userHasPerm(string $action, string $ressourceClass, User $user): bool
    {
        $userPerms = $user->getRole()->getPermissions();

        $action = strtolower($action);

        $ressourceClass = strtolower($ressourceClass);
        $ressourceClassName = str_replace('app\models\\', '', $ressourceClass);

        foreach ($userPerms as $userPerm) {
            if (strtolower($userPerm->getAction()) === $action && strtolower($userPerm->getRessource()) === $ressourceClassName) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if the current logged user got the permission of a specific action on a type of ressource
     * @param string $action         The action to check (e.g. 'create')
     * @param string $ressourceClass The ressource to check (e.g. 'App\Model\User')
     * @return bool True if the user has the permission, false otherwise
     * @throws \Exception If the action is not valid
     */
    public function hasPerm(string $action, string $ressourceClass): bool
    {
        $user = $this->getUser();
        if ($user === null) {
            throw new \Exception('L\'utilisateur n\'est pas authentifié, vous ne pouvez pas vérifier ses permissions.');
        }

        return self::userHasPerm($action, $ressourceClass, $user);
    }

    /**
     * Check if the current logged user can do an action on a ressource
     * @param Action $perm      The action to check (e.g. 'update')
     * @param IModel $ressource The entity to check (e.g. a Post)
     * @return bool True if the user has the permission, false otherwise
     * @throws \Exception If the action is not valid
     */
    public function can(Action $perm, IModel $ressource): bool
    {
        $user = $this->getUser();
        if ($user === null) {
            throw new \Exception('L\'utilisateur n\'est pas authentifié, vous ne pouvez pas vérifier ses permissions.');
        }

        return self::userCan($perm, $ressource, $user);
    }
}
