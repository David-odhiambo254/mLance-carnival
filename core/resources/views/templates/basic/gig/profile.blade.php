@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="profile-page py-60">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-5 col-xl-4">
                    <aside class="profile-sidebar">
                        <div class="profile-panel">
                            <div class="profile-panel__block">
                                <div class="profile-info">
                                    <div class="share text-end">
                                        <div class="dropdown">
                                            <span class="icon" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                              <i class="fas fa-share-alt"></i>
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
                                    <div class="avatar avatar--mega avatar--online">
                                        <img class="avatar__img" src="{{ avatar(@$user->image ? getFilePath('userProfile') . '/' . $user->image : null) }}" alt="@lang('image')">
                                    </div>
                                    <h6 class="profile-info__name">{{ __($user->fullname) }}</h6>
                                    @if ($user->tagline)
                                        <span class="profile-info__tagline">{{ __(@$user->tagline) }}</span>
                                    @endif

                                    <ul class="rating-list text--warning gap-0">
                                        @php echo avgRating($avgRating); @endphp
                                        <li class="rating-list__item"><span class="rating-list__text">&nbsp; {{ showAmount($avgRating) }}&nbsp; ({{ $reviewCount }})</span></li>
                                    </ul>
                                </div>
                                <div class="profile-panel__buttons">
                                    <button class="btn btn--base w-100 @if (auth()->user()) contactMeBtn @else loginNotify @endif" type="button" @disabled($user->id == auth()->id())>@lang('Contact Me') </button>
                                </div>
                            </div>

                            <div class="profile-panel__block">
                                <ul class="profile-meta">
                                    <li class="profile-meta__item">
                                        <div class="profile-meta__label">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>@lang('From')</span>
                                        </div>
                                        <span class="profile-meta__value">{{ __($user->country_name) }}</span>
                                    </li>
                                    <li class="profile-meta__item">
                                        <div class="profile-meta__label">
                                            <i class="fas fa-user"></i>
                                            <span>@lang('Member Since')</span>
                                        </div>
                                        <span class="profile-meta__value">{{ showDateTime($user->created_at, 'M Y') }}</span>
                                    </li>
                                    @if ($lastDelivery)
                                        <li class="profile-meta__item">
                                            <div class="profile-meta__label">
                                                <i class="fas fa-paper-plane"></i>
                                                <span>@lang('Last Delivery')</span>
                                            </div>
                                            <span class="profile-meta__value">{{ diffForHumans(@$lastDelivery->updated_at) }}</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        @if ($totalPortfolioCount)
                            <div class="profile-panel">
                                <h6 class="profile-panel__title">@lang('My Portfolio')</h6>
                                <ul class="profile-panel__portfolio">
                                    @foreach ($portfolios as $key => $portfolio)
                                        <li class="profile-panel__portfolio-item sl-{{ $key }}">
                                            <a class="profile-portfolio" href="{{ route('gig.owner.portfolio.details', $portfolio->id) }}">
                                                <img class="profile-portfolio__thumb" src="{{ getImage(getFilePath('userPortfolio') . '/thumb_' . $portfolio->image, getFileThumb('userPortfolio')) }}" alt="@lang('image')">
                                                <div class="profile-portfolio__content">
                                                    <h6 class="title">{{ strLimit(__($portfolio->title), 30) }}</h6>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                <a class="profile-panel__more-link" href="{{ route('gig.owner.portfolio', $user->id) }}">
                                    <span>@lang('See Projects') ({{ $totalPortfolioCount }})</span>
                                    <i class="fas fa-long-arrow-alt-right"></i>
                                </a>
                            </div>
                        @endif

                        <div class="profile-panel">
                            @if ($user->description)
                                <div class="profile-panel__block">
                                    <h6 class="profile-panel__title">@lang('Description')</h6>
                                    <p class="profile-panel__desc">
                                        {{ __(@$user->description) }}
                                    </p>
                                </div>
                            @else
                                @include($activeTemplate . 'partials.empty', ['message' => 'Description not found!'])
                            @endif
                            <div class="profile-panel__block">
                                <h6 class="profile-panel__title">@lang('Educations / Certifications')</h6>
                                <ul class="profile-panel__edu">
                                    @forelse ($user->educations ?? [] as $education)
                                        <li class="profile-panel__edu-item">
                                            <span>{{ $education->title }}</span>
                                            <p>{{ $education->institute }} - {{ $education->year }}</p>
                                        </li>
                                    @empty
                                        @include($activeTemplate . 'partials.empty', ['message' => 'Education Not found!'])
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>

                <div class="col-lg-7 col-xl-8">
                    <div class="profile-content">
                        <h5 class="profile-content__title">{{ $user->firstname }}'s @lang('Gigs')</h5>
                        <div class="row gy-4">
                            @foreach ($gigs as $gig)
                                @include($activeTemplate . 'partials.gig', ['cols' => 'col-xsm-6 col-sm-6 col-xl-4'])
                            @endforeach
                        </div>
                        @if ($gigs->hasPages())
                            <ul class="pagination">
                                {{ $gigs->links() }}
                            </ul>
                        @endif
                        <div class="review-section">
                            <div class="review-section__header">
                                <h4 class="block-title">@lang('Reviews')</h4>
                                <h6 class="review-section__title"><span class="underline">{{ @$reviewCount }}</span> @lang('Reviews for this seller')</h6>
                            </div>

                            <div class="review-section__body">
                                <ul class="review-list">
                                    @include($activeTemplate . 'gig.review', ['reviews' => $reviews])
                                </ul>
                            </div>
                            @if ($reviewCount > 10)
                                <div class="review-section__footer">
                                    <button class="review-section__link-show" type="button">
                                        <i class="las la-angle-double-right"></i>
                                        <span>@lang('Show More')</span>
                                    </button>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="contact-modal d-none" id="contact-modal">
        <div class="contact-modal__header">
            <div class="contact-modal__seller-info">
                <img class="seller-img" id="modalImage" src="{{ avatar(@$user->image ? getFilePath('userProfile') . '/' . $user->image : null) }}" alt="@lang('image')">
                <h6 class="seller-name">{{ $user->fullname }}</h6>
            </div>

            <button class="contact-modal__close-btn" type="button">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="contact-modal__body">
            <form class="form" id="messageForm" action="{{ route('user.conversation.message.to') }}" method="POST">
                @csrf
                <div class="form-group">
                    <textarea class="form--control" id="message" name="message" placeholder="@lang('Ask a question or share your project details')"></textarea>
                    <input name="seller_id" type="hidden" value="{{ $user->id }}">
                </div>
                <div class="py-3">
                    <p class="pre-msg-item">👋 @lang('Hey') <span class="seller__name">{{ $user->fullname }}</span>, @lang('can you help me with') </p>
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
                        <h5 class="mb-0">@lang('Login Notify!')</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
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


            //more-my-gig-reviews//
            $('.review-section__link-show').on('click', function() {
                let skip = $('.review-list-item').length;
                $.ajax({
                    url: '{{ route('gig.my.reviews', $user->id) }}',
                    type: 'GET',
                    data: {
                        length: skip,
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.count > 0) {
                                $('.review-list').append(response.html);
                            } else {
                                $('.review-section__link-show').hide();
                            }
                        }
                    }
                });
            });

        })(jQuery);
    </script>
@endpush
