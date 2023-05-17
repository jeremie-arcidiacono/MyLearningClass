<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class is a controller used to manage the users.
 *                  The difference between this controller and the AuthController is that this one is used by the
 *                  administrators to manage the other users, while the AuthController is used by the users itself to create
 *                  his account, login, logout, etc.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Controllers;

use App\App;
use App\Contracts\ISession;
use App\Enums\Action;
use App\Exceptions\ForbiddenHttpException;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use App\Services\RoleService;
use App\Services\UserService;
use App\Validator;
use Doctrine\ORM\Exception\ORMException;

/**
 * Manage the users.
 */
class UserController
{
    /**
     * Show the users list with pagination and a search bar.
     * @return string
     * @throws ForbiddenHttpException
     */
    public function index(): string
    {
        if (!App::$auth->can(Action::Read, new User())) {
            throw new ForbiddenHttpException("Vous n'avez pas la permission d'accéder à cette page.");
        }

        $filters = getAllInputs();

        $rules = [
            'page' => ['integer', 'min:1'],
            'recherche' => ['string', 'lenmin:1'],
        ];

        $validator = new Validator($filters, $rules);
        if ($validator->isValid()) {
            $search = $filters['recherche'] ?? null;
            $page = isset($filters['page']) ? (int)$filters['page'] : 1;
        }
        else {
            $search = null;
            $page = 1;
        }

        $userPaginator = UserService::FindByWithPagination(
            $page,
            unescape($search),
        );

        $user = $userPaginator->getIterator()->getArrayCopy();

        flashInputsOnly(['recherche']);

        return App::$templateEngine->run('dashboard.user-index', [
            'nbTotalUsers' => $userPaginator->getTotalItems(),
            'users' => $user,
            'currentPage' => $userPaginator->getCurrentPage(),
            'totalPages' => $userPaginator->getTotalPage(),
        ]);
    }

    /**
     * Create a new user.
     * @return string
     * @throws ForbiddenHttpException
     * @throws ORMException
     */
    public function store(): string
    {
        if (!App::$auth->can(Action::Create, new User())) {
            throw new ForbiddenHttpException("Vous n'avez pas la permission de créer un utilisateur.");
        }

        $inputs = getAllInputs();
        $rules = [
            'email' => ['required', 'email', 'lenmax:50', 'unique:' . User::class . ':email'],
            'prenom' => ['required', 'lenmin:2', 'lenmax:30'],
            'nom' => ['required', 'lenmin:2', 'lenmax:30'],
            'motDePasse' => ['required', 'lenmin:8', 'containUpper', 'containLower', 'containNumber', 'containSpecial'],
            'motDePasseConfirmation' => ['required', 'same:motDePasse'],
            'role' => ['required', 'exists:' . Role::class],
        ];

        $validator = new Validator($inputs, $rules, App::$db);

        if (!$validator->isValid()) {
            flashInputsExcept(['motDePasse', 'motDePasseConfirmation']);

            App::$session->setFlash(ISession::ERROR_KEY, $validator->getErrors());
            return App::$templateEngine->run('dashboard.user-create');
        }

        $user = (new User())
            ->setEmail($inputs['email'])
            ->setFirstname($inputs['prenom'])
            ->setLastname($inputs['nom'])
            ->setPassword($inputs['motDePasse'])
            ->setRole(RoleService::Find((int)$inputs['role']));

        if (!UserService::Create($user)) {
            flashInputsExcept(['motDePasse', 'motDePasseConfirmation']);

            App::$session->setFlash(ISession::ERROR_KEY, ['Une erreur est survenue lors de la création de l\'utilisateur.']);
            return App::$templateEngine->run('dashboard.user-create');
        }

        return App::$templateEngine->run('dashboard.user-create');
    }

    /**
     * Return the view to create a new user.
     * @return string
     * @throws ForbiddenHttpException
     */
    public function create(): string
    {
        if (!App::$auth->can(Action::Create, new User())) {
            throw new ForbiddenHttpException("Vous n'avez pas la permission de créer un utilisateur.");
        }

        return App::$templateEngine->run('dashboard.user-create');
    }

    /**
     * Delete a user.
     * The user can't delete himself.
     * The user can't delete a user who has a course in progress or done.
     * The user can't delete a user who has a public course.
     * The user can't delete a user who owns a course with enrolled users.
     * @param User $user
     * @return string
     * @throws ForbiddenHttpException
     */
    public function destroy(User $user): string
    {
        if (!App::$auth->can(Action::Delete, $user)) {
            throw new ForbiddenHttpException("Vous n'avez pas les droits pour supprimer cet utilisateur.");
        }

        if ($user->getId() === App::$auth->getUser()->getId()) {
            App::$session->setFlash(ISession::ERROR_KEY, ['Vous ne pouvez pas supprimer votre propre compte.']);
            return App::$templateEngine->run('dashboard.user-index');
        }

        if (UserService::UserHasCourseInProgressOrDone($user)) {
            App::$session->setFlash(ISession::ERROR_KEY, ['Vous ne pouvez pas supprimer un utilisateur qui a un cours en cours.']);
            return App::$templateEngine->run('dashboard.user-index');
        }

        if (UserService::UserHasPublicCourse($user)) {
            App::$session->setFlash(ISession::ERROR_KEY, ['Vous ne pouvez pas supprimer un utilisateur qui a un cours public.']);
            return App::$templateEngine->run('dashboard.user-index');
        }

        if (UserService::TeacherHasStudent($user)) {
            App::$session->setFlash(ISession::ERROR_KEY, ['Vous ne pouvez pas supprimer un utilisateur qui a créé un cours qui a des étudiants.']);
            return App::$templateEngine->run('dashboard.user-index');
        }

        if (!UserService::Delete($user)) {
            App::$session->setFlash(ISession::ERROR_KEY, ['Une erreur est survenue lors de la suppression de l\'utilisateur.']);
            return App::$templateEngine->run('dashboard.user-index');
        }

        // User was successfully deleted, we can remove all his files.
        $courses = $user->getCreatedCourses();
        /** @var Course $course */
        foreach ($courses as $course) {
            unlink(CourseController::COURSE_BANNER_IMG_PATH . '/' . $course->getBanner()->getFilename());

            /** @var Chapter $chapter */
            foreach ($course->getChapters() as $chapter) {
                if ($chapter->getVideo() !== null) {
                    unlink(ChapterController::CHAPTER_MEDIA_PATH . '/' . $chapter->getVideo()->getFilename());
                }

                if ($chapter->getRessource() !== null) {
                    unlink(ChapterController::CHAPTER_MEDIA_PATH . '/' . $chapter->getRessource()->getFilename());
                }
            }
        }

        // All is good, we can redirect the user.
        redirect(url('user.index'));
    }

}
