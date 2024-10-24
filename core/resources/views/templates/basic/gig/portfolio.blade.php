@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="portfolio-page py-60">
        <div class="container">
            <div class="portfolio-page__top">
                <a class="back-link order-2 order-sm-1" href="{{ route('gig.owner.profile', $user->id) }}">
                    <i class="fas fa-angle-left"></i>
                    <span>@lang('Back to profile')</span>
                </a>

                <div class="seller-info order-1 order-sm-2">
                    <img class="seller-info__thumb" src="{{ avatar(@$portfolio->user->image ? getFilePath('userProfile') . '/' . $portfolio->user->image : null) }}" alt="@lang('image')">
                    <div class="portfolio-details-user__content">
                        <a class="username" href="{{ route('gig.owner.profile', $user->id) }}">
                            <span>{{ $user->fullname }}'s</span> @lang('Profile')
                        </a>
                        <button class="contact-btn @if (auth()->user()) contactMeBtn @else loginNotify @endif" type="button" @disabled($user->id == auth()->id())>
                            <i class="fas fa-comment"></i>
                            <span>@lang('Contact seller')</span>
                        </button>
                    </div>
                </div>

                
                <div class="share flex-align gap-2 ms-auto gig-details-page-buttons order-3">
                    <div class="dropdown">
                        <span class="icon" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                            @lang('Share'): <i class="fas fa-share-alt"></i>
                        </span>
                        <div class="dropdown-menu share-action dropdown-menu-end">
                            <div class="copy-link input-group">
                                <span class="input-group-text fs-16 bg-transparent border-0"><i class="las la-link"></i></span>
                                <input class="form-control form--control copy-input fs-14" type="text" value="{{ url()->current() }}" aria-label="" disabled>
                                <span class="input-group-text flex-align copy-btn fs-14 " id="copyBtn" data-link="{{ url()->current() }}"><i class="far fa-copy"></i> @lang('Copy Link')</span>
                            </div>
                            <div class="social-list flex-align">
                                <a target="_blank" class="social-btn facebook flex-align" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"><i class="fab fa-facebook-f"></i> @lang('Facebook')</a>
                                <a target="_blank" class="social-btn linkedin flex-align" href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title=my share text&amp;summary=dit is de linkedin summary"><i class="fab fa-linkedin-in"></i>@lang('Linkedin')</a>
                                <a target="_blank" class="social-btn twitter flex-align" href="https://twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"><i class="fab fa-twitter"></i>@lang('Twitter')</a>
                                <a target="_blank" class="social-btn instagram flex-align" href="https://www.instagram.com/share?url={{ urlencode(url()->current()) }}"><i class="fab fa-instagram"></i>@lang('Instagram')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gy-4">
                @foreach ($portfolios as $portfolio)
                    <div class="col-xsm-6 col-sm-6 col-md-4">
                        <a class="portfolio-card" href="{{ route('gig.owner.portfolio.details', $portfolio->id) }}">
                            <div class="portfolio-card__content">
                                <h6 class="portfolio-card__title">{{ __(strLimit($portfolio->title, 40)) }}</h6>
                            </div>
                            <img class="portfolio-card__thumb" src="{{ getImage(getFilePath('userPortfolio') . '/' . $portfolio->image, getFileSize('userPortfolio')) }}" alt="@lang('image')">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- //contact-modal start// -->
    <div class="contact-modal d-none" id="contact-modal">
        <div class="contact-modal__header">
            <div class="contact-modal__seller-info">
                <img class="seller-img" id="modalImage" src="{{ avatar(@$portfolio->user->image ? getFilePath('userProfile') . '/' . $portfolio->user->image : null) }}" alt="@lang('image')">
                <h6 class="seller-name">{{ $portfolio->user->fullname }}</h6>

            </div>

            <button class="contact-modal__close-btn" type="button">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="contact-modal__body">
            <form class="form" id="messageForm" action="{{ route('user.conversation.message.to') }}" method="POST">
                @csrf
                <div class="form-group">
                    <textarea class="form--control" id="message" name="message" placeholder="@lang('Ask a question or share your project details')">{{ old('message') }}</textarea>
                    <input name="seller_id" type="hidden" value="{{ $portfolio->user_id }}">
                </div>

                <div class="py-3">
                    <p class="pre-msg-item">👋 @lang('Hey') <span class="seller__name">{{ $portfolio->user->fullname }}</span>, @lang('can you help me with') </p>
                    <p class="pre-msg-item"> @lang('Do you think you can deliver an order by') </p>
                    <p class="pre-msg-item"> @lang('Would it be possible to get a custom offer for ') </p>
                    <p class="pre-msg-item"> @lang('Can you send me some work samples? ') </p>
                </div>

                <div class="text-end">
                    <button class="btn btn--base message-send-btn" type="submit">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

   
    <div class="modal" id="loginNotifyModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between my-2">
                        <h5 class="mb-0">@lang('Login Alert!')</h5>
                        <button class="btn-close fs-20" data-bs-dismiss="modal" type="button"></button>
                    </div>

                    <p> @lang('Please login first for explore gigs!')</p>

                    <div class="text-end">
                        <button class="btn btn-dark btn--sm" data-bs-dismiss="modal" type="button">@lang('Cancel')</button>
                        <a class="btn btn--base btn--sm" href="{{ route('user.login') }}">@lang('Login')</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .custom-modal {
            width: 120%;
            position: fixed;
            bottom: 1px;
            right: 1px;
            margin: 1px;
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            // contact-Message//
            var modal = $('#contact-modal');
            $(document).on('click', '.contactMeBtn', function() {
                modal.removeClass('d-none');
                var message = modal.find('textarea[name="message"]').empty();
                var btn = modal.find('.message-send-btn');
                message.val('');
                btn.attr('disabled', true);
                message.on('input', function() {
                    if ($(this).val()) {
                        btn.removeAttr('disabled');
                    } else {
                        btn.attr('disabled', true);
                    }
                });
            });

            $('.message-send-btn, .contact-modal__close-btn').on('click', function() {
                modal.addClass('d-none');
            })

            $('.pre-msg-item').on('click', function() {
                modal.find('.message-send-btn').removeAttr('disabled');
                var clickedText = $(this).text();
                var textarea = modal.find('textarea[name="message"]');
                var currentText = textarea.val();
                if (currentText.length > 0) {
                    textarea.val(currentText + '\n' + '\n' + clickedText);
                } else {
                    textarea.val(clickedText);
                }
                $(this).hide();
            })

            modal.find('textarea[name="message"]').on('input', function() {
                var textarea = $(this);
                var currentText = textarea.val();
                if (currentText.trim() === '') {
                    $('.pre-msg-item').show();
                }
            });
            //End Message

            $(document).on('click', '.loginNotify', function() {
                var modal = $('#loginNotifyModal');
                modal.modal('show');
            });


        })(jQuery);
    </script>
@endpush
