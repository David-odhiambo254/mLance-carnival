@php
    $order = $project->order;
    $now = Carbon\Carbon::now();
    $interval = $now->diff(@$order->deadline);
    $daysLeft = $interval->days;
@endphp

<div class="order-details-info">
    <div class="order-details__profile">
        <div class="order-details__profile-thumb mb-3">
            <img src="{{ getImage(getFilePath('gig') . '/' . thumbnail($project->gig->id)->name, getFileSize('gig')) }}">
        </div>
        <h5 class="order-details__profile-title mb-2">
            {{ __($project->gig->title) }}
        </h5>
        <p class="order-details__profile-subtitle mb-3"> {{ __(@$order->quotes) }}</p>
    </div>
    <ul class="order-details__information">
        <li><span class="name"><i class="las la-money-bill-wave"></i> @lang('Amount') :</span>
            <span class="value">{{ showAmount(@$order->price) }}</span>
        </li>
        <li><span class="name"><i class="las la-history"></i> @lang('Deadline') :</span>
            @if (@$order->status == Status::ORDER_PENDING)
                <span class="value"><small>@lang('Expected deadline will come after accepting the order')</small></span>
            @else
                <span class="value">{{ showDateTime(@$order->deadline, 'd M, Y h:i A') }}
                    @if ($daysLeft > @$order->deadline)
                        <span>({{ $daysLeft }} @lang('days left'))</span>
                    @else
                        <small>@lang('(Expected)')</small>
                    @endif
                </span>
            @endif
        </li>
        <li><span class="name"><i class="las la-grin-stars"></i> @lang('Status') :</span>
            <span lass="value statusBadge">
                @php
                    echo @$project->statusBadge;
                @endphp
            </span>
        </li>
    </ul>
</div>

<div class="sticky-order-header">
    <div class="order-details-tabs nav-item mt-4">
        <a class="{{ menuActive('admin.project.details') }} nav-link"
            href="{{ route('admin.project.details', $project->id) }}"> <i class="las la-envelope"></i>
            @lang('Messages')</a>
        <a class="{{ menuActive('admin.project.files') }} nav-link"
            href="{{ route('admin.project.files', $project->id) }}"> <i class="las la-folder-open"></i>
            @lang('Files')</a>
    </div>
</div>

<x-confirmation-modal />

@push('breadcrumb-plugins')
    @if ($project->status == Status::PROJECT_REPORTED)
        <button class="btn btn-outline--danger confirmationBtn"
            data-action="{{ route('admin.project.reject', $project->id) }}" data-question="@lang('Are you sure to reject this project')?"
            type="button">
            <i class="la la-times"></i> @lang('Reject')
        </button>
        <button class="btn btn-outline--success confirmationBtn"
            data-action="{{ route('admin.project.complete', $project->id) }}" data-question="@lang('Are you sure to complete this project')?"
            type="button">
            <i class="la la-check"></i> @lang('Complete')
        </button>
    @endif
@endpush
