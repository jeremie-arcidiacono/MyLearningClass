{{--
Author      : Jérémie Arcidiacono
Created     : May 2023
Description : The home page of the application

Need to provide :
    - $topCourses : the top courses to display in the carousel
    - $randomCourses : the random courses to display in the page

--}}

@component('layouts.app', ['title' => 'Accueil'])
    <!-- Start Banner Area -->
    <div class="rbt-banner-area rbt-banner-1">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 pb--120 pt--70">
                    <div class="content">
                        <div class="inner">
                            <div class="rbt-new-badge rbt-new-badge-one">
                                <span class="rbt-new-badge-icon">🏆</span> Le leader de l'apprentissage en ligne
                            </div>

                            <h1 class="title">
                                La meilleure platforme <br> pour
                                <span class="header-caption">
                                            <span class="cd-headline clip is-full-width">
                                                <span class="cd-words-wrapper" style="width: 157px;">
                                                    <b class="theme-gradient is-visible">apprendre.</b>
                                                    <b class="theme-gradient is-hidden">évoluer.</b>
                                                    <b class="theme-gradient is-hidden">se former.</b>
                                                </span>
                                        </span>
                                        </span>
                            </h1>
                            <p class="description">
                                {{ $appName }} est une plateforme d'apprentissage en ligne. Elle propose des cours en
                                ligne
                                <strong>gratuits pour tous</strong>
                            </p>
                            <div class="slider-btn">
                                @if($auth->check())
                                    <a class="rbt-btn btn-gradient hover-icon-reverse" href="{{ url('course.index') }}">
                                        <span class="icon-reverse-wrapper">
                                                <span class="btn-text">Voir les cours</span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        </span>
                                    </a>
                                @else
                                    <a class="rbt-btn btn-gradient hover-icon-reverse"
                                       href="{{ url('auth.register_view') }}">
                                        <span class="icon-reverse-wrapper">
                                                <span class="btn-text">Commencer maintenant</span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        </span>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="shape-wrapper" id="scene">
                            <img src="assets/images/banner/banner-01.png" alt="Hero Image">
                            <div class="hero-bg-shape-1 layer" data-depth="0.4">
                                <img src="assets/images/shape/shape-01.png" alt="Hero Image Background Shape">
                            </div>
                            <div class="hero-bg-shape-2 layer" data-depth="0.4">
                                <img src="assets/images/shape/shape-02.png" alt="Hero Image Background Shape">
                            </div>
                        </div>

                        <div class="banner-card pb--60 mb--50 swiper rbt-dot-bottom-center banner-swiper-active">
                            <div class="swiper-wrapper">

                                <!-- Start Single Card  -->
                                @foreach($topCourses as $course)
                                    <div class="swiper-slide">
                                        @component('components.course-card-s1', [
                                            'course' => $course,
                                            'badge' => 'Top course',
                                            'showDescription' => false])
                                        @endcomponent
                                    </div>
                                @endforeach
                                <!-- End Single Card  -->

                            </div>
                            <div class="rbt-swiper-pagination"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Banner Area -->

    <!-- Start Course Area -->
    <div class="rbt-course-area rbt-section-gap pt--0">
        <div class="container">
            <div class="row mb--60">
                <div class="col-lg-12">
                    <div class="section-title text-center">
                        <span class="subtitle bg-secondary-opacity">Cours populaires</span>
                        <h2 class="title">Découvrez notre grand <br/> catalogue de cours.</h2>
                    </div>
                </div>
            </div>
            <!-- Start Card Area -->
            <div class="row g-5">
                @foreach($randomCourses as $course)
                    <div class="col-lg-4 col-md-6 col-12">
                        @component('components.course-card-s1', ['course' => $course])
                        @endcomponent
                    </div>
                @endforeach

            </div>
            <!-- End Card Area -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="load-more-btn mt--60 text-center">
                        <a class="rbt-btn btn-gradient btn-lg hover-icon-reverse" href="{{ url('course.index') }}">
                                <span class="icon-reverse-wrapper">
                                    <span class="btn-text">Voir plus de cours</span>
                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Course Area -->

    <div class="rbt-separator-mid">
        <div class="container">
            <hr class="rbt-separator m-0">
        </div>
    </div>
@endcomponent
