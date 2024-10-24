@extends($activeTemplate . 'layouts.' . $layout)
@section('content')
    <div class="container @if ($layout == 'frontend') py-60 @endif">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card custom--card">
                    <div class="card-header card-header-bg d-flex flex-wrap justify-content-between align-items-center">
                        <h5 class="mt-0 mb-0">
                            @php echo $myTicket->statusBadge; @endphp
                            [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                        </h5>
                        @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                            <button class="btn btn--danger close-button btn--sm closeConfirmationBtn mt-2 mt-md-0"
                                data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}"
                                type="button"><i class="fa fa-lg fa-times-circle"></i>
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <form method="post" class="disableSubmission" action="{{ route('ticket.reply', $myTicket->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-between">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea name="message" class="form-control form--control" rows="4" required>{{ old('message') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <button type="button" class="btn btn-dark btn--sm addAttachment my-2"> <i
                                            class="fas fa-plus"></i> @lang('Add Attachment') </button>
                                    <p class="mb-2"><span class="text--base">@lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</span></p>
                                    <div class="row fileUploadsContainer">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn--base w-100 my-2" type="submit"><i
                                            class="la la-fw la-lg la-reply"></i> @lang('Reply')
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        @forelse($messages as $message)
                            @if ($message->admin_id == 0)
                                <div class="row border border--base border-radius-3 my-3 py-3 mx-2">
                                    <div class="col-md-3 border-end text-end">
                                        <h5 class="my-3">{{ $message->ticket->name }}</h5>
                                    </div>
                                    <div class="col-md-9">
                                        <p class="text-muted fw-bold my-3">
                                            @lang('Posted on') {{ showDateTime($message->created_at, 'l, dS F Y @ h:i a') }}
                                        </p>
                                        <p>{{ $message->message }}</p>
                                        @if ($message->attachments->count() > 0)
                                            <div class="mt-2">
                                                @foreach ($message->attachments as $k => $image)
                                                    <a href="{{ route('ticket.download', encrypt($image->id)) }}"
                                                        class="me-3"><i class="fa-regular fa-file"></i> @lang('Attachment')
                                                        {{ ++$k }} </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="row border border-warning border-radius-3 my-3 py-3 mx-2 reply-bg">
                                    <div class="col-md-3 border-end text-end">
                                        <h5 class="my-3">{{ $message->admin->name }}</h5>
                                        <p class="lead text-muted">@lang('Staff')</p>
                                    </div>
                                    <div class="col-md-9">
                                        <p class="text-muted fw-bold my-3">
                                            @lang('Posted on') {{ showDateTime($message->created_at, 'l, dS F Y @ h:i a') }}
                                        </p>
                                        <p>{{ $message->message }}</p>
                                        @if ($message->attachments->count() > 0)
                                            <div class="mt-2">
                                                @foreach ($message->attachments as $k => $image)
                                                    <a href="{{ route('ticket.download', encrypt($image->id)) }}"
                                                        class="me-3"><i class="fa-regular fa-file"></i> @lang('Attachment')
                                                        {{ ++$k }} </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="empty-message text-center">
                                <img src="{{ asset('assets/images/empty_list.png') }}" alt="empty">
                                <h5 class="text-muted">@lang('No replies found here!')</h5>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal" id="closeConfirmationModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0"><i class="las la-question text--warning"></i> @lang('Confirmation Alert!')</h5>
                            <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                        </div>
                        <p class="pb-3 pt-2 question"></p>
                        <div class="text-end">
                            <button class="btn btn--sm btn-dark" data-bs-dismiss="modal"
                                type="button">@lang('No')</button>
                            <button class="btn btn--sm btn--base" type="submit">@lang('Yes')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }

        .reply-bg {
            background-color: #ffd96729
        }
    </style>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addAttachment').on('click', function() {
                fileAdded++;
                if (fileAdded == 5) {
                    $(this).attr('disabled', true)
                }
                $(".fileUploadsContainer").append(`
                    <div class="col-lg-4 col-md-12 removeFileInput">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="file" name="attachments[]" class="form-control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                                <button type="button" class="input-group-text removeFile bg--danger border--danger"><i class="fas fa-times text-white"></i></button>
                            </div>
                        </div>
                    </div>
                `)
            });
            $(document).on('click', '.removeFile', function() {
                $('.addAttachment').removeAttr('disabled', true)
                fileAdded--;
                $(this).closest('.removeFileInput').remove();
            });

            $(document).on('click', '.closeConfirmationBtn', function() {
                var modal = $('#closeConfirmationModal');
                let data = $(this).data();
                modal.find('.question').text(`${data.question}`);
                modal.find('form').attr('action', `${data.action}`);
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush