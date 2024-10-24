@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="gig-wrapper">
                    @include($activeTemplate . 'partials.gig_progress_bar')
                    <div class="gig-body">
                        @yield('gig-page')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
