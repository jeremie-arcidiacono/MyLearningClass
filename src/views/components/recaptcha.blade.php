{{--
A recaptcha component.
(Will only be displayed if recaptcha is enabled in the .env file)
--}}

@if(
    \App\Recaptcha::isEnabled()
)
    @push('scripts')
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endpush

    <div class="g-recaptcha" data-sitekey="{{ \App\Recaptcha::getSiteKey() }}"></div>
@endif
