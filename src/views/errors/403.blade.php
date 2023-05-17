{{--
Need to provide :
- $message : (optional) the message to display to the user
--}}

@component('layouts.app', ['title' => 'Erreur 403'])
    <div class="rbt-error-area bg-gradient-11 rbt-section-gap">
        <div class="error-area">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-10">
                        <h1 class="title">403 !</h1>
                        <h3 class="sub-title">Accès refusé</h3>
                        <p>
                            @if(!empty($message))
                                {{ $message }}
                            @else
                                Vous n'avez pas les droits nécessaires pour accéder à cette page ou effectuer cette
                                action.
                            @endif
                        </p>
                        <a class="rbt-btn btn-gradient icon-hover" href="{{ url('home') }}">
                            <span class="btn-text">Retourner à l'accueil</span>
                            <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                        </a>
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
