@php
    $counterContent = getContent('counter.content',true);
    $counters = getContent('counter.element', orderById:true);
@endphp

<section class="statistics-area pb-120">
    <div class="container">
        <div class="bg-darkGreen statistics-area-bg">
            <div class="row align-items-center">
                <div class="col-12 col-xl-7">
                    <div class="statistics-content" data-aos="fade-up" data-aos-duration="1000" data-aos-easing="linear">
                        <p class="statistics-subtitle fw-bold mb-2">{{ __(@$counterContent->data_values->subtitle) }}</p>
                        <h2 class="section-title-light fw-bold mb-4">{{ __(@$counterContent->data_values->title) }}</h2>
                        <p class="section-desc-light mb-4">{{ __(@$counterContent->data_values->description) }}</p>
                        <a href="{{ @$counterContent->data_values->button_link }}" class="btn btn--base">{{ __(@$counterContent->data_values->button_name) }}</a>
                    </div>
                    <div class="statistics-counter mt-5">
                        @foreach ($counters as $counter)
                        <div class="statistics-counter-item">
                            <h4 class="statistics-counter-item__title mb-1">
                                <span class="odometer" data-odometer-final="{{ @$counter->data_values->count }}">0</span>{{ __(@$counter->data_values->indicator) }}
                            </h4>
                            <p class="statistics-counter-desc fw-bold">{{ __(@$counter->data_values->title) }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-12 col-xl-5 mt-5 mt-xl-0">
                    <div class="statistics-img">
                        <img src="{{ frontendImage('counter', @$counterContent->data_values->image,'480x425') }}" class="img-fluid w-100" alt="@lang('image')" >
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
