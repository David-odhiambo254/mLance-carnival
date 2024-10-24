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
                                @include($activeTemplate . 'partials.empty', ['message' => 'Your message box'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
