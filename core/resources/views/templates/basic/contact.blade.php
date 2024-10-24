@php
    $contactContent = getContent('contact_us.content', true);
    $contactElement = getContent('contact_us.element', false, null, true);
@endphp

@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="contact-page py-120">
        <div class="container">
            <div class="row gy-4 justify-content-center">
                <div class="col-lg-10">
                    <div class="row gy-5">
                        <div class="col-md-6">
                            <div class="contact-form">
                                <div class="contact-form-area">
                                    <div class="section-heading">
                                        <h3>{{ __(@$contactContent->data_values->heading) }}</h3>
                                        <p>{{ __(@$contactContent->data_values->subheading) }}</p>
                                    </div>
                                    <form class="verify-gcaptcha contact-form" method="POST" autocomplete="off">
                                        @csrf
                                        <div class="row gy-2">
                                            <div class="col-lg-12 form-group">
                                                <input class="form--control" placeholder="Full Name" name="name" type="text"
                                                    value="{{ old('name', @$user->fullname) }}" @if ($user && $user->profile_complete) readonly @endif
                                                    required>
                                            </div>

                                            <div class="col-lg-12 form-group">
                                                <input class="form--control" placeholder="Your Email" name="email" type="email"
                                                    value="{{ old('email', @$user->email) }}" @if ($user) readonly @endif required>
                                            </div>
                                            <div class="col-lg-12 form-group">
                                                <input class="form--control" placeholder="Subject" name="subject" type="text"
                                                    value="{{ old('subject') }}">
                                            </div>
                                            <div class="col-lg-12 form-group">
                                                <textarea class="form--control" placeholder="Message" name="message" cols="30" rows="10" required>{{ old('message') }}</textarea>
                                            </div>
                                            <x-captcha />

                                            <div class="col-lg-12 text-end">
                                                <button class="btn btn--base w-100" type="submit">@lang('Send Message')</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-map">
                                <iframe src="{{ $contactContent->data_values->map_embed_url }}" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                    </div>

                    <div class="contact-item-wrapper pt-120">
                        <div class="row gy-4 justify-content-center">
                            @foreach ($contactElement as $contact)
                                <div class="col-lg-4 col-sm-6">
                                    <div class="contact-list__item h-100">
                                        <div class="contact-list__item-icon">
                                            @php
                                                echo @$contact->data_values->icon;
                                            @endphp
                                        </div>

                                        <div class="contact-list__item-content">
                                            <h4 class="title">{{ __(@$contact->data_values->title) }}</h4>
                                            @if (in_array(@$contact->data_values->contact_type, ['mailto', 'tel']))
                                                <p class="desc">
                                                    <a href="{{ @$contact->data_values->contact_type }}:{{ @$contact->data_values->content }}">
                                                        {{ __(@$contact->data_values->content) }}
                                                    </a>
                                                </p>
                                            @else
                                                <p class="desc">{{ __(@$contact->data_values->content) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection


@push('script')
    <script>
        "use strict";
        (function($) {
            $(".call-to-action").addClass('pt-60')
        })(jQuery);
    </script>
@endpush
