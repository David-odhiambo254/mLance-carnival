@php
    $ctaContent = getContent('cta.content', true);
@endphp
<div class="call-to-action pb-120">
    <div class="container">
        <div class="call-to-action__content bg-img"
            data-background-image="{{ frontendImage('cta', @$ctaContent->data_values->background_image, '13x500') }}">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="call-to-action__title highlight">
                        {{ __($ctaContent->data_values->heading) }}
                    </h2>
                    <p class="mb-4 text-white">{{ __(@$ctaContent->data_values->subheading) }}</p>
                    <a class="btn btn--dark" href="{{ url($ctaContent->data_values->button_link) }}">
                        <span> {{ __($ctaContent->data_values->button_name) }}</span>
                    </a>
                </div>
                <div class="col-lg-6">
                    <div class="cta-thumb">
                        <img class="cta-people position-absolute d-none d-lg-block"
                            src="{{ frontendImage('cta', @$ctaContent->data_values->right_image, '410x500') }}" alt="Image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
