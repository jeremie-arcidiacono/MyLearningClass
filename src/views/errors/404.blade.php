{{--
Need to provide :
- $message : (optional) the title of the page displayed in the browser tab
--}}

@component('layouts.app', ['title' => 'Page introuvable'])
    <div class="rbt-error-area bg-gradient-11 rbt-section-gap">
        <div class="error-area">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-10">
                        <h1 class="title">404 !</h1>
                        <h3 class="sub-title">Page introuvable</h3>
                        <p>
                            @if(!empty($message))
                                {{ $message }}
                            @else
                                La page que vous cherchez n'a pas pu être trouvée.
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
