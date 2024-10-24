@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $bannerContent = getContent('banner.content', true);
        $bannerElement = getContent('banner.element', orderById: true);
        $subcategories = App\Models\Subcategory::active()
            ->whereHas('category', function ($q) {
                $q->active();
            })
            ->featured()
            ->hasGigs()
            ->get();
    @endphp

    <section class="banner">
        <div class="banner-slider">
            @foreach ($bannerElement as $banner)
            <div class="single-slide">
                <img src="{{ frontendImage('banner', @$banner->data_values->image,'1920x670') }}" alt="@lang('img')">
            </div>
            @endforeach
        </div>
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="banner__content">
                    <h1 class="banner__title" data-break="-2">{{ __($bannerContent->data_values->heading) }}</h1>
                    <form class="search-form" action="{{ route('gig.index') }}">
                        <input class="form--control banner__input" name="search" type="search" value="{{ request()->search }}" required placeholder="@lang('Find the perfect service for your needs')...">
                        <button class="search-form__btn btn btn--base" type="submit">
                            <i class="las la-search"></i>
                        </button>
                    </form>

                    <div class="tag-list-wrapper">
                        <ul class="tag-list">
                            @foreach ($subcategories as $subcategory)
                                <li class="tag-list__item"><a class="tag-list__link" href="{{ route('gig.subcategories', $subcategory->id) }}">{{ __($subcategory->name) }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
