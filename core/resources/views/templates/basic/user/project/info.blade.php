@php
    $order = $project->order;
@endphp



<div class="order-complete">
    @if (@$order->status == Status::ORDER_PENDING && $project->seller_id == auth()->id())
        <button class="btn btn--success btn--sm acceptedBtn"
            data-action="{{ route('user.project.accept', $project->id) }}" data-question="@lang('Are you sure to accept this project')?"><i
                class="las la-check-circle"></i> @lang('Accept')</button>
        <button class="btn btn--danger btn--sm rejectedBtn" data-action="{{ route('user.project.reject', $project->id) }}"
            data-question="@lang('Are you sure to reject this project')?"><i class="las la-times-circle"></i> @lang('Reject')</button>
    @elseif(@$order->status == Status::ORDER_ACCEPTED && $project->seller_id == auth()->id() && $order->deadline < now())
        <button class="btn btn--warning btn--sm SellerReportedBtn"><i class="las la-ban"></i> @lang('Report')</button>
    @elseif($order->status == Status::ORDER_ACCEPTED && $project->buyer_id == auth()->id())
        <button class="btn btn--success btn--sm completedBtn"><i class="las la-check-circle"></i>
            @lang('Complete')</button>
        <button class="btn btn--warning btn--sm reportedBtn"><i class="las la-ban"></i> @lang('Report')</button>
    @elseif($order->status == Status::ORDER_COMPLETED && $project->buyer_id == auth()->id())
        <button class="btn btn--base btn--sm edit-review" type="button"><i class="la la-edit"></i>
            @lang('Edit Review')
        </button>
    @endif

</div>

<div class="order-details-info">
    <div class="order-details__profile">
        <div class="order-details__profile-thumb mb-3">
            <img
                src="{{ getImage(getFilePath('gig') . '/' . thumbnail($project->gig->id)->name, getFileSize('gig')) }}">
        </div>
        <h5 class="order-details__profile-title mb-2">
            {{ __($project->gig->title) }}
        </h5>
        <p class="order-details__profile-subtitle mb-3"> {{ __(@$order->quotes) }}</p>

    </div>
    <ul class="order-details__information">
        <li>
            <span class="name"> <i class="las la-money-bill-wave"></i> @lang('Amount') :</span>
            <span class="value">{{ showAmount($order->price) }}</span>
        </li>
        <li><span class="name"><i class="las la-history"></i> @lang('Deadline') :</span>
            @if (@$order->status == Status::ORDER_PENDING)
                <span class="value"><small>@lang('Expected deadline will come after accepting the order').</small></span>
            @elseif (@$order->status == Status::ORDER_COMPLETED)
                <span class="value">{{ showDateTime(@$order->deadline, 'd M, Y') }}</span>
            @else
                <span class="value">{{ showDateTime(@$order->deadline, 'd M, Y') }}
                    <small>@lang('(Expected)')</small>
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
    <div class="order-details-tabs mt-4">
        <a class="{{ menuActive('user.project.details*') }}" href="{{ route('user.project.details', $project->id) }}">
            <i class="las la-envelope"></i>
            @lang('Messages')</a>
        <a class="{{ menuActive('user.project.files*') }}" href="{{ route('user.project.files', $project->id) }}"> <i
                class="las la-folder-open"></i>
            @lang('Files')</a>
    </div>
</div>

<div class="modal" id="confirmModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="deleteForm">
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0"> <i class="las la-trash text--danger"></i> @lang('Confirmation Alert!')</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <p class="py-2"><small>@lang('Are you certain about deleting this file? Once confirmed, this action cannot be undone!')</small></p>
                    <div class="text-end">
                        <button class="btn btn--sm btn-dark" data-bs-dismiss="modal"
                            type="button">@lang('No')</button>
                        <button class="btn btn--sm btn--base deleteBtn" type="button">@lang('Yes')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="orderAcceptModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="acceptForm">
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0"><i class="las la-check-circle text--success"></i> @lang('Accept the Order')</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <p class="pt-2">@lang('Are you sure to accept the order?')</p>
                    <p class="py-3">@lang('Provide your anticipated delivery date, as this information is crucial for our planning and ensuring timely service.')</p>
                    <div class="form-group">
                        <label class="form--label">@lang('Exptected Deadline')</label>
                        <input class="form--control" name="deadline" type="date" required>
                    </div>
                    <div class="text-end">
                        <button class="btn btn--sm btn--base acceptBtn" type="button">@lang('Accept')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="orderRejectModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="rejectForm">
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0"><i class="las la-times-circle text--danger"></i> @lang('Reject the Order')</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <p class="py-3">@lang('Are you sure to reject this order?')</p>
                    <div class="text-end">
                        <button class="btn btn--sm btn-dark" data-bs-dismiss="modal"
                            type="button">@lang('No')</button>
                        <button class="btn btn--sm btn--base rejectBtn" type="button">@lang('Yes')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="orderCompleteModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="completeForm">
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0"><i class="las la-check-circle text--success"></i> @lang('Complete the Order')</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <p class="py-3">@lang('Are you sure to change the order status as completed?')</p>

                    <h6>@lang('Please provide your valuable review & rating.')</h6>

                    <div class="mb-5">
                        <div class='give-rating text--base'>
                            @for ($i = 5; $i >= 1; $i--)
                                <span class="rating-star" data-rating="{{ $i }}">
                                    <label for='str{{ $i }}'><i class="la la-star"></i></label>
                                </span>
                            @endfor
                            <input name="rating" type="number" value="{{ old('rating') }}" hidden>
                        </div>
                    </div>

                    <div class="form-group pt-4">
                        <textarea class="form--control" name="review" required placeholder="@lang('Write your review')"></textarea>
                    </div>

                    <div class="text-end">
                        <button class="btn btn--sm btn-dark" data-bs-dismiss="modal"
                            type="button">@lang('No')</button>
                        <button class="btn btn--sm btn--base completeBtn" type="button">@lang('Yes')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="orderReportModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="reportForm">
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0"><i class="las la-ban text--warning"></i> @lang('Report the Order')</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <p class="pb-3 pt-3">@lang('Are you sure you want to report this order? If you report this order, admin will take action and decide wether the order will be cancelled or completed.')</p>
                    <div class="form-group">
                        <label class="form--label">@lang('Reason Of Report')</label>
                        <textarea class="from--control form--control" name="message" required placeholder="@lang('Enter order reject reason.')"
                            cols="55"></textarea>
                    </div>
                    <div class="text-end">
                        <button class="btn btn--sm btn-dark" data-bs-dismiss="modal"
                            type="button">@lang('No')</button>
                        <button class="btn btn--sm btn--base reportBtn" type="button">@lang('Yes')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- update review modal -->
<div class="modal fade" id="reviewUpdateModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="reviewRatingForm">
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0"><i class="las la-star-half-alt text--warning"></i> @lang('Update Review')</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>

                    <div class="mb-5">
                        <div class='give-rating give-rating-update text--base'>
                            @for ($i = 5; $i >= 1; $i--)
                                <span class="rating-star" data-rating="{{ $i }}">
                                    <label for='str{{ $i }}'><i class="la la-star"></i></label>
                                </span>
                            @endfor
                            <input name="rating" type="number" hidden>
                        </div>
                    </div>

                    <div class="form-group pt-4">
                        <textarea class="form--control" name="review" required>{{ __(@$project->review->review) }}</textarea>
                    </div>

                    <div class="text-end">
                        <button class="btn btn--sm btn-dark" data-bs-dismiss="modal"
                            type="button">@lang('No')</button>
                        <button class="btn btn--sm btn--base updateReviewRatingBtn"
                            type="button">@lang('Yes')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('style')
    <style>
        .spinner-border {
            width: 1rem;
            height: 1rem;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $(document).on('click', '.acceptedBtn', function() {
                $('#orderAcceptModal').modal('show');
            });

            $('.acceptBtn').on('click', function() {
                var btnAfterSubmit = `<div class="spinner-border"></div>`;
                var btn = $(this);
                var btnName = `@lang('Accept')`;
                btn.html(btnAfterSubmit);
                btn.attr('disabled', true);

                //store
                var formData = new FormData($('#acceptForm')[0]);

                var url = '{{ route('user.project.accept', $project->id) }}';
                var token = '{{ csrf_token() }}';
                formData.append('_token', token);

                setTimeout(() => {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {

                            if (response.success) {
                                notify('success', response.message);
                                btn.html(btnName);

                                $('#orderAcceptModal').modal('hide');
                                $('.order-complete').addClass('d-none')
                                btn.removeAttr('disabled');
                                $('.order-details__information').find(
                                    '.badge--warning').text(
                                    "@lang('Accepted')").addClass(
                                    'badge--success').removeClass(
                                    'badge--warning');
                            } else {
                                notify('error', response.message);
                                btn.html(btnName);
                                btn.removeAttr('disabled');
                            }
                            $('[name="deadline"]').val('');
                        },
                        error: function(xhr, status, error) {
                            btn.removeAttr('disabled');
                            notify('error', error);
                        }
                    });
                }, 1000);
            });

            $(document).on('click', '.rejectedBtn', function() {
                $('#orderRejectModal').modal('show');
            });

            $('.rejectBtn').on('click', function() {
                var btnAfterSubmit = `<div class="spinner-border"></div>`;
                var btn = $(this);
                var btnName = `@lang('Reject')`;
                btn.html(btnAfterSubmit);
                btn.attr('disabled', true);

                //store
                var formData = new FormData($('#rejectForm')[0]);
                var url = '{{ route('user.project.reject', $project->id) }}';
                var token = '{{ csrf_token() }}';
                formData.append('_token', token);

                setTimeout(() => {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                notify('success', response.message);
                                btn.html(btnName);
                                $('#orderRejectModal').modal('hide');
                                btn.removeAttr('disabled');
                                $('.order-complete').addClass('d-none')
                                $('.order-details__information').find(
                                    '.badge--warning').text(
                                    "@lang('Rejected')").addClass(
                                    'badge--danger').removeClass(
                                    'badge--warning')
                            } else {
                                notify('error', response.message);
                                btn.html(btnName);
                                btn.removeAttr('disabled');
                            }
                        },
                        error: function(xhr, status, error) {
                            notify('error', error);
                            btn.html(btnName);
                            btn.removeAttr('disabled');
                        }
                    });
                }, 1000);
            });


            $(document).on('click', '.completedBtn', function() {
                $('#orderCompleteModal').modal('show');
            });

            $('.completeBtn').on('click', function() {
                var btnAfterSubmit = `<div class="spinner-border"></div>`;
                var btnName = `@lang('Yes')`;
                var btn = $(this);
                btn.html(btnAfterSubmit);
                btn.attr('disabled', true);
                //store
                var formData = new FormData($('#completeForm')[0]);

                var url = '{{ route('user.project.complete', $project->id) }}';
                var token = '{{ csrf_token() }}';
                formData.append('_token', token);

                setTimeout(() => {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                notify('success', response.message);
                                btn.html(btnName);

                                btn.removeAttr('disabled');
                                $('#orderCompleteModal').modal('hide');
                                $('.order-complete').addClass('d-none')
                                $('.order-details__information').find(
                                    '.badge--success').text(
                                    "@lang('Completed')");
                            } else {
                                notify('error', response.message);
                                btn.html(btnName);
                                btn.removeAttr('disabled');
                            }
                        },
                        error: function(xhr, status, error) {
                            notify('error', error);
                            btn.html(btnName);
                            btn.removeAttr('disabled');
                        }
                    });
                }, 1000);
            });



            $(document).on('click', '.reportedBtn', function() {
                $('#orderReportModal').modal('show');
            });

            $(document).on('click', '.SellerReportedBtn', function() {
                $('#orderReportModal').modal('show');
            });

            $('.reportBtn').on('click', function() {
                var btnAfterSubmit = `<div class="spinner-border"></div>`;
                var btnName = `@lang('Report')`;
                var btn = $(this);
                btn.html(btnAfterSubmit);
                btn.attr('disabled', true);

                //store
                var formData = new FormData($('#reportForm')[0]);
                var url = '{{ route('user.project.report', $project->id) }}';
                var token = '{{ csrf_token() }}';
                formData.append('_token', token);

                setTimeout(() => {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                notify('success', response.message);
                                btn.html(btnName);

                                $('#orderReportModal').modal('hide');
                                $('.order-complete').addClass('d-none')
                                btn.removeAttr('disabled');
                                $('.order-details__information').find(
                                    '.badge--success').text(
                                    "@lang('Reported')").addClass(
                                    'badge--warning').removeClass(
                                    'badge--success');
                            } else {
                                notify('error', response.message);
                                btn.html(btnName);
                                btn.removeAttr('disabled');
                            }
                        },
                        error: function(xhr, status, error) {
                            notify('error', error);
                            btn.html(btnName);
                            btn.removeAttr('disabled');
                        }
                    });

                }, 1000);
            });

            //delete-modal
            $(document).on('click', '.confirmBtn', function() {
                let data = $(this).data();
                $('#confirmModal').modal('show');
                var orderMessagesItem = $(this).closest('.order-messages-item');

                $('.deleteBtn').on('click', function() {
                    var btnAfterSubmit = `<div class="spinner-border"></div>`;
                    var btnName = `@lang('Yes')`;
                    var btn = $(this);
                    btn.html(btnAfterSubmit);
                    btn.attr('disabled', true);

                    //store
                    var formData = new FormData();
                    var url = data.action;
                    var token = '{{ csrf_token() }}';
                    formData.append('_token', token);

                    setTimeout(() => {
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                if (response.success) {
                                    btn.html(btnName);
                                    btn.removeAttr('disabled');
                                    $('#confirmModal').modal('hide')
                                    orderMessagesItem.remove();
                                } else {
                                    notify('error', response.message);
                                    btn.html(btnName);
                                    btn.removeAttr('disabled');
                                }
                            }
                        });
                    }, 500);
                });
            });

            // Check Radio-box - review-rating//
            $(".give-rating input:radio").attr("checked", false);

            $(document).on('click', '.edit-review', function() {
                $('#reviewUpdateModal').modal('show');
            });

            //update review&rating//
            var existRating = `{{ @$project->review->rating }}`;
            $(`.rating-star[data-rating="${existRating}"]`).addClass('checked');
            $('[name=rating]').val(existRating);
            $('.rating-star').on('click', function() {
                let rating = $(this).data('rating');
                $('.rating-star').removeClass('checked');
                $(this).addClass('checked');
                $('[name=rating]').val(rating);
            });

            //review&RatingUpdate//
            $('.updateReviewRatingBtn').on('click', function() {
                var btnAfterSubmit = `<div class="spinner-border"></div>`;
                var btnName = `@lang('Yes')`;
                var btn = $(this);
                btn.html(btnAfterSubmit);
                btn.attr('disabled', true);

                //store
                var formData = new FormData($('#reviewRatingForm')[0]);
                var url = '{{ route('user.project.review.rating.update', $project->id) }}';
                var token = '{{ csrf_token() }}';
                formData.append('_token', token);

                setTimeout(() => {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                notify('success', response.message);
                                btn.html(btnName);
                                location.reload();
                            } else {
                                notify('error', response.message);
                                btn.html(btnName);
                                btn.removeAttr('disabled');
                            }
                        },
                        error: function(xhr, status, error) {
                            notify('error', error);
                            btn.html(btnName);
                            btn.removeAttr('disabled');
                        }
                    });

                }, 1000);
            });



        })(jQuery);
    </script>
@endpush
