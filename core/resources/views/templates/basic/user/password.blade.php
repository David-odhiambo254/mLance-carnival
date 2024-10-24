@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                @include($activeTemplate . 'partials.profile_header')
                <div class="p-0 bg--white">

                    <div class="card custom--card">
                        <div class="card-header">
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <h5 class="card-title">{{ $pageTitle }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <form method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label class="form-label">@lang('Current Password')</label>
                                            <input type="password" class="form-control form--control"
                                                name="current_password" required autocomplete="current-password">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">@lang('Password')</label>
                                            <input type="password"
                                                class="form-control form--control @if (gs('secure_password')) secure-password @endif"
                                                name="password" required autocomplete="current-password">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">@lang('Confirm Password')</label>
                                            <input type="password" class="form-control form--control"
                                                name="password_confirmation" required autocomplete="current-password">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
