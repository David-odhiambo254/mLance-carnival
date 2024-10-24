@php
    $policyPages = getContent('policy_pages.element', false, null, true);
    $content = getContent('register.content', true);
    $subcategories = App\Models\Subcategory::active()
        ->whereHas('category', function ($q) {
            $q->active();
        })
        ->featured()
        ->hasGigs()
        ->get();
    $partnerElement = getContent('partner.element', orderById: true);
@endphp
@extends($activeTemplate . 'layouts.app')
@section('panel')
    @if (gs('registration'))
        <section class="account">
            <div class="account__inner">
                <div class="account-left bg-img"
                    data-background-image="{{ frontendImage('register', @$content->data_values->image, '640x960') }}">
                    <div class="account-left__content">
                        <h2 class="account-left__title">
                            {{ __($content->data_values->heading) }}
                        </h2>
                        <p class="account-left__desc">
                            {{ __($content->data_values->subheading) }}
                        </p>
                        <ul class="tag-list">
                            @foreach ($subcategories as $subcategory)
                                <li class="tag-list__item"><a class="tag-list__link"
                                        href="{{ route('gig.subcategories', $subcategory->id) }}">{{ __($subcategory->name) }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="brand__inner py-20">
                            <div class="brand__logos-wrapper">
                                <div class="brand__logos brand__slider-two">
                                    @foreach ($partnerElement as $partner)
                                        <img src="{{ frontendImage('partner', @$partner->data_values->image, '160x30') }}"
                                            alt="@lang('image')">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="account-right">
                    <div class="account-right__body">
                        <div class="account-right__header">
                            <a class="account-right__logo logo" href="{{ route('home') }}">
                                <img src="{{ siteLogo('dark') }}" alt="@lang('logo')">
                            </a>

                            <p class="account-right__member-text">
                                @lang(' Already have an account?') <a href="{{ route('user.login') }}"><span
                                        class="underline">@lang('Sign In')</span></a>
                            </p>
                        </div>
                        <div class="account-right__content">
                            <form class="account-form verify-gcaptcha disableSubmission" action="{{ route('user.register') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="section-heading">
                                            <h3 class="account-right__title section-heading__title " data-break="-1"
                                                data-length="1">
                                                {{ __(@$content->data_values->title) }}</h3>
                                            <p class="account-right__title__desc">
                                                {{ __(@$content->data_values->subtitle) }}</p>
                                        </div>
                                        @include($activeTemplate . 'partials.social_login')
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-6 form-group">
                                        <label class="form--label required" for="firstname">@lang('First Name')</label>
                                        <input class="form--control" id="firstname" name="firstname" type="text"
                                            value="{{ old('firstname') }}" required>
                                    </div>

                                    <div class="col-lg-6 col-md-12 col-sm-6 form-group">
                                        <label class="form--label required" for="lastname">@lang('Last Name')</label>
                                        <input class="form--control" id="lastname" name="lastname" type="text"
                                            value="{{ old('lastname') }}" required>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                        <label class="form--label required" for="email">@lang('Email Address')</label>
                                        <input class="form--control checkUser" id="email" name="email" type="text"
                                            value="{{ old('email') }}" required>
                                    </div>

                                    <div class="col-lg-6 col-md-12 col-sm-6 form-group">
                                        <label class="form--label required" for="your-password">@lang('Password')</label>
                                        <div class="position-relative">
                                            <input
                                                class="form--control @if (gs('secure_password')) secure-password @endif"
                                                id="your-password" name="password" type="password">
                                            <div class="password-show-hide fas toggle-password fa-eye-slash"
                                                id="#your-password"></div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-12 col-sm-6 form-group">
                                        <label class="form--label required" for="confirm-password">@lang('Confirm Password')</label>
                                        <div class="position-relative">
                                            <input class="form--control" id="confirm-password" name="password_confirmation"
                                                type="password">
                                            <div class="password-show-hide fas toggle-password fa-eye-slash"
                                                id="#confirm-password"></div>
                                        </div>
                                    </div>

                                    <x-captcha />

                                    <div class="col-md-12 form-group">
                                        <div class="form--check">
                                            <input class="form-check-input" id="policy-privacy" name="agree"
                                                type="checkbox" @checked(old('agree')) required>

                                            <label class="form-check-label" for="policy-privacy">
                                                @lang('By proceeding, you agree to the')
                                                @foreach ($policyPages as $policy)
                                                    <a class="link" href="{{ route('policy.pages', $policy->slug) }}"
                                                        target="_blank">{{ __($policy->data_values->title) }}</a>
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 form-group">
                                        <button class="w-100 btn btn--base" id="recaptcha" type="submit">
                                            @lang('Sign Up')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal" id="existModalCenter">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body p-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">@lang('You are with us')</h5>
                            <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                        </div>
                        <p class="py-3">@lang('You already have an account please Login')</p>
                        <div class="text-end">
                            <button class="btn btn-dark btn--sm" data-bs-dismiss="modal"
                                type="button">@lang('Close')</button>
                            <a class="btn btn--base btn--sm" href="{{ route('user.login') }}">@lang('Login')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        @include($activeTemplate . 'partials.registration_disabled')
    @endif
@endsection

@if (gs('registration'))
    @push('style-lib')
        <link href="{{ asset($activeTemplateTrue . 'css/main.css') }}" rel="stylesheet">
    @endpush

    @push('script-lib')
        @if (gs('secure_password'))
            <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
        @endif
        @include($activeTemplate . 'partials.script_lib')
    @endpush

    @push('script')
        <script>
            "use strict";
            (function($) {

                $('.checkUser').on('focusout', function(e) {
                    var url = '{{ route('user.checkUser') }}';
                    var value = $(this).val();
                    var token = '{{ csrf_token() }}';

                    var data = {
                        email: value,
                        _token: token
                    }

                    $.post(url, data, function(response) {
                        if (response.data != false) {
                            $('#existModalCenter').modal('show');
                        }
                    });
                });
            })(jQuery);
        </script>
    @endpush

@endif
