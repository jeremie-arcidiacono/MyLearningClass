{{--
Author      : Jérémie Arcidiacono
Created     : May 2023
Description : The login page
--}}

@component('layouts.app', ['title' => 'Connexion', 'autoDisplayErrors' => true])
    <div class="rbt-elements-area bg-color-white rbt-section-gap">
        <div class="container">
            <div class="row gy-5 row--30 justify-content-center">
                <div class="col-lg-6">
                    <div class="rbt-contact-form contact-form-style-1 max-width-auto">
                        <h3 class="title">Connexion</h3>
                        <form action="{{ url('auth.login')}}" method="post" class="max-width-auto">
                            @customCsrf

                            @component('components.form-input-s1', [
                                'name' => 'email',
                                'type' => 'email',
                            ])
                                Adresse e-mail *
                            @endcomponent

                            @component('components.form-input-s1', [
                                'name' => 'motDePasse',
                                'type' => 'password',
                                'sticky' => false,
                            ])
                                Mot de passe *
                            @endcomponent

                            <div class="row mb--30">
                                @component('components.recaptcha')@endcomponent
                            </div>

                            <div class="form-submit-group">
                                <button type="submit" class="rbt-btn btn-md btn-gradient hover-icon-reverse w-100">
                                    <span class="icon-reverse-wrapper">
                                        <span class="btn-text">Se connecter</span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    </span>
                                </button>
                            </div>
                            <div class="row mt--20">
                                <div class="text-center">
                                    <a class="rbt-btn-link" href="{{ url('auth.register_view') }}">Vous n'avez pas de
                                        compte ?</a>
                                </div>
                            </div>
                        </form>
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
