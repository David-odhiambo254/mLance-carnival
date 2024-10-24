<div class="chatboard-chat-left">
    <div class="chatboard-chat-left__search">
        <div class="form-group">
            <input class="form-control" autocomplete="off" id="search" name="search" type="text" placeholder="@lang('Search user & then press ↩ enter')">
        </div>
    </div>
    <ul class="chatboard-chat-left-item">
        @include($activeTemplate . 'user.conversation.single_conversation')
    </ul>
</div>

@push('script')
    <script>
        "use strict";
        $(document).ready(function() {
            $('#search').keyup(function(e) {
                var search = $(this).val();
                if (e.which == 13) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('user.conversation.search.list') }}',
                        data: {
                            search: search
                        },
                        success: function(response) {
                            $('.chatboard-chat-left-item').html(response.html);
                        }
                    });
                }
            });
        });
    </script>
@endpush
