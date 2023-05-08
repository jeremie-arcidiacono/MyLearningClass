@component('layouts.app', ['title' => 'Erreur 500'])
    <div class="rbt-error-area bg-gradient-18 rbt-section-gap">
        <div class="error-area">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-10">
                        <h1 class="title">500 !</h1>
                        <h3 class="sub-title">Erreur interne du serveur</h3>
                        <p>Une erreur est survenue lors du traitement de votre demande.</p>
                        <p>Veuillez réessayer ultérieurement.</p>
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
