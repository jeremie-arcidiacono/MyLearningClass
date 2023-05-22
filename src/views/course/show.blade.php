{{--
Author      : Jérémie Arcidiacono
Created     : May 2023
Description : The page to display the details of a course

Need to provide :
    - $course

--}}

@php
    /**
    * @var \App\Models\Course $course
    * @var \App\Models\Chapter[] $chapters The chapters of the course (ordered by position)
    * @var \App\Models\Chapter $chapter
    */
@endphp

@component('layouts.app', ['title' => 'Detail du cours', 'stickyHeader' => false])

    {{-- Start breadcrumb Area --}}
    <div class="rbt-breadcrumb-default rbt-breadcrumb-style-3">
        <div class="breadcrumb-inner">
            <img src="/assets/images/bg/bg-image-10.jpg" alt="Education Images">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="content text-start">
                        <ul class="page-list">
                            <li class="rbt-breadcrumb-item"><a href="{{ url('home') }}">Accueil</a></li>
                            <li>
                                <div class="icon-right"><i class="feather-chevron-right"></i></div>
                            </li>
                            <li class="rbt-breadcrumb-item"><a href="{{ url('course.index') }}">Tous les cours</a></li>
                            <li>
                                <div class="icon-right"><i class="feather-chevron-right"></i></div>
                            </li>
                            <li class="rbt-breadcrumb-item active">Détail du cours</li>
                        </ul>@if($auth->check())
                            <div class="rbt-bookmark-btn">
                                @if($auth->getUser()->getBookmarkedCourses()->contains($course))
                                    <form action="{{ url('course.unbookmark', ['courseId' => $course->getId()]) }}"
                                          method="post">
                                        @method('DELETE')
                                        @customCsrf
                                        <input type="hidden" name="redirect">
                                        <a class="rbt-round-btn fs-2" title="Favoris"
                                           href="javascript:void(0)" onclick="$(this).closest('form').submit()">
                                            <i class="fas fa-bookmark"></i>
                                        </a>
                                    </form>
                                @else
                                    <form action="{{ url('course.bookmark', ['courseId' => $course->getId()]) }}"
                                          method="post">
                                        @customCsrf
                                        <input type="hidden" name="redirect">
                                        <a class="rbt-round-btn fs-2" title="Favoris"
                                           href="javascript:void(0)" onclick="$(this).closest('form').submit()">
                                            <i class="far fa-bookmark"></i>
                                        </a>
                                    </form>
                                @endif
                            </div>
                        @endif
                        <h2 class="title">
                            {{ $course->getTitle() }}</h2>
                        <p class="description">{{ $course->getDescription() }}</p>

                        <div class="d-flex align-items-center mb--20 flex-wrap rbt-course-details-feature">
                            <div class="feature-sin total-student">
                                <span>{{$course->getEnrollments()->count()}} étudiants inscrits</span>
                            </div>

                        </div>

                        <div class="rbt-author-meta mb--20">
                            <div class="rbt-author-info">
                                Par {{ $course->getOwner()->getFirstname() . ' ' . $course->getOwner()->getLastname() }}
                            </div>
                        </div>

                        <ul class="rbt-meta">
                            <li><i class="feather-calendar"></i>Dernière mise à jour
                                {{ $course->getUpdatedAt()->format('d/m/Y') }}
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- End Breadcrumb Area --}}

    <div class="rbt-course-details-area ptb--60">
        <div class="container">
            <div class="row g-5">

                <div class="col-lg-8">
                    <div class="course-details-content">
                        <div class="rbt-course-feature-box rbt-shadow-box thuumbnail">
                            <img class="w-100" src="{{ url('course.banner', ['courseId' => $course->getId()]) }}"
                                 alt="Card image">
                        </div>

                        {{-- Start Course Content  --}}
                        <div class="course-content rbt-shadow-box coursecontent-wrapper mt--30" id="coursecontent">
                            <div class="rbt-course-feature-inner">
                                <div class="section-title">
                                    <h4 class="rbt-title-style-3">Chapitres du cours</h4>
                                </div>

                                <ul class="rbt-course-main-content liststyle">
                                    @foreach($course->getChapters() as $chapter)
                                        <li>
                                            <a href="javascript:void(0)">
                                                <div class="course-content-left">
                                                    @if($chapter->getVideo() != null)
                                                        <i class="feather-play-circle"></i>
                                                    @endif
                                                    @if($chapter->getRessource() != null)
                                                        <i class="feather-file-text"></i>
                                                    @endif

                                                    <span class="text">{{ $chapter->getTitle() }}</span>
                                                </div>

                                                @if($chapter->getVideo() != null)
                                                    <div class="course-content-right">
                                                        <span class="min-lable">
                                                            {{ gmdate('i', $chapter->getVideo()->getDuration()) }} minutes
                                                        </span>
                                                    </div>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        {{-- End Course Content  --}}
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="course-sidebar sticky-top rbt-shadow-box course-sidebar-top rbt-gradient-border">
                        <div class="inner">
                            <div class="content-item-content">
                                <div class="add-to-card-button mt--15">
                                    @if($auth->check() && \App\Services\CourseEnrollmentService::isEnrolled($auth->getUser(), $course))

                                        <a class="rbt-btn btn-gradient icon-hover w-100 d-block text-center"
                                           href="{{ url('chapter.show', ['courseId' => $course->getId()]) }}">
                                            <span class="btn-text">Commencer à étudier</span>
                                            <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        </a>

                                        <form action="{{ url('course.enrollment.destroy', ['courseId' => $course->getId()]) }}"
                                              method="POST">
                                            @method('DELETE')
                                            @customCsrf
                                            <a class="rbt-btn-link color-primary"
                                               href="javascript:void(0)" onclick="$(this).closest('form').submit()">
                                                Se désinscrire
                                            </a>
                                        </form>
                                    @else
                                        <form action="{{ url('course.enrollment.store', ['courseId' => $course->getId()])
                                                 }}"
                                              method="POST">
                                            @customCsrf
                                            <a class="rbt-btn btn-gradient icon-hover w-100 d-block text-center"
                                               href="javascript:void(0)"
                                               onclick="$(this).closest('form').submit()">
                                                <span class="btn-text">S'inscrire</span>
                                                <span class="btn-icon"><i
                                                            class="feather-arrow-right"></i></span>
                                            </a>
                                        </form>
                                    @endif
                                </div>

                                @if(!\App\App::$auth->check())
                                    <span class="subtitle"><i class="feather-info"></i> Créez vous un compte
                                    pour vous inscrire à ce cours</span>
                                @endif

                                <div class="rbt-widget-details mt--10">
                                    <ul class="has-show-more-inner-content rbt-course-details-list-wrapper">
                                        <li><span>Catégorie</span><span class="rbt-feature-value rbt-badge-5">
                                                {{ $course->getCategory()->getLabel() }}</span>
                                        </li>
                                        <li><span>Chapitres</span><span class="rbt-feature-value rbt-badge-5">
                                                {{ $course->getChapters()->count() }}</span>
                                        </li>
                                        @php
                                            $totalDuration = 0;
                                            foreach ($course->getChapters() as $chapter){
                                                $totalDuration += $chapter->getVideo()?->getDuration();
                                            }
                                        @endphp
                                        <li><span>Durée</span><span
                                                    class="rbt-feature-value rbt-badge-5">
                                                {{ gmdate('i', $totalDuration) }} minutes</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rbt-separator-mid">
        <div class="container">
            <hr class="rbt-separator m-0">
        </div>
    </div>

    @pushonce('scripts_bookmark_course_action_button')
        <script>
            // Add the redirection to this page after bookmarking the course
            window.addEventListener('load', function () {
                document.getElementsByName('redirect').forEach(function (input) {
                    input.value = window.location.href;
                });
            });
        </script>
    @endpushonce
@endcomponent
