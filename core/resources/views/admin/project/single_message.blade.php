<div class="order-messages-item">
    <div class="order-messagfe-top d-flex align-items-center justify-content-between">
        <span class="date"> {{ showDateTime($activity->created_at, 'd M Y H:i A') }}</span>
        @if (!$activity->user_id && auth()->guard('admin') && $project->status == Status::PROJECT_REPORTED)
            <button class="btn btn--sm btn--danger text-white confirmationBtn" data-action="{{ route('admin.project.history.delete', $activity->id) }}" data-question="@lang('Are you sure to delete this message?')" type="button">
                <i class="las la-trash"></i>
            </button>
        @endif
    </div>
    <div class="order-messages-item-content">
        <div class="order-messages-item__thumb">
            @if ($activity->user_id)
                <img src="{{ avatar(@$activity->user->image ? getFilePath('userProfile') . '/' . @$activity->user->image : null) }}" alt="@lang('image')">
            @else
                <img src="{{ avatar(getFilePath('adminProfile') . '/' . @$activity->admin->image) }}" alt="@lang('image')">
            @endif
        </div>

        <div class="order-messages-item__content">
            <h6 class="mb-0">
                @if ($activity->user_id)
                    {{ @$activity->user->fullname }}
                @else
                    {{ $activity->admin->name }}
                @endif
            </h6>
        </div>
    </div>
    <p class="mt-2 fs-14">{{ __($activity->message) }}</p>
</div>
