@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="chatboard-chat-area mt-5">
                    <div class="row flex-wrap-reverse">
                        <div class="col-md-4">
                            @include($activeTemplate . 'user.conversation.chat_left')
                        </div>

                        <div class="col-md-8">
                            <div class="chat-box">
                                <div class="chat-box__thread" id="message">
                                    @forelse ($conversationMessages->messages->sortBy('created_at') as $message)
                                        @include($activeTemplate . 'user.conversation.message')
                                    @empty
                                        @include($activeTemplate . 'partials.empty', [
                                            'message' => 'No message yet',
                                        ])
                                    @endforelse
                                    <div class="messageContainer"> </div>
                                </div>

                                <div class="chat-box__footer">
                                    <div class="chat-send-area">
                                        <div class="chat-send-field">
                                            <form class="send__msg" id="messageForm">
                                                <div class="input-group">
                                                    <textarea class="form--control" id="message" name="message" placeholder="@lang('Write Message')..."></textarea>
                                                    <button class="btn--base btn-sm chat-send-btn" type="submit"><i
                                                            class="las la-paper-plane"></i></button>
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
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {



            scrollToBottom();


            $(document).on('keydown', "#message", function(event) {

                if (event.keyCode === 13 && !event.shiftKey) {
                    // Prevent the default behavior of the Enter key (e.g., new line)
                    event.preventDefault();

                    // Trigger the form submission
                    $("#messageForm").submit();
                } else if (event.keyCode === 13 && event.shiftKey) {
                    // Adjust the height of the textarea when Shift + Enter is pressed
                    var textarea = document.getElementById("message");
                    var height = textarea.scrollHeight;
                    $(this).css('height', `${height}px`);
                }
            })


            //message-store-ajax
            $('#messageForm').on('submit', function(e) {
                e.preventDefault();
                var btnAfterSubmit = `<div class="spinner-border"></div>`;
                var btn = $('.chat-send-btn');
                btn.html(btnAfterSubmit);
                btn.attr('disabled', true);

                //store
                var formData = new FormData($('#messageForm')[0]);
                var url = "{{ route('user.conversation.messaging', ':id') }}";
                var token = '{{ csrf_token() }}';
                formData.append('_token', token);

                setTimeout(() => {
                    $.ajax({
                        url: url.replace(":id", "{{ @request()->convId }}"),
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
                                $('.messageContainer').before(response.html);


                                scrollToBottom();

                                $(document).ready(function() {
                                    // Get the last message's text and conversation-id
                                    var lastMessageText = $(
                                            '.chat-box__thread .single-message')
                                        .last().find('.message-text').text();
                                    var lastMessageConversationId = $(
                                            '.chat-box__thread .single-message')
                                        .last().find('.message-text').data(
                                            'conversation-id');

                                    // Limit the string to 45 characters and add "..." if it exceeds the limit
                                    if (lastMessageText.length > 45) {
                                        lastMessageText = lastMessageText.substring(
                                            0, 45) + '...';
                                    }

                                    // Find the corresponding conversation item by matching the data-id
                                    $('.chatboard-chat-left-item .conversation a')
                                        .each(function() {
                                            if ($(this).data('id') ==
                                                lastMessageConversationId) {
                                                // Update the .recent-message with the last message text
                                                $(this).find('.recent-message')
                                                    .text(lastMessageText);
                                            }
                                        });
                                });


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


            //scroll
            function scrollToBottom() {
                var ChatDiv = $('.chat-box__thread');
                var height = ChatDiv[0].scrollHeight;
                ChatDiv.scrollTop(height);
            }


        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .spinner-border {
            width: 1rem;
            height: 1rem;
        }

        #messageForm .input-group {
            border: none !important;
        }
    </style>
@endpush
