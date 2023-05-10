{{--
Author      : Jérémie Arcidiacono
Created     : May 2023
Description : The layout is used for the dashboard page of any user role.
                It extends the app layout and add the dashboard content.

Need to provide :

--}}
@php
    /**
     * @var \App\Auth $auth
     * @var \App\Models\User $user The user
     */
    $user = $auth->getUser();
@endphp

@component('layouts.app', ['title' => 'Accueil Dashboard', 'stickyHeader' => false])
    <div class="rbt-page-banner-wrapper">
        <!-- Start Banner BG Image  -->
        <div class="rbt-banner-image"></div>
        <!-- End Banner BG Image  -->
    </div>

    <div class="rbt-dashboard-area rbt-section-overlayping-top rbt-section-gapBottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Start Dashboard Top  -->
                    <div class="rbt-dashboard-content-wrapper">
                        <div class="tutor-bg-photo bg_image height-350"
                             style="background-image: url('/assets/images/banner/dashboard.jpg')">
                        </div>
                        <!-- Start Tutor Information  -->
                        <div class="rbt-tutor-information">
                            <div class="rbt-tutor-information-left">
                                <div class="tutor-content">
                                    <h4 class="title">{{ $user->getFirstname() . ' ' .$user->getLastname() }}</h4>
                                    <span class="sub-title color-white">{{ $user->getRole()->getLabel() }}</span>
                                </div>
                            </div>
                            <div class="rbt-tutor-information-right">
                                <div class="tutor-btn">
                                    <a class="rbt-btn btn-md hover-icon-reverse" href="">
                                        <span class="icon-reverse-wrapper">
                        <span class="btn-text">Créer un nouveau cours</span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- End Tutor Information  -->
                    </div>
                    <!-- End Dashboard Top  -->

                    <div class="row g-5">
                        <div class="col-lg-3">
                            <!-- Start Dashboard Sidebar  -->
                            <div class="rbt-default-sidebar sticky-top rbt-shadow-box rbt-gradient-border">
                                <div class="inner">
                                    <div class="content-item-content">

                                        <div class="rbt-default-sidebar-wrapper">
                                            <div class="section-title mb--20">
                                                <h6 class="rbt-title-style-2">Bonjour, {{ $user->getFirstname() }}</h6>
                                            </div>
                                            <nav class="mainmenu-nav">
                                                <ul class="dashboard-mainmenu rbt-default-sidebar-list">
                                                    @component('components.dashboard-nav-link',[
                                                        'targetRouteName' => 'dashboard.index',
                                                        'iconName' => 'feather-home',
                                                    ])
                                                        Dashboard
                                                    @endcomponent

                                                    @component('components.dashboard-nav-link',[
                                                        'targetRouteName' => 'dashboard.enrolledCourse',
                                                        'iconName' => 'feather-book-open',
                                                    ])
                                                        Cours suivis
                                                    @endcomponent

                                                    @component('components.dashboard-nav-link',[
                                                        'targetRouteName' => 'dashboard.bookmarkedCourse',
                                                        'iconName' => 'feather-bookmark',
                                                    ])
                                                        Favoris
                                                    @endcomponent
                                                </ul>
                                            </nav>

                                            @if($auth->getUser()->getRole()->getName() === 'teacher')
                                                <div class="section-title mt--40 mb--20">
                                                    <h6 class="rbt-title-style-2">Enseignant</h6>
                                                </div>

                                                <nav class="mainmenu-nav">
                                                    <ul class="dashboard-mainmenu rbt-default-sidebar-list">
                                                        @component('components.dashboard-nav-link',[
                                                            'targetRouteName' => 'dashboard.createdCourse',
                                                            'iconName' => 'feather-monitor',
                                                        ])
                                                            Cours créés
                                                        @endcomponent
                                                    </ul>
                                                </nav>
                                            @endif

                                            @if($auth->getUser()->getRole()->getName() === 'admin')

                                                <div class="section-title mt--40 mb--20">
                                                    <h6 class="rbt-title-style-2">Admin</h6>
                                                </div>

                                                <nav class="mainmenu-nav">
                                                    <ul class="dashboard-mainmenu rbt-default-sidebar-list">
                                                    </ul>
                                                </nav>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- End Dashboard Sidebar  -->
                        </div>

                        <div class="col-lg-9">
                            {!! $slot !!}
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
