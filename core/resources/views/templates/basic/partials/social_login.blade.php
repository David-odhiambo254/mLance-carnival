@php
    $credentials = gs('socialite_credentials');
@endphp

@if ($credentials->google->status == Status::ENABLE || $credentials->facebook->status == Status::ENABLE || $credentials->linkedin->status == Status::ENABLE)
    @if ($credentials->facebook->status == Status::ENABLE)
        <div class="account-social-btn mb-3">
            <a href="{{ route('user.social.login', 'facebook') }}" class="btn btn--google fs-18 w-100 flex-center"> <img src="{{ asset($activeTemplateTrue . 'images/facebook.svg') }}" alt="" class="icon">@lang('Login with Facebook')</a>
        </div>
    @endif

    @if ($credentials->google->status == Status::ENABLE)
        <div class="account-social-btn mb-3">
            <a href="{{ route('user.social.login', 'google') }}" class="btn btn--google fs-18 w-100 flex-center gap-1"> <img src="{{ asset($activeTemplateTrue . 'images/google.svg') }}" alt="" class="icon">@lang('Login with Google')</a>
        </div>
    @endif

    @if ($credentials->facebook->status == Status::ENABLE)
        <div class="account-social-btn mb-3">
            <a href="{{ route('user.social.login', 'linkedin') }}" class="btn btn--google fs-18 w-100 flex-center"> <img src="{{ asset($activeTemplateTrue . 'images/linkedin.svg') }}" alt="" class="icon">@lang('Login with Linkedin')</a>
        </div>
    @endif

    <div class="other-option">
        <span class="other-option__text">@lang('OR')</span>
    </div>
@endif
