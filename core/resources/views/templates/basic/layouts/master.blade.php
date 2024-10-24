@extends($activeTemplate . 'layouts.app')
@push('style-lib')
    <link href="{{ asset($activeTemplateTrue . 'css/user/main.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/user/dashboard.css ') }}" rel="stylesheet">
@endpush

@section('panel')
    @include($activeTemplate . 'partials.top_header')
    @include($activeTemplate . 'partials.auth_header')
    <div class="dashboard-area">
        @yield('content')
    </div>

    @push('script-lib')
        <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
        <script src="{{ asset($activeTemplateTrue . 'js/swiper-bundle.min.js') }}"></script>
        <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>
        <script src="{{ asset($activeTemplateTrue . 'js/magnific-popup.min.js') }}"></script>
    @endpush

    @include($activeTemplate . 'partials.auth_footer')
@endsection

