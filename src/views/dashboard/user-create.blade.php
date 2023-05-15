{{--
Author      : Jérémie Arcidiacono
Created     : May 2023
Description : The page to display the form to create a new user as an admin
--}}

@component('layouts.dashboard')
    <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
        <div class="content">
            <div class="section-title">
                <h4 class="rbt-title-style-3">Créer un utilisateur</h4>
            </div>

            <form action="{{ url('user.create') }}" class="rbt-profile-row rbt-default-form row row--15" method="post">
                @customCsrf
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="rbt-form-group">
                        @component('components.form-input-s2', [
                                'name' => 'prenom',
                                'otherAttributes' => 'minlength="2"',
                            ])
                            Prénom
                        @endcomponent
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="rbt-form-group">
                        @component('components.form-input-s2', [
                                'name' => 'nom',
                                'otherAttributes' => 'minlength="2"',
                            ])
                            Nom
                        @endcomponent
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="rbt-form-group">
                        @component('components.form-input-s2', [
                            'name' => 'email',
                            'type' => 'email',
                        ])
                            Adresse e-mail
                        @endcomponent
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="filter-select rbt-modern-select">
                        <label for="role" class="">Role</label>
                        <div class="dropdown bootstrap-select w-100">
                            <select id="role" name="role" class="w-100">
                                <option value="1">Étudiant</option>
                                <option value="2">Enseignant</option>
                                <option value="3">Administrateur</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="rbt-form-group">
                        @component('components.form-input-s2', [
                                'name' => 'motDePasse',
                                'type' => 'password',
                                'sticky' => false,
                                'otherAttributes' => 'minlength="8"',
                            ])
                            Mot de passe (8 caractères minimum)
                        @endcomponent
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="rbt-form-group">
                        @component('components.form-input-s2', [
                                'name' => 'motDePasseConfirmation',
                                'type' => 'password',
                                'sticky' => false,
                                'otherAttributes' => 'minlength="8"',
                            ])
                            Confirmation du mot de passe
                        @endcomponent
                    </div>
                </div>
                <div class="col-12 mt--20">
                    <div class="rbt-form-group">
                        <button class="rbt-btn btn-gradient" type="submit">Ajouter l'utilisateur</button>
                        <small class="ml--20"><i class="feather-info"></i>Tous les champs sont obligatoires</small>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endcomponent

