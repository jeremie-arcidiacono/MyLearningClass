{{--
Author      : Jérémie Arcidiacono
Created     : May 2023
Description : The registration page
--}}

@component('layouts.app', ['title' => 'Inscription'])
    <div class="rbt-elements-area bg-color-white rbt-section-gap">
        <div class="container">
            <div class="row gy-5 row--30 justify-content-center">
                <div class="col-lg-6">
                    <div class="rbt-contact-form contact-form-style-1 max-width-auto">
                        <h3 class="title">Inscription</h3>
                        <form action="{{ url('auth.register')}}" method="post" class="max-width-auto">
                            @customCsrf

                            @component('components.form-input-s1', [
                                'name' => 'email',
                                'type' => 'email',
                            ])
                                Adresse e-mail *
                            @endcomponent

                            @component('components.form-input-s1', [
                                'name' => 'prenom',
                                'otherAttributes' => 'minlength="2"',
                            ])
                                Prénom *
                            @endcomponent

                            @component('components.form-input-s1', [
                                'name' => 'nom',
                                'otherAttributes' => 'minlength="2"',
                            ])
                                Nom *
                            @endcomponent

                            @component('components.form-input-s1', [
                                'name' => 'motDePasse',
                                'type' => 'password',
                                'sticky' => false,
                                'otherAttributes' => 'minlength="8"',
                            ])
                                Mot de passe *
                            @endcomponent

                            @component('components.form-input-s1', [
                                'name' => 'motDePasseConfirmation',
                                'type' => 'password',
                                'sticky' => false,
                                'otherAttributes' => 'minlength="8"',
                            ])
                                Confirmation du mot de passe *
                            @endcomponent

                            <div class="d-inline-flex mb--15">
                                Type de compte
                                <div class="rbt-form-check ml--30" style="width: fit-content">
                                    <input class="form-check-input" type="radio" name="typeDeCompte"
                                           id="typeDeCompte_1" value="1" checked>
                                    <label class="form-check-label" for="typeDeCompte_1"> Étudiant</label>
                                </div>
                                <div class="rbt-form-check ml--10" style="width: fit-content">
                                    <input class="form-check-input" type="radio" name="typeDeCompte"
                                           id="typeDeCompte_2" value="2">
                                    <label class="form-check-label" for="typeDeCompte_2"> Enseignant</label>
                                </div>
                            </div>
                            <div class="row mb--30">
                                @component('components.recaptcha')@endcomponent
                            </div>

                            <div class="form-submit-group">
                                <button type="submit" class="rbt-btn btn-md btn-gradient hover-icon-reverse w-100">
                                    <span class="icon-reverse-wrapper">
                                        <span class="btn-text">S'inscrire</span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    </span>
                                </button>
                            </div>
                            <div class="row mt--20">
                                <div class="text-center">
                                    <a class="rbt-btn-link" href="{{ url('auth.login_view') }}">Vous avez déjà un compte
                                        ?</a>
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
