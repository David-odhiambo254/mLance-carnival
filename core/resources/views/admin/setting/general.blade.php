@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group">
                                    <label> @lang('Site Title')</label>
                                    <input class="form-control" name="site_name" type="text" value="{{ gs('site_name') }}" required>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('Currency')</label>
                                    <input class="form-control" type="text" name="cur_text" required value="{{gs('cur_text')}}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Currency Symbol')</label>
                                    <input class="form-control" name="cur_sym" type="text" value="{{ gs()->cur_sym }}" required>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group">
                                    <label class="required"> @lang('Timezone')</label>
                                    <select class="select2 form-control" name="timezone">
                                        @foreach ($timezones as $key => $timezone)
                                            <option value="{{ @$key }}" @selected(@$key == $currentTimezone)>{{ __($timezone) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group">
                                    <label> @lang('Buyer Percent Fee')</label>
                                    <div class="input-group">
                                        <input class="form-control" name="buyer_percent_fee" type="text" value="{{ getAmount(gs()->buyer_percent_fee) }}" step="any" required />
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group">
                                    <label> @lang('Buyer Fixed Fee')</label>
                                    <div class="input-group">
                                        <input class="form-control" name="buyer_fixed_fee" type="text" value="{{ getAmount(gs()->buyer_fixed_fee) }}" step="any" required />
                                        <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group">
                                    <label> @lang('Seller Percent Fee')</label>
                                    <div class="input-group">
                                        <input class="form-control" name="seller_percent_fee" type="text" value="{{ getAmount(gs()->seller_percent_fee) }}" step="any" required />
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group">
                                    <label> @lang('Seller Fixed Fee')</label>
                                    <div class="input-group">
                                        <input class="form-control" name="seller_fixed_fee" type="text" value="{{ getAmount(gs()->seller_fixed_fee) }}" step="any" required />
                                        <span class="input-group-text">{{ __(gs()->cur_text) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <div class="form-group">
                                    <label class="required"> @lang('Site Base Color')</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-0 p-0">
                                            <input class="form-control colorPicker" type='text' value="{{ gs('base_color') }}">
                                        </span>
                                        <input class="form-control colorCode" name="base_color" type="text" value="{{ gs('base_color') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <div class="form-group">
                                <label> @lang('Record to Display Per page')</label>
                                <select class="select2 form-control" name="paginate_number" data-minimum-results-for-search="-1">
                                    <option value="20" @selected(gs('paginate_number') == 20)>@lang('20 items per page')</option>
                                    <option value="50" @selected(gs('paginate_number') == 50)>@lang('50 items per page')</option>
                                    <option value="100" @selected(gs('paginate_number') == 100)>@lang('100 items per page')</option>
                                </select>
                            </div>
                            </div>

                            <div class="col-xl-4 col-sm-6">
                                <div class="form-group">
                                <label class="required"> @lang('Currency Showing Format')</label>
                                <select class="select2 form-control" name="currency_format" data-minimum-results-for-search="-1">
                                    <option value="1" @selected(gs('currency_format') == Status::CUR_BOTH)>@lang('Show Currency Text and Symbol Both')</option>
                                    <option value="2" @selected(gs('currency_format') == Status::CUR_TEXT)>@lang('Show Currency Text Only')</option>
                                    <option value="3" @selected(gs('currency_format') == Status::CUR_SYM)>@lang('Show Currency Symbol Only')</option>
                                </select>
                            </div>
                            </div>
                            <div class="col-12">
                                <small class="form-group ">
                                    <i class="la la-info-circle text--primary"></i>
                                    <i class="text-muted">@lang('Fixed + Percent charge amount will be applied on each order within') {{ __(gs()->site_name) }} .</i>
                                </small>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/admin/css/spectrum.css') }}" rel="stylesheet">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";


            $('.colorPicker').spectrum({
                color: $(this).data('color'),
                change: function(color) {
                    $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                }
            });

            $('.colorCode').on('input', function() {
                var clr = $(this).val();
                $(this).parents('.input-group').find('.colorPicker').spectrum({
                    color: clr,
                });
            });
        })(jQuery);
    </script>
@endpush
