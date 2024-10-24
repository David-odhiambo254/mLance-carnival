@php
    $partnerContent = getContent('partner.content', true);
    $partnerElement = getContent('partner.element', orderById: true);
@endphp
<div class="brand py-60">
    <div class="container">
        <div class="brand__inner">
            <div class="brand__logos-wrapper">
                <div class="brand__logos brand__slider">
                    @foreach ($partnerElement as $brand)
                        <img src="{{ frontendImage('partner', @$brand->data_values->image, '160x30') }}" alt="@lang('image')">
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
