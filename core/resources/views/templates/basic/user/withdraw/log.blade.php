@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-2">
            <div class="col-lg-12">
                @if (!blank($withdraws))
                    <form>
                        <div class="d-flex justify-content-end mb-3">
                            <div class="input-group search-group">
                                <input class="form-control form--control" name="search" type="text"
                                    value="{{ request()->search }}" placeholder="@lang('Search by transactions')">
                                <button class="input-group-text btn--base text-white">
                                    <i class="las la-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
                <div class="card custom--card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 py-2">{{ __($pageTitle) }}</h6>
                        <a class="btn btn--base btn--sm" href="{{ route('user.withdraw') }}"> <i class="las la-wallet"></i>
                            @lang('Withdraw Now')</a>
                    </div>
                    <div class="card-body p-0">
                        @if (!blank($withdraws))
                            <div class="table-responsive--sm table-responsive">
                                <table class="table--light style--two custom-data-table table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Gateway | Transaction')</th>
                                            <th class="text-center">@lang('Initiated')</th>
                                            <th class="text-center">@lang('Amount')</th>
                                            <th class="text-center">@lang('Conversion')</th>
                                            <th class="text-center">@lang('Status')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($withdraws as $withdraw)
                                            @php
                                                $details = [];
                                                foreach ($withdraw->withdraw_information as $key => $info) {
                                                    $details[] = $info;
                                                    if ($info->type == 'file') {
                                                        $details[$key]->value = route(
                                                            'user.download.attachment',
                                                            encrypt(getFilePath('verify') . '/' . $info->value),
                                                        );
                                                    }
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    <span class="fw-bold"><span class="text--base">
                                                            {{ __(@$withdraw->method->name) }}</span></span>
                                                    <br>
                                                    <small>{{ $withdraw->trx }}</small>
                                                </td>
                                                <td class="text-center">
                                                    {{ showDateTime($withdraw->created_at) }} <br>
                                                    {{ diffForHumans($withdraw->created_at) }}
                                                </td>
                                                <td class="text-center">
                                                    {{ showAmount($withdraw->amount) }} - <span class="text--danger"
                                                        data-bs-toggle="tooltip"
                                                        title="@lang('Processing Charge')">{{ showAmount($withdraw->charge) }}
                                                    </span>
                                                    <br>
                                                    <strong data-bs-toggle="tooltip" title="@lang('Amount after charge')">
                                                        {{ showAmount($withdraw->amount - $withdraw->charge) }}
                                                    </strong>

                                                </td>
                                                <td class="text-center">
                                                    {{ showAmount(1) }} =
                                                    {{ showAmount($withdraw->rate, currencyFormat: false) }}
                                                    {{ __($withdraw->currency) }}
                                                    <br>
                                                    <strong>{{ showAmount($withdraw->final_amount, currencyFormat: false) }}
                                                        {{ __($withdraw->currency) }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    @php echo $withdraw->statusBadge @endphp
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn--base detailBtn"
                                                        data-user_data="{{ json_encode($details) }}"
                                                        @if ($withdraw->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                                                        <i class="la la-desktop"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            @include($activeTemplate . 'partials.empty', [
                                'message' => 'No withdraw found',
                            ])
                        @endif
                    </div>
                    @if ($withdraws->hasPages())
                        <div class="card-footer">
                            {{ paginateLinks($withdraws) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>

    {{-- APPROVE MODAL --}}
    <div class="modal fade" id="detailModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData">

                    </ul>
                    <div class="feedback"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-dark btn-sm" data-bs-dismiss="modal" type="button">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var userData = $(this).data('user_data');
                var html = ``;
                userData.forEach(element => {
                    if (element.type != 'file') {
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${element.name}</span>
                            <span">${element.value}</span>
                        </li>`;
                    } else {
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${element.name}</span>
                            <span"><a href="${element.value}"><i class="fa-regular fa-file"></i> @lang('Attachment')</a></span>
                        </li>`;
                    }
                });
                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);

                modal.modal('show');
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .search-group {
            width: 50%;
        }

        @media (max-width:575px) {
            .search-group {
                width: 100%;
            }
        }
    </style>
@endpush
