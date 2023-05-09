{{--
Author      : Jérémie Arcidiacono
Created     : May 2023
Description : The page to display the details of a course

Need to provide :
    - $course

--}}

@php
    /** @var \App\Models\Course $course */
@endphp

@component('layouts.app', ['title' => 'Accueil', 'stickyHeader' => false])

    <!-- Start breadcrumb Area -->
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
                            <li class="rbt-breadcrumb-item active">Détail du cours</li>
                        </ul>
                        <h2 class="title">{{ $course->getTitle() }}</h2>
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
                                {{ $course->getUpdatedAt()->format('m/Y') }}
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb Area -->

    <div class="rbt-course-details-area ptb--60">
        <div class="container">
            <div class="row g-5">

                <div class="col-lg-8">
                    <div class="course-details-content">
                        <div class="rbt-course-feature-box rbt-shadow-box thuumbnail">
                            <img class="w-100" src="{{ url('course.banner', ['courseId' => $course->getId()]) }}"
                                 alt="Card image">
                        </div>

                        <!-- Start Course Content  -->
                        <div class="course-content rbt-shadow-box coursecontent-wrapper mt--30" id="coursecontent">
                            <div class="rbt-course-feature-inner">
                                <div class="section-title">
                                    <h4 class="rbt-title-style-3">Course Content</h4>
                                </div>
                                <div class="rbt-accordion-style rbt-accordion-02 accordion">
                                    <div class="accordion" id="accordionExampleb2">

                                        <div class="accordion-item card">
                                            <h2 class="accordion-header card-header" id="headingTwo1">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseTwo1" aria-expanded="true"
                                                        aria-controls="collapseTwo1">
                                                    Intro to Course and Histudy <span class="rbt-badge-5 ml--10">1hr 30min</span>
                                                </button>
                                            </h2>
                                            <div id="collapseTwo1" class="accordion-collapse collapse show"
                                                 aria-labelledby="headingTwo1" data-bs-parent="#accordionExampleb2">
                                                <div class="accordion-body card-body pr--0">
                                                    <ul class="rbt-course-main-content liststyle">
                                                        <li>
                                                            <a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-play-circle"></i> <span
                                                                            class="text">Course Intro</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="min-lable">30 min</span>
                                                                    <span class="rbt-badge variation-03 bg-primary-opacity"><i
                                                                                class="feather-eye"></i> Preview</span>
                                                                </div>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-play-circle"></i> <span
                                                                            class="text">Watch Before Start</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="min-lable">0.5 min</span>
                                                                    <span class="rbt-badge variation-03 bg-primary-opacity"><i
                                                                                class="feather-eye"></i> Preview</span>
                                                                </div>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-file-text"></i> <span
                                                                            class="text">Read Before You Start</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item card">
                                            <h2 class="accordion-header card-header" id="headingTwo2">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo2"
                                                        aria-expanded="false" aria-controls="collapseTwo2">
                                                    Course Fundamentals <span
                                                            class="rbt-badge-5 ml--10">2hr 30min</span>
                                                </button>
                                            </h2>
                                            <div id="collapseTwo2" class="accordion-collapse collapse"
                                                 aria-labelledby="headingTwo2" data-bs-parent="#accordionExampleb2">
                                                <div class="accordion-body card-body pr--0">
                                                    <ul class="rbt-course-main-content liststyle">
                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-play-circle"></i> <span
                                                                            class="text">Course Intro</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>
                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-file-text"></i> <span
                                                                            class="text">Read Before You Start</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>

                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-play-circle"></i> <span
                                                                            class="text">Why You Should Not Go To
                                                                        Education.</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>

                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-file-text"></i> <span
                                                                            class="text">Read Before You Start</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>


                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-play-circle"></i> <span
                                                                            class="text">Ten Factors That Affect Education's
                                                                        Longevity.</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>

                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-file-text"></i> <span
                                                                            class="text">Read Before You Start</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item card">
                                            <h2 class="accordion-header card-header" id="headingTwo3">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo3"
                                                        aria-expanded="false" aria-controls="collapseTwo3">
                                                    You can develop skill and setup <span class="rbt-badge-5 ml--10">1hr 50min</span>
                                                </button>
                                            </h2>
                                            <div id="collapseTwo3" class="accordion-collapse collapse"
                                                 aria-labelledby="headingTwo3" data-bs-parent="#accordionExampleb2">
                                                <div class="accordion-body card-body pr--0">
                                                    <ul class="rbt-course-main-content liststyle">
                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-play-circle"></i> <span
                                                                            class="text">Course Intro</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>
                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-file-text"></i> <span
                                                                            class="text">Read Before You Start</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>

                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-play-circle"></i> <span
                                                                            class="text">Why You Should Not Go To
                                                                        Education.</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>

                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-file-text"></i> <span
                                                                            class="text">Read Before You Start</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>


                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-play-circle"></i> <span
                                                                            class="text">Ten Factors That Affect Education's
                                                                        Longevity.</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>

                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-file-text"></i> <span
                                                                            class="text">Read Before You Start</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item card">
                                            <h2 class="accordion-header card-header" id="headingTwo4">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo4"
                                                        aria-expanded="false" aria-controls="collapseTwo4">
                                                    15 Things To Know About Education? <span class="rbt-badge-5 ml--10">2hr 60min</span>
                                                </button>
                                            </h2>
                                            <div id="collapseTwo4" class="accordion-collapse collapse"
                                                 aria-labelledby="headingTwo4" data-bs-parent="#accordionExampleb2">
                                                <div class="accordion-body card-body pr--0">
                                                    <ul class="rbt-course-main-content liststyle">
                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-play-circle"></i> <span
                                                                            class="text">Course Intro</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>
                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-file-text"></i> <span
                                                                            class="text">Read Before You Start</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>

                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-play-circle"></i> <span
                                                                            class="text">Why You Should Not Go To
                                                                        Education.</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>

                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-file-text"></i> <span
                                                                            class="text">Read Before You Start</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>


                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-play-circle"></i> <span
                                                                            class="text">Ten Factors That Affect Education's
                                                                        Longevity.</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>

                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-file-text"></i> <span
                                                                            class="text">Read Before You Start</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item card">
                                            <h2 class="accordion-header card-header" id="headingTwo5">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo5"
                                                        aria-expanded="false" aria-controls="collapseTwo5">
                                                    Course Description <span class="rbt-badge-5 ml--10">2hr 20min</span>
                                                </button>
                                            </h2>
                                            <div id="collapseTwo5" class="accordion-collapse collapse"
                                                 aria-labelledby="headingTwo5" data-bs-parent="#accordionExampleb2">
                                                <div class="accordion-body card-body pr--0">
                                                    <ul class="rbt-course-main-content liststyle">
                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-play-circle"></i> <span
                                                                            class="text">Course Intro</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>
                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-file-text"></i> <span
                                                                            class="text">Read Before You Start</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>

                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-play-circle"></i> <span
                                                                            class="text">Why You Should Not Go To
                                                                        Education.</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>

                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-file-text"></i> <span
                                                                            class="text">Read Before You Start</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>


                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-play-circle"></i> <span
                                                                            class="text">Ten Factors That Affect Education's
                                                                        Longevity.</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>

                                                        <li><a href="lesson.html">
                                                                <div class="course-content-left">
                                                                    <i class="feather-file-text"></i> <span
                                                                            class="text">Read Before You Start</span>
                                                                </div>
                                                                <div class="course-content-right">
                                                                    <span class="course-lock"><i
                                                                                class="feather-lock"></i></span>
                                                                </div>
                                                            </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Course Content  -->
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="course-sidebar sticky-top rbt-shadow-box course-sidebar-top rbt-gradient-border">
                        <div class="inner">
                            <div class="content-item-content">
                                <div class="add-to-card-button mt--15">
                                    <a class="rbt-btn btn-gradient icon-hover w-100 d-block text-center" href="#">
                                        <span class="btn-text">S'inscrire</span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    </a>
                                </div>

                                @if(!\App\App::$auth->check())
                                    <span class="subtitle"><i class="feather-info"></i> Créez vous un compte
                                    pour vous inscrire à ce cours</span>
                                @endif

                                <div class="rbt-widget-details has-show-more mt--10">
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
                                                    class="rbt-feature-value rbt-badge-5">{{ $totalDuration }}
                                                secondes</span>
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
@endcomponent
