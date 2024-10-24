@php
    $serviceContent = getContent('service.content', true);
    $serviceElement = getContent('service.element', orderById: true);
@endphp
<section class="popular-services pb-120">
    <div class="container">
        <div class="section-heading">
            <h3 class="section-heading__title" data-break="-1" data-length="1">{{ __($serviceContent->data_values->heading) }}</h3>
        </div>
        <div class="service-slider">
            @foreach ($serviceElement as $service)
                <a class="service-slider-card" href="{{ url($service->data_values->service_link) }}">
                    <img class="service-slider-card__thumb" src="{{ frontendImage('service', @$service->data_values->image, '310x465') }}" alt="@lang('image')">
                    <div class="service-slider-card__content">
                        <span class="text fs-14">{{ __($service->data_values->subtitle) }}</span>
                        <h6 class="title">{{ __($service->data_values->title) }}</h6>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
