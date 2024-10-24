@extends('admin.layouts.app')
@section('panel')
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="order-details">

                        @include('admin.project.info')

                        @php
                            $projectActivities = $project->projectActivities->whereNotNull('message')->sortByDesc('created_at');
                        @endphp
                        @if ($project->status == Status::PROJECT_REPORTED)
                            <div class="chating_message mt-4">
                                <form class="form" id="messageForm">
                                    <div class="chating_message-box">
                                        <textarea class="form-control form--control" id="message" name="message" placeholder="@lang('Write Messages')"></textarea>
                                        <button class="btn btn--primary message-send-btn" type="submit"><i
                                               class="fas fa-paper-plane"></i></button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        <div class="order-messages-area mt-4">
                            <div class="order-messages-box">
                                <div class="messagesContainer"> </div>
                                @foreach ($projectActivities as $activity)
                                    @include('admin.project.single_message')
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {

            $("#message").keydown(function(event) {
                if (event.keyCode === 13 && !event.shiftKey) {
                    event.preventDefault();
                    $("#messageForm").submit();
                }
                if (event.keyCode === 13 && event.shiftKey) {
                    var textarea = document.getElementById("message");
                    var height = textarea.height;
                    height += 24;
                    $(this).css('height', `${height}px`)
                }
            });


            $('#messageForm').on('submit', function(e) {
                e.preventDefault();
                var btnAfterSubmit = `<div class="spinner-border"></div>`;
                var btn = $('.message-send-btn');
                btn.html(btnAfterSubmit);
                btn.attr('disabled', true);

                //store
                var formData = new FormData($('#messageForm')[0]);
                var url = '{{ route('admin.project.message', $project->id) }}';
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
                            $('[name="message"]').val('');
                            if (response.success) {
                                btn.html(
                                    `<i class="fas fa-paper-plane"></i>`
                                );
                                btn.removeAttr('disabled');
                                $('.messagesContainer').after(response.html);
                            } else {
                                notify('error', response.message);
                                btn.html(
                                    `<i class="fas fa-paper-plane"></i>`
                                );
                                btn.removeAttr('disabled');
                            }
                        },
                        error: function(xhr, status, error) {
                            notify('error', error);
                        }
                    });
                }, 1000);
            });

        })(jQuery);
    </script>
@endpush
