@php
    $content = getContent('login.content', true);
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
    <section class="account">
        <div class="account__inner">
            <div class="account-left bg-img" data-background-image="{{ frontendImage('login', @$content->data_values->image, '640x960') }}">
                <div class="account-left__content">
                    <h2 class="account-left__title">
                        {{ __($content->data_values->heading) }}
                    </h2>
                    <p class="account-left__desc">
                        {{ __($content->data_values->subheading) }}
                    </p>
                    <ul class="tag-list">
                        @foreach ($subcategories as $subcategory)
                            <li class="tag-list__item"><a class="tag-list__link" href="{{ route('gig.subcategories', $subcategory->id) }}">{{ __($subcategory->name) }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="brand__inner py-20">
                        <div class="brand__logos-wrapper">
                            <div class="brand__logos brand__slider-two">
                                @foreach ($partnerElement as $partner)
                                    <img src="{{ frontendImage('partner', @$partner->data_values->image, '160x30') }}" alt="@lang('image')">
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
                            @lang('Don\'t have an account?') <a href="{{ route('user.register') }}"><span class="underline">@lang('Sign Up')</span></a>
                        </p>
                    </div>

                    <div class="account-right__content">
                        <form class="account-form verify-gcaptcha" method="POST" action="{{ route('user.login') }}">
                            @csrf
                            <div class="section-heading">
                                <h3 class="account-right__title section-heading__title " data-break="-1" data-length="1">{{ __(@$content->data_values->title) }}</h3>
                                <p class="account-right__title__desc">{{ __(@$content->data_values->subtitle) }}</p>
                            </div>

                            @include($activeTemplate . 'partials.social_login')

                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="form--label required" for="username">@lang('Username or Email')</label>
                                    <input class="form--control" id="username" name="username" type="text" value="{{ old('username') }}" required>
                                </div>

                                <div class="col-md-12 form-group">
                                    <label class="form--label required" for="your-password">@lang('Password')</label>
                                    <div class="position-relative">
                                        <input class="form--control" id="your-password" name="password" type="password" required>
                                        <span class="password-show-hide fas toggle-password fa-eye-slash" id="#your-password"></span>
                                    </div>
                                </div>

                                <x-captcha />

                                <div class="col-md-12 form-group">
                                    <div class="d-flex justify-content-between flex-wrap">
                                        <div class="form--check">
                                            <input class="form-check-input" id="remember" name="remember" type="checkbox" @checked(old('remember'))>
                                            <label class="form-check-label" for="remember">@lang('Remember Me')</label>
                                        </div>
                                        <a class="forgot-password" href="{{ route('user.password.request') }}">@lang('Forgot your password?')</a>
                                    </div>
                                </div>
                                <div class="col-md-12 form-group">
                                    <button class="w-100 btn btn--base" id="recaptcha" type="submit">
                                        @lang('Sign In')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style-lib')
    <link href="{{ asset($activeTemplateTrue . 'css/main.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    @include($activeTemplate . 'partials.script_lib')
@endpush
