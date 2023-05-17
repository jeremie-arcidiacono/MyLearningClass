@php
    /** @var \App\Auth $auth */
@endphp

{{-- Start Header Area --}}
<header class="rbt-header rbt-header-10">
    <div class="rbt-sticky-placeholder"></div>
    <div class="rbt-header-wrapper header-space-betwween header-sticky">
        <div class="container-fluid">
            <div class="mainbar-row rbt-navigation-center align-items-center">
                <div class="header-left rbt-header-content">
                    <div class="header-info">
                        <div class="logo">
                            <a href="{{ url('home') }}">
                                <img src="/assets/images/logo/logo.png" alt="MyLearningClass logo">
                            </a>
                        </div>
                    </div>
                    <div class="header-info">
                        <a href="{{ url('home') }}">
                            <h4 class="title theme-gradient mb--0">{{ $appName }}</h4>
                        </a>
                    </div>
                </div>

                {{-- Navigation links  --}}
                <div class="rbt-main-navigation d-none d-xl-block">
                    <nav class="mainmenu-nav">
                        <ul class="mainmenu" style="height: 80px">

                            <a class="transparent-button fs-3 p-5"
                               href="{{ url('course.index') }}">
                                Voir les cours
                                <i>
                                    <svg width="17" height="12" xmlns="http://www.w3.org/2000/svg">
                                        <g stroke="#27374D" fill="none" fill-rule="evenodd">
                                            <path d="M10.614 0l5.629 5.629-5.63 5.629"></path>
                                            <path stroke-linecap="square" d="M.663 5.572h14.594"></path>
                                        </g>
                                    </svg>
                                </i></a>
                        </ul>
                    </nav>
                </div>

                <div class="header-right">

                    {{-- User/authentication related links --}}
                    @if($auth->check())
                        <ul class="quick-access">

                            <li class="account-access rbt-user-wrapper d-none d-xl-block">
                                <a href="{{ url('dashboard.index') }}">
                                    <i class="feather-user"></i>
                                    {{ $auth->getUser()->getFirstname()}}
                                </a>
                                <div class="rbt-user-menu-list-wrapper">
                                    <div class="inner">
                                        <div class="rbt-admin-profile">
                                            <div class="admin-info">
                                                <span class="name">{{ $auth->getUser()->getFirstname() . ' ' .
                                                $auth->getUser()->getLastname() }}</span>
                                                <span class="fs-5">{{ $auth->getUser()->getRole()->getLabel() }}</span>
                                            </div>
                                        </div>
                                        <ul class="user-list-wrapper">
                                            <li>
                                                <a href="{{ url('course.enrollment.index') }}">
                                                    <i class="feather-book-open"></i>
                                                    <span>Cours suivit</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ url('course.bookmark.index') }}">
                                                    <i class="feather-bookmark"></i>
                                                    <span>Favoris</span>
                                                </a>
                                            </li>
                                            @if($auth->can(\App\Enums\Action::Create,new \App\Models\Course()))
                                                <li>
                                                    <a href="{{ url('user.createdCourse') }}">
                                                        <i class="feather-monitor"></i>
                                                        <span>Cours créés</span>
                                                    </a>
                                                </li>
                                            @endif
                                            @if($auth->can(\App\Enums\Action::Create,new \App\Models\User()))
                                                <li>
                                                    <a href="{{ url('user.index') }}">
                                                        <i class="fas fa-users"></i>
                                                        <span>Utilisateurs</span>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                        <hr class="mt--10 mb--10">
                                        <ul class="user-list-wrapper">
                                            <li>
                                                <form method="POST" action="{{ url('auth.logout') }}" id="logout-form1">
                                                    <a onclick="document.getElementById('logout-form1').submit();">
                                                        <i class="feather-log-out"></i>
                                                        <span>Déconnexion</span>
                                                    </a>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>


                        </ul>

                        <div class="rbt-btn-wrapper d-none d-xl-block">
                            <a class="rbt-btn btn-border-gradient radius-round btn-sm"
                               href="{{ url('dashboard.index') }}">
                                <span data-text="Dashboard">Dashboard</span>
                            </a>
                        </div>
                    @else
                        <div class="pl--30 ml--30 mr--30">
                            <a class="rbt-btn-link fs-4" href="{{ url('auth.login_view') }}">Connexion</a>
                        </div>

                        <div class="rbt-btn-wrapper d-none d-xl-block">
                            <a class="rbt-btn btn-border-gradient radius-round btn-sm"
                               href="{{ url('auth.register_view') }}">
                                <span data-text="Inscription">Inscription</span>
                            </a>
                        </div>
                    @endif
                    {{-- Start Mobile-Menu-Bar --}}
                    <div class="mobile-menu-bar d-block d-xl-none">
                        <div class="hamberger">
                            <button class="hamberger-button rbt-round-btn">
                                <i class="feather-menu"></i>
                            </button>
                        </div>
                    </div>
                    {{-- End Mobile-Menu-Bar --}}

                </div>
            </div>
        </div>
    </div>
</header>
{{-- Mobile Menu Section --}}
<div class="popup-mobile-menu">
    <div class="inner-wrapper">
        <div class="inner-top">
            <div class="content">
                <div class="logo">
                    <a href="{{ url('home') }}">
                        <img src="/assets/images/logo/logo.png" alt="MyLearningClass logo">
                    </a>
                </div>
                <div class="ml--10 mt-auto mb-auto">
                    <a href="{{ url('home') }}">
                        <h5 class="title theme-gradient mb--0">{{ $appName }}</h5>
                    </a>
                </div>
                <div class="rbt-btn-close">
                    <button class="close-button rbt-round-btn"><i class="feather-x"></i></button>
                </div>
            </div>
        </div>

        <nav class="mainmenu-nav">
            <ul class="mainmenu">
                <ul class="mainmenu" style="height: 80px">

                    <a class="transparent-button fs-3 p-5"
                       href="{{ url('course.index') }}">
                        Voir les cours
                        <i>
                            <svg width="17" height="12" xmlns="http://www.w3.org/2000/svg">
                                <g stroke="#27374D" fill="none" fill-rule="evenodd">
                                    <path d="M10.614 0l5.629 5.629-5.63 5.629"></path>
                                    <path stroke-linecap="square" d="M.663 5.572h14.594"></path>
                                </g>
                            </svg>
                        </i></a>
                </ul>

            </ul>
        </nav>

        @if(!$auth->check())
            <div class="mobile-menu-bottom">
                <div class="rbt-btn-wrapper mb--20">
                    <a class="rbt-btn btn-border-gradient radius-round btn-sm hover-transform-none w-100 justify-content-center text-center"
                       href="{{ url('auth.register_view') }}">
                        <span>Inscription</span>
                    </a>
                </div>
                <div class="">
                    <a class="rbt-btn-link btn-gradient radius-round btn-sm hover-transform-none w-100
                justify-content-center text-center"
                       href="{{ url('auth.login_view') }}">
                        <span>Connexion</span>
                    </a>
                </div>
            </div>
        @else
            <form method="POST" action="{{ url('auth.logout') }}" id="logout-form2">
                <a class="rbt-btn-link" href="javascript:void(0);"
                   onclick="document.getElementById('logout-form2').submit();">
                    <i class="feather-log-out"></i>
                    <span>Déconnexion</span>
                </a>
            </form>
        @endif
    </div>
</div>
