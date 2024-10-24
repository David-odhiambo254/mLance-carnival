@php
    $aboutContent = getContent('about.content', true);
    $aboutElement = getContent('about.element', orderById: true);
@endphp
<section class="proposition py-120">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-6">
                <div class="h-100 video-popup">
                    <img class="fit-image" src="{{ frontendImage('about', @$aboutContent->data_values->image, '160x30') }}" alt="@lang('image')">
                    <a class="video-play-btn" href="{{ url($aboutContent->data_values->video_url) }}">
                        <i class="fas fa-play"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="proposition__content">
                    <div class="section-heading">
                        <h3 class="section-heading__title" data-break="-1">{{ __($aboutContent->data_values->heading) }}</h3>
                    </div>

                    <div class="proposition__list">
                        <div class="row gy-4">
                            @foreach ($aboutElement as $about)
                                <div class="col-md-12">
                                    <div class="proposition__list-item">
                                        <div class="proposition__list-heading">
                                            <span class="icon">
                                                <img src="{{ frontendImage('about', @$about->data_values->image, '40x40') }}" alt="@lang('image')">
                                            </span>
                                            <div class="proposition__list-content">
                                                <h6 class="title">{{ __($about->data_values->title) }}</h6>
                                                <p class="proposition__list-description">
                                                    {{ __($about->data_values->description) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @break($loop->index == 3)
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('style-lib')
    <link href="{{ asset($activeTemplateTrue . 'css/magnific-popup.css') }}" rel="stylesheet">
@endpush
