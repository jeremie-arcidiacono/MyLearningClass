{{-- Start Footer aera --}}
<footer class="footer-style-2 ptb--60 bg-color-white">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-12">
                <div class="inner text-center">

                    <div class="logo">
                        <a href="{{ url('home') }}" class="d-inline-flex align-items-center">
                            <img src="/assets/images/logo/logo.png" alt="MyLearningClass Logo">
                            <h4 class="title theme-gradient mb--0 ml--10"
                                style="width: fit-content; height: fit-content">
                                {{ $appName }}
                            </h4>
                        </a>
                    </div>
                    {{-- End --}}
                    <div class="text mt--20">
                        <p class="rbt-link-hover text-center">Copyright © 2023 <a href="{{ url('home') }}">
                                {{ $appName }}</a>. Tous droits réservés.
                        </p>
                        <p class="copyright-link rbt-link-hover justify-content-center justify-content-lg-end mt_sm--10
                mt_md--10">Crée par &nbsp;<a href="https://github.com/jeremie-arcidiacono" target="_blank">Jérémie
                                Arcidiacono</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
