@php
    $testimonialContent = getContent('testimonial.content', true);
    $testimonialElement = getContent('testimonial.element', orderById: true);
@endphp

<section class="testimonials pb-120">
    <div class="container">
        <div class="section-heading">
            <h3 class="section-heading__title" data-break="-1" data-length="1">{{ __($testimonialContent->data_values->heading) }}</h3>
        </div>
        <div class="testimonial-slider">
            @foreach ($testimonialElement as $testimonial)
                <div class="testimonails-card">
                    <div class="testimonial-item">
                        <span class="testi-quote"><i class="las la-quote-right"></i></span>
                        <div class="testimonial-item__rating">
                            <ul class="rating-list">
                                @php  echo avgRating((int)$testimonial->data_values->rating);  @endphp
                            </ul>
                        </div>
                        <p class="testimonial-item__desc">{{ __(@$testimonial->data_values->quote) }}</p>
                        <div class="testimonial-item__content">
                            <div class="testimonial-item__thumb">
                                <img class="fit-image" src="{{ frontendImage('testimonial', @$testimonial->data_values->image, '60x60') }}" alt="@lang('image')">
                            </div>
                            <div class="testimonial-item__details">
                                <h6 class="testimonial-item__name"> {{ __(@$testimonial->data_values->author) }}</h6>
                                <span class="testimonial-item__designation"> {{ __(@$testimonial->data_values->designation) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
