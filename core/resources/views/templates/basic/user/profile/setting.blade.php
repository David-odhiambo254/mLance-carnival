@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                @include($activeTemplate . 'partials.profile_header')
                <div class="p-0 bg--white">
                    <div class="card custom--card">
                        <div class="card-header">
                            <h5 class="card-title">{{ __($pageTitle) }}</h5>
                        </div>
                        <div class="card-body">
                            <form class="register" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <div class="profile-thumb-wrapper">
                                            <div class="profile-thumb justify-content-center">
                                                <div class="avatar-preview">
                                                    <div class="profilePicPreview"
                                                        style="background-image: url('{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}');">
                                                    </div>
                                                    <div class="avatar-edit">
                                                        <input class="profilePicUpload" id="profilePicUpload1"
                                                            name="image" type='file' accept=".png, .jpg, .jpeg" />
                                                        <label class="btn btn--base mb-0" for="profilePicUpload1"><i
                                                                class="la la-camera"></i></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form--label">@lang('First Name')</label>
                                            <input class="form--control control-two" name="firstname" type="text"
                                                value="{{ $user->firstname }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form--label">@lang('Last Name')</label>
                                            <input class="form--control control-two" name="lastname" type="text"
                                                value="{{ $user->lastname }}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('E-mail Address')</label>
                                            <input class="form-control form--control" value="{{ $user->email }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Mobile Number')</label>
                                            <input class="form-control form--control" value="{{ $user->mobile }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group ">
                                            <label class="form-label">@lang('Address')</label>
                                            <input class="form-control form--control" name="address" type="text"
                                                value="{{ @$user->address }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('State')</label>
                                            <input class="form-control form--control" name="state" type="text"
                                                value="{{ @$user->state }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Zip Code')</label>
                                            <input class="form-control form--control" name="zip" type="text"
                                                value="{{ @$user->zip }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('City')</label>
                                            <input class="form-control form--control" name="city" type="text"
                                                value="{{ @$user->city }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Country')</label>
                                            <input class="form-control form--control" value="{{ @$user->country_name }}"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Tagline')</label>
                                            <input class="form--control" name="tagline" type="text"
                                                value="{{ old('tagline', @$user->tagline) }}" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">@lang('Description')</label>
                                        <textarea class="form--control" name="description">{{ old('description', @$user->description) }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group text-end">
                                    <button class="btn btn--base" type="submit">@lang('Submit')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .form-control:focus {
            box-shadow: none !important;
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";

        function proPicURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = $(input).parents('.profile-thumb').find('.profilePicPreview');
                    $(preview).css('background-image', 'url(' + e.target.result + ')');
                    $(preview).addClass('has-image');
                    $(preview).hide();
                    $(preview).fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(".profilePicUpload").on('change', function() {
            proPicURL(this);
        });

        $(".remove-image").on('click', function() {
            $(".profilePicPreview").css('background-image', 'none');
            $(".profilePicPreview").removeClass('has-image');
        })
    </script>
@endpush
