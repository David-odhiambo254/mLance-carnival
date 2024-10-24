@php
    $kycInstruction = getContent('kyc_instruction.content', true);
@endphp
@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="notice"></div>

        @if ($user->kv == Status::KYC_UNVERIFIED || $user->kv == Status::KYC_PENDING)
            <div class="project-area mb-5">
                <div class="dashboard-widget">
                    @if ($user->kv == Status::KYC_UNVERIFIED && $user->kyc_rejection_reason)
                        <div class="alert alert-danger" role="alert">
                            <div class="d-flex justify-content-between">
                                <h4 class="alert-heading">@lang('KYC Documents Rejected')</h4>
                                <button class="btn btn--sm btn-outline--base" data-bs-toggle="modal"
                                    data-bs-target="#kycRejectionReason">@lang('Show Reason')</button>
                            </div>
                            <hr>
                            <p class="mb-0">{{ __(@$kycInstruction->data_values->reject) }} <a
                                    href="{{ route('user.kyc.form') }}">@lang('Click Here to Re-submit Documents')</a>.</p>
                            <br>
                            <a href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a>
                        </div>
                    @elseif ($user->kv == Status::KYC_UNVERIFIED)
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">@lang('KYC Verification required')</h4>
                            <hr>
                            <p class="mb-0">{{ __($kycInstruction->data_values->verification_instruction) }}<a
                                    class="border-effect" href="{{ route('user.kyc.form') }}"> @lang('Click Here to Verify')</a></p>
                        </div>
                    @elseif($user->kv == Status::KYC_PENDING)
                        <div class="alert alert-warning" role="alert">
                            <h4>@lang('KYC Verification pending')</h4>
                            <hr>
                            <i>{{ __($kycInstruction->data_values->pending_instruction) }} <a class="border-effect"
                                    href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a></i>
                        </div>
                    @endif

                </div>
            </div>
        @endif

        <div class="row gy-4">
            <div class="col-lg-5">
                <div class="dashboard-widget balance-widget mb-0 h-100">
                    <div class="dashboard-widget-content">
                        <p class="title mb-0">@lang('Balance')</p>
                        <h3 class="balance">{{ showAmount($user->balance) }}</h3>
                    </div>
                    <div class="mt-2">
                        <small>
                            <i>
                                <i class="las la-info-circle"></i>
                                @lang('You are able to use your balance to purchase any gig or you can withdraw the balance as well').</i>
                        </small>
                    </div>
                    <div class="widget-btn mt-2">
                        <a class="btn btn--success btn--sm" href="{{ route('user.deposit.index') }}"> <i
                                class="las la-plus-circle"></i>
                            @lang('Deposit')</a>
                        <a class="btn btn--danger btn--sm" href="{{ route('user.withdraw.money') }}"> <i
                                class="las la-minus-circle"></i>
                            @lang('Withdraw')</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="row gy-4">
                    <div class="col-md-6">
                        <div class="dashboard-widget">
                            <a class="btn  btn-view" href="{{ route('user.gig.list') }}">@lang('View All')</a>
                            <p class="title">@lang('Total Gigs')</p>
                            <h4 class="widget-value mb-0">{{ $gigCount }} @lang('Gigs')</h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-widget">
                            <a class="btn  btn-view" href="{{ route('user.project.pending') }}">@lang('View All')</a>
                            <p class="title">@lang('Total Projects')</p>
                            <h4 class="widget-value mb-0">{{ $projectCount }} @lang('Projects')</h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-widget mb-0">
                            <a class="btn btn-view" href="{{ route('user.deposit.history') }}">@lang('View All')</a>
                            <p class="title">@lang('Deposits')</p>
                            <h4 class="widget-value mb-0">{{ showAmount($totalDeposit) }}</h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-widget mb-0">
                            <a class="btn btn-view" href="{{ route('user.withdraw.history') }}">@lang('View All')</a>
                            <p class="title">@lang('Withdrawals')</p>
                            <h4 class="widget-value mb-0">{{ showAmount($totalWithdraw) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <div class="project-area">
                    <div class="card custom--card">
                        <div class="card-header d-flex justify-content-between align-items-center gap-3 flex-wrap">
                            <h6 class="mb-0 py-2">@lang('Running Projects')</h6>
                        </div>

                        @if (!blank($projects))
                            <div class="table-responsive--sm table-responsive">
                                <table class="table table--light style--two custom-data-table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Project Title')</th>
                                            <th>@lang('Buyer / Seller')</th>
                                            <th>@lang('Deadline')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($projects as $project)
                                            <tr>
                                                <td><a class="project-title border-effect"
                                                        href="{{ route('user.project.details', $project->id) }}">{{ __($project->gig->title) }}</a>
                                                </td>
                                                <td><a class="border-effect-bottom"
                                                        href="{{ route('user.project.details', $project->id) }}">
                                                        @if ($project->buyer_id == auth()->id())
                                                            {{ $project->seller->fullname }}
                                                            <small>(@lang('Seller'))</small>
                                                        @else
                                                            {{ $project->buyer->fullname }}
                                                            <small>(@lang('Buyer'))</small>
                                                        @endif
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="fw-bold">
                                                        @if (@$order->status == Status::ORDER_PENDING)
                                                            <span><small>@lang('Expected deadline will come after accepting the order')</small></span>
                                                        @elseif(@$order->status == Status::ORDER_REJECTED)
                                                            @lang('N/A')
                                                        @else
                                                            <span
                                                                class="value">{{ showDateTime(@$order->deadline, 'd M, Y h:i A') }}
                                                                <small>@lang('(Expected)')</small></span>
                                                            <br>
                                                            {{ diffForHumans(@$order->deadline) }}
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        echo $project->statusBadge;
                                                    @endphp
                                                </td>
                                                <td>
                                                    <a class="invest-details-link"
                                                        href="{{ route('user.project.details', $project->id) }}">
                                                        <i class="las la-angle-right"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        @if (blank($projects))
                            @include($activeTemplate . 'partials.empty', [
                                'message' => 'Project not found',
                            ])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($user->kv == Status::KYC_UNVERIFIED && $user->kyc_rejection_reason)
    <div class="modal fade" id="kycRejectionReason">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('KYC Document Rejection Reason')</h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ $user->kyc_rejection_reason }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('style')
    <style>
        .btn-close {
            background: transparent;
            opacity: 1;
        }
        .btn-close:hover {
            opacity: 1;
        }
        .btn-close i {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 20px;
            color: #000;
        }
    </style>
@endpush
