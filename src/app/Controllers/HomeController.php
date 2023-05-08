<?php
declare(strict_types=1);

/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Author      :    Jérémie Arcidiacono
 * Created     :    May 2023
 * Description :    This class is a controller used to render the home page.
 ** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace App\Controllers;

use App\App;
use App\Services\CourseService;

/**
 * Render the home page.
 */
class HomeController
{

    /**
     * Render the home page.
     * @return string
     * @throws \Exception
     */
    public function index(): string
    {
        $topCourses = CourseService::FindRandom(3);

        $randomCourses = CourseService::FindRandom(App::$config->get('ux.home.nbRandomCourseDisplayed'));
        return App::$templateEngine->run(
            'home',
            [
                'topCourses' => $topCourses,
                'randomCourses' => $randomCourses
            ]
        );
    }
}
