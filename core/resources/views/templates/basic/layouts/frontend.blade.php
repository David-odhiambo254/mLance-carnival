@extends($activeTemplate . 'layouts.app')
@push('style-lib')
    <link href="{{ asset($activeTemplateTrue . 'css/odometer-theme-default.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/slick.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/main.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/magnific-popup.css') }}" rel="stylesheet">
@endpush

@section('panel')
    @include($activeTemplate . 'partials.header')

    @yield('content')

    @push('script-lib')
        @include($activeTemplate . 'partials.script_lib')
    @endpush

    @include($activeTemplate . 'partials.footer')
@endsection

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/odometer.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/viewport.js') }}"></script>
@endpush
