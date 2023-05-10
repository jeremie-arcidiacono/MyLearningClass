{{--
Author      : Jérémie Arcidiacono
Created     : May 2023
Description : The page to display the list of courses the user is enrolled in.
--}}

@php
    /**
    * @var \App\Models\Course[] $enrolledCourses The courses the user is enrolled in (excludes the courses the user has completed)
    * @var \App\Models\Course[] $completedCourses The courses the user has completed
    */
@endphp

@component('layouts.dashboard')
    <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
        <div class="content">

            <div class="section-title">
                <h4 class="rbt-title-style-3">Vos cours suivis</h4>
            </div>

            <div class="advance-tab-button mb--30">
                <ul class="nav nav-tabs tab-button-style-2 justify-content-start" id="myTab-4" role="tablist">
                    <li role="presentation">
                        <a href="#" class="tab-button active" id="home-tab-4" data-bs-toggle="tab"
                           data-bs-target="#currentCourse" role="tab" aria-controls="currentCourse"
                           aria-selected="true">
                            <span class="title">Cours à faire</span>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#" class="tab-button" id="profile-tab-4" data-bs-toggle="tab"
                           data-bs-target="#finishedCourse" role="tab" aria-controls="finishedCourse"
                           aria-selected="false">
                            <span class="title">Cours terminés</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade active show" id="currentCourse" role="tabpanel"
                     aria-labelledby="currentCourse">
                    <div class="row g-5">
                        @foreach($enrolledCourses as $course)
                            <div class="col-lg-4 col-md-6 col-12">
                                @component('components.course-card-s2',
                                    ['course' => $course,
                                    'showDescription' =>false,
                                    'progressPercentage' => \App\Services\ChapterProgressService::GetProgressPercentage(
                                        $auth->getUser(),
                                        $course
                                    ),])
                                @endcomponent
                            </div>
                        @endforeach
                        @empty($enrolledCourses)
                            <h5 class="text-center">Vous n'avez pas encore de cours à faire ou en cours.</h5>
                        @endempty
                    </div>
                </div>

                <div class="tab-pane fade" id="finishedCourse" role="tabpanel" aria-labelledby="finishedCourse">
                    <div class="row g-5">
                        @foreach($completedCourses as $course)
                            <div class="col-lg-4 col-md-6 col-12">
                                @php$progressPercentage = \App\Services\ChapterProgressService::GetProgressPercentage(
                                    $auth->getUser(),
                                    $course
                                )
                                @endphp
                                @component('components.course-card-s2',
                                    ['course' => $course,
                                    'showDescription' =>false,
                                    'progressPercentage' => $progressPercentage,])
                                @endcomponent
                            </div>
                        @endforeach
                        @empty($completedCourses)
                            <h5 class="text-center">Vous n'avez pas encore de cours terminés.</h5>
                        @endempty
                    </div>
                </div>
            </div>

        </div>
    </div>
@endcomponent

