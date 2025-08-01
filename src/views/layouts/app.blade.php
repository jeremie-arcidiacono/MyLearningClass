{{--
Author      : Jérémie Arcidiacono
Created     : May 2023
Description : The main app layout with
                - headers data
                - script and style loading
                - navigation bar
                - footer

Need to provide :
    - $title : the title of the page displayed in the browser tab
    - $autoDisplayErrors : if true, this app layout will display the error message if there is one. Set to false if you want to display the error message
                          yourself in the page content
    - $stickyHeader : (optinal) if true, the header will be sticky. Set to false if you want a static header. Default is true.

--}}
@php
    if (!isset($stickyHeader)) {
        $stickyHeader = true;
        }
@endphp

        <!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ isset($title) ? "$title - " : '' }} {{ $appName }}</title>
    <meta name="robots" content="noindex, follow">
    <meta name="description" content="{{ $appName }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- Favicon --}}
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/favicon.ico">

    {{-- CSS ============================================ --}}
    <link rel="stylesheet" href="/assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/vendor/slick.css">
    <link rel="stylesheet" href="/assets/css/vendor/slick-theme.css">
    <link rel="stylesheet" href="/assets/css/plugins/sal.css">
    <link rel="stylesheet" href="/assets/css/plugins/feather.css">
    <link rel="stylesheet" href="/assets/css/plugins/fontawesome.min.css">
    <link rel="stylesheet" href="/assets/css/plugins/euclid-circulara.css">
    <link rel="stylesheet" href="/assets/css/plugins/swiper.css">
    <link rel="stylesheet" href="/assets/css/plugins/magnify.css">
    <link rel="stylesheet" href="/assets/css/plugins/odometer.css">
    <link rel="stylesheet" href="/assets/css/plugins/animation.css">
    <link rel="stylesheet" href="/assets/css/plugins/bootstrap-select.min.css">
    <link rel="stylesheet" href="/assets/css/plugins/jquery-ui.css">
    <link rel="stylesheet" href="/assets/css/plugins/magnigy-popup.min.css">
    <link rel="stylesheet" href="/assets/css/plugins/plyr.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="{{ $stickyHeader ? 'rbt-header-sticky' : 'rbt-header'}}">

{{-- Navigation section --}}
@include('layouts.navigation')

<main class="rbt-main-wrapper">
    {{-- Errors section --}}
    @if($autoDisplayErrors ?? true)
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
            @foreach($errors as $errorsOfField)
                <div class="alert alert-danger alert-dismissible fade show alert-container-custom" role="alert">
                    @if(is_array($errorsOfField) )
                        @foreach($errorsOfField as $error)
                            <p class="mb--0">{{ $error }}</p>
                        @endforeach
                    @else
                        <p class="mb--0">{{ $errorsOfField }}</p>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div class="progress">
                        <div class="progress-bar progress-bar-animated progress-bar-custom bg-danger"
                             style="width: 0"></div>
                    </div>
                </div>
            @endforeach
        </div>

        @push('scripts_errorAlertAutoClose')
            <script>
                // Animate the progress bars and remove the alert containers when the progress bar is full
                const progressBars = document.getElementsByClassName('progress-bar-custom');
                let width = 0;
                const interval = setInterval(increaseWidth, 100); // The progress bar will be full in 10 seconds

                function increaseWidth() {
                    width += 1;
                    for (let i = 0; i < progressBars.length; i++) {
                        progressBars[i].style.width = width + '%';
                    }

                    // Progress bar is full: start removing the alert containers
                    if (width >= 100) {
                        clearInterval(interval);
                        const alertContainers = Array.from(document.getElementsByClassName('alert-container-custom'));
                        for (let j = 0; j < alertContainers.length; j++) {
                            alertContainers[j].remove();
                        }
                    }
                }
            </script>
        @endpush
    @endif
    {{-- End errors section --

    {{-- Main content section --}}
    {!! $slot !!}

    {{-- Footer section --}}
    @include('layouts.footer')
</main>

{{-- Go to top button --}}
<div class="rbt-progress-parent">
    <svg class="rbt-back-circle svg-inner" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
    </svg>
</div>

{{-- Scripts --}}
{{-- Modernizer JS --}}
<script src="/assets/js/vendor/modernizr.min.js" defer></script>
{{-- jQuery JS --}}
<script src="/assets/js/vendor/jquery.js" defer></script>
{{-- Bootstrap JS --}}
<script src="/assets/js/vendor/bootstrap.min.js" defer></script>
{{-- sal.js --}}
<script src="/assets/js/vendor/sal.js" defer></script>
<script src="/assets/js/vendor/swiper.js" defer></script>
<script src="/assets/js/vendor/magnify.min.js" defer></script>
<script src="/assets/js/vendor/jquery-appear.js" defer></script>
<script src="/assets/js/vendor/odometer.js" defer></script>
<script src="/assets/js/vendor/backtotop.js" defer></script>
<script src="/assets/js/vendor/isotop.js" defer></script>
<script src="/assets/js/vendor/imageloaded.js" defer></script>

<script src="/assets/js/vendor/wow.js" defer></script>
<script src="/assets/js/vendor/waypoint.min.js" defer></script>
<script src="/assets/js/vendor/easypie.js" defer></script>
<script src="/assets/js/vendor/text-type.js" defer></script>
<script src="/assets/js/vendor/jquery-one-page-nav.js" defer></script>
<script src="/assets/js/vendor/bootstrap-select.min.js" defer></script>
<script src="/assets/js/vendor/jquery-ui.js" defer></script>
<script src="/assets/js/vendor/magnify-popup.min.js" defer></script>
<script src="/assets/js/vendor/paralax-scroll.js" defer></script>
<script src="/assets/js/vendor/paralax.min.js" defer></script>
<script src="/assets/js/vendor/countdown.js" defer></script>
<script src="/assets/js/vendor/plyr.js" defer></script>
{{-- Main JS --}}
<script src="/assets/js/main.js" defer></script>

{{-- Custom JS by page --}}
@stack('scripts*')

<script>
    // To prevent the user to put folder in the input form (with drag and drop)
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('dragover', function (e) {
            e.preventDefault();
        }, false);

        form.addEventListener('drop', function (e) {
            e.preventDefault();
        }, false);
    });
</script>

</body>

</html>
