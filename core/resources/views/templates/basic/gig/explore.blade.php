@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="gig-details-page py-60">
        <div class="container">
            <div class="gig-details-page-top">
                <div class="breadcrumb">
                    <ul class="breadcrumb__list">
                        <li class="breadcrumb__item">
                            <a class="breadcrumb__link" href="{{ route('home') }}"><i class="las la-home fs-18"></i></a>
                        </li>
                        <li class="breadcrumb__item">
                            <a class="breadcrumb__link"
                                href="{{ route('gig.categories', $gig->category_id) }}">{{ __($gig->category->name) }}</a>
                        </li>
                        @if ($gig->subcategory_id)
                            <li class="breadcrumb__item">
                                <a class="breadcrumb__text"
                                    href="{{ route('gig.subcategories', $gig->subcategory_id) }}">{{ __($gig->subcategory->name) }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="share flex-align gap-2 ms-auto">
                    <div class="dropdown">
                        <span class="icon" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                            @lang('Share'): <i class="fas fa-share-alt"></i>
                        </span>
                        <div class="dropdown-menu share-action dropdown-menu-end">
                            <div class="copy-link input-group">
                                <span class="input-group-text fs-16 bg-transparent border-0"><i
                                        class="las la-link"></i></span>
                                <input class="form-control form--control copy-input fs-14" type="text"
                                    value="{{ url()->current() }}" aria-label="" disabled>
                                <span class="input-group-text flex-align copy-btn fs-14 " id="copyBtn"
                                    data-link="{{ url()->current() }}"><i class="far fa-copy"></i> @lang('Copy Link')</span>
                            </div>
                            <div class="social-list flex-align">
                                <a target="_blank" class="social-btn facebook flex-align"
                                    href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"><i
                                        class="fab fa-facebook-f"></i> @lang('Facebook')</a>
                                <a target="_blank" class="social-btn linkedin flex-align"
                                    href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title=my share text&amp;summary=dit is de linkedin summary"><i
                                        class="fab fa-linkedin-in"></i>@lang('Linkedin')</a>
                                <a target="_blank" class="social-btn twitter flex-align"
                                    href="https://twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"><i
                                        class="fab fa-twitter"></i>@lang('Twitter')</a>
                                <a target="_blank" class="social-btn instagram flex-align"
                                    href="https://www.instagram.com/share?url={{ urlencode(url()->current()) }}"><i
                                        class="fab fa-instagram"></i>@lang('Instagram')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gy-4">
                <div class="col-lg-7 col-xl-8">
                    <div class="gig-details">
                        <h1 class="gig-details__title mb-30">
                            {{ __($gig->title) }}
                        </h1>
                        <div class="gig-details__user mb-30">
                            <img class="gig-details__user-img"
                                src="{{ avatar(@$gig->user->image ? getFilePath('userProfile') . '/' . $gig->user->image : null) }}"
                                alt="@lang('image')">

                            <div class="gig-details__user-wrapper">
                                <div class="gig-details__user-name">
                                    <a
                                        href="{{ route('gig.owner.profile', $gig->user_id) }}">{{ __($gig->user->fullname) }}</a>
                                </div>

                                <ul class="gig-details__user-meta">
                                    <li class="gig-details__user-meta-item">
                                        <ul class="rating-list gap-0 text--warning">
                                            @php echo avgRating($gig->avg_rating); @endphp
                                            <li class="rating-list__item"><span
                                                    class="rating-list__text">{{ $gig->avg_rating }}</span></li>
                                            <li class="rating-list__item"><span
                                                    class="rating-list__text">({{ @$gig->reviews_count }})</span></li>
                                        </ul>
                                    </li>

                                    <li class="gig-details__user-meta-item">
                                        <span class="gig-details__queue-text">{{ getAmount($queueOrders) }}
                                            @lang('Orders in Queue')</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="gig-details__slider-wrapper mb-60">
                            <div class="swiper gig-details__slider1">
                                <div class="swiper-wrapper">
                                    @foreach ($gig->images as $image)
                                        <div class="swiper-slide">
                                            <img class="swiper-slide__img"
                                                src="{{ getImage(getFilePath('gig') . '/' . $image->name, getFileSize('gig')) }}"
                                                alt="@lang('image')" />
                                        </div>
                                    @endforeach
                                </div>
                                <button class="swiper-button-next" type="button"><i
                                        class="fas fa-angle-right"></i></button>
                                <button class="swiper-button-prev" type="button"><i class="fas fa-angle-left"></i></button>
                            </div>
                            <div class="swiper gig-details__slider2" thumbsSlider="">
                                <div class="swiper-wrapper">
                                    @foreach ($gig->images as $image)
                                        <div class="swiper-slide">
                                            <img class="swiper-slide__img"
                                                src="{{ getImage(getFilePath('gig') . '/' . $image->name, getFileSize('gig')) }}"
                                                alt="@lang('image')" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="d-lg-none mb-60">
                            @include($activeTemplate . 'gig.pricing', ['device' => 'mobile'])
                        </div>

                        <ul class="nav nav-pills mb-4 gig-details-content gap-2" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-about-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-about" type="button" role="tab"
                                    aria-controls="pills-about" aria-selected="true">@lang('About This Gig')</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-requirement-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-requirement" type="button" role="tab"
                                    aria-controls="pills-requirement" aria-selected="false">@lang('Requirement')</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-package-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-package" type="button" role="tab"
                                    aria-controls="pills-package" aria-selected="false">@lang('Packages')</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-faq-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-faq" type="button" role="tab" aria-controls="pills-faq"
                                    aria-selected="false">@lang('FAQ')</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-about" role="tabpanel"
                                aria-labelledby="pills-about-tab">
                                <div class="gig-details__content mb-60">
                                    <h5 class="title">@lang('About This Gig')</h5>
                                    <div class="gig-details__content-wrapper">
                                        <div class="gig-details__desc">
                                            @php echo $gig->description; @endphp
                                        </div>
                                    </div>
                                    <button class="gig-details__collapse-btn" type="button">
                                        <i class="fas fa-plus"></i>
                                        <span>@lang('Show more')</span>
                                    </button>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-requirement" role="tabpanel"
                                aria-labelledby="pills-requirement-tab">
                                @if ($gig->requirement)
                                    <div class="gig-details__content gig-details__requirement mb-60 border p-3">
                                        <h5 class="title">@lang('Requirements This Gig')</h5>
                                        <div class="gig-details__content-wrapper">
                                            <div class="gig-details__desc">
                                                @php echo $gig->requirement; @endphp
                                            </div>
                                        </div>
                                        <button class="gig-details__collapse-btn" type="button">
                                            <i class="fas fa-plus"></i>
                                            <span>@lang('Show more')</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="pills-package" role="tabpanel"
                                aria-labelledby="pills-package-tab">
                                <div class="gig-details__packages-table mb-60" id="compare-packages">
                                    <h4 class="block-title">@lang('Compare Packages')</h4>
                                    <div class="table-responsive">
                                        <table class="table table--packages">
                                            <thead>
                                                <tr>
                                                    <th class="package-row-label">@lang('Package')</th>
                                                    @php
                                                        $array = [];
                                                    @endphp
                                                    @foreach ($packages as $package)
                                                        @php
                                                            $pricing = @$package->gigPricing($gig->id);
                                                            foreach ($pricing->pricing_data as $key => $value) {
                                                                $array[$key][$pricing->id] = $value;
                                                            }
                                                        @endphp
                                                        <th class="package-col">
                                                            <span class="price">
                                                                {{ gs('cur_sym') . showAmount($pricing->price) }}
                                                            </span>
                                                            <span class="type">{{ __($package->name) }}</span>
                                                            <span class="title">{{ __($pricing->name) }}</span>
                                                            <span
                                                                class="description">{{ __($pricing->description) }}</span>
                                                        </th>
                                                    @endforeach
                                            </thead>
                                            <tbody>
                                                @foreach ($array as $key => $item)
                                                    <tr>
                                                        <td class="package-row-label">{{ keyToTitle($key) }}</td>
                                                        @foreach ($item as $i)
                                                            <td>
                                                                @if (getType($i) == 'array')
                                                                    {{ implode(',', $i) }}
                                                                @else
                                                                    {{ __($i) }}
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                                <tr class="select-package">
                                                    <td class="package-row-label">@lang('Total')</td>
                                                    @foreach ($packages as $package)
                                                        @php
                                                            $pricing = @$package->gigPricing($gig->id);
                                                        @endphp
                                                        <td>
                                                            <span
                                                                class="total-price">{{ gs('cur_sym') . showAmount($pricing->price) }}
                                                                {{ __(gs('cur_text')) }}</span>
                                                            <button
                                                                class="w-100 mt-2 btn btn--sm btn--base @if (auth()->user()) orderBtn @else loginNotify @endif btn--base"
                                                                data-action="{{ route('user.order.process', [$gig->id, $pricing->id]) }}"
                                                                type="button" @disabled($gig->user_id == auth()->id())>
                                                                @lang('Request To Order')</button>
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane fade" id="pills-faq" role="tabpanel" aria-labelledby="pills-faq-tab">
                                <div class="gig-details__faq mb-60">
                                    <h4 class="block-title">@lang('FAQ')</h4>
                                    <div class="accordion custom--accordion" id="gig-details-faq">
                                        @foreach ($gig->faqs->question as $key => $faq)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                                        data-bs-target="#gig-details-faq-item-{{ $key + 1 }}"
                                                        type="button" aria-expanded="false">
                                                        {{ __($gig->faqs->question[$key]) }}
                                                    </button>
                                                </h2>
                                                <div class="accordion-collapse collapse"
                                                    id="gig-details-faq-item-{{ $key + 1 }}"
                                                    data-bs-parent="#gig-details-faq">
                                                    <div class="accordion-body">
                                                        {{ __($gig->faqs->answer[$key]) }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>




                        <div class="gig-details__discover mb-60">
                            <h4 class="block-title">@lang('Discover More')</h4>
                            <div class="swiper gig-details__discover-slider">
                                <div class="swiper-wrapper">
                                    @forelse ($relatedGigs as $relative)
                                        <div class="swiper-slide">
                                            @include($activeTemplate . 'partials.gig', [
                                                'gig' => $relative,
                                            ])
                                        </div>
                                    @empty
                                        @lang('Relevent gig not found!')
                                    @endforelse
                                </div>
                                <button class="swiper-button-next" type="button"><i
                                        class="fas fa-angle-right"></i></button>
                                <button class="swiper-button-prev" type="button"><i
                                        class="fas fa-angle-left"></i></button>
                            </div>
                        </div>



                        <div class="gig-details__reviews mb-60">
                            <div class="review-section">
                                <div class="review-section__header">
                                    <h4 class="block-title mb-0">@lang('Reviews')</h4>
                                    <h6 class="review-section__title">@lang('Total') <span
                                            class="underline">{{ @$gig->reviews_count }}</span>
                                        @lang('reviews')</h6>
                                </div>

                                <div class="review-section__body">
                                    <ul class="review-list">
                                        @include($activeTemplate . 'gig.review', ['reviews' => $reviews])
                                    </ul>
                                </div>
                                @if ($gig->reviews_count > 10)
                                    <div class="review-section__footer">
                                        <button class="review-section__link-show" type="button">
                                            <i class="las la-angle-double-right"></i>
                                            <span>@lang('Show more reviews')</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($gig->tags)
                            <div class="gig-details__tags">
                                <h4 class="block-title">@lang('Related Tags')</h4>
                                <ul class="tag-list">
                                    @foreach ($gig->tags as $tag)
                                        <li class="tag-list__item"><a class="tag-list__link"
                                                href="{{ route('gig.index') }}?search={{ $tag }}">{{ __($tag) }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-5 col-xl-4 d-none d-lg-block">
                    <aside class="gig-details-sidebar">
                        @include($activeTemplate . 'gig.pricing', ['device' => 'web'])

                        <div class="freelancer-sidebar-card position-relative">
                            <div class="job-type-badge">
                                <p class="badge badge--success">@lang('Top Seller')</p>
                            </div>
                            <div
                                class="freelancer-sidebar-card-header d-flex flex-column justify-content-center align-items-center py-4">
                                <div class="thumb">
                                    <img src="{{ avatar(@$gig->user->image ? getFilePath('userProfile') . '/' . $gig->user->image : null) }}"
                                        class="freelancer-avatar mb-4" alt="">
                                </div>
                                <h4 class="fw-bold freelancer-name mb-2">
                                    {{ __(@$gig->user->fullname) }}
                                </h4>
                                <p class="text-dark-200 mb-1">{{ __(@$gig->user->tagline) }}</p>
                                <p>
                                    <span><i class="las la-star text--warning"></i></span>
                                    <span class="frelancer-title">{{ showAmount($avgRating) }} </span>
                                    <span class="text-dark-200"> ({{ $reviewCount }} @lang('Reviews'))</span>
                                </p>
                            </div>
                            <div class="d-flex gap-4 justify-content-between sidebar-rate-card p-4">
                                <div>
                                    <p class="frelancer-title">@lang('Location')</p>
                                    <p class="text-dark-200">{{ __(@$gig->user->address->city) }}</p>
                                </div>
                                <div>
                                    <p class="frelancer-title">{{ __(str()->plural('Gig', $totalGig)) }}</p>
                                    <p class="text-dark-200">{{ $totalGig }}</p>
                                </div>
                                <div>
                                    <p class="frelancer-title">{{ __(str()->plural('Project', $totalProject)) }}</p>
                                    <p class="text-dark-200">{{ $totalProject }}</p>
                                </div>
                            </div>
                            <div class="d-grid">
                                <a href="{{ route('gig.owner.profile', $gig->user_id) }}" class="btn btn--base w-100">
                                    @lang('Contact Me')
                                    <i class="las la-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    <div class="contact-modal d-none" id="contact-modal">
        <div class="contact-modal__header">
            <div class="contact-modal__seller-info">
                <img class="seller-img" id="modalImage"
                    src="{{ avatar(@$gig->user->image ? getFilePath('userProfile') . '/' . $gig->user->image : null) }}"
                    alt="@lang('image')">
                <h6 class="seller-name">{{ $gig->user->fullname }}</h6>
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
                    <input name="seller_id" type="hidden" value="{{ $gig->user_id }}">
                    <input name="gig_id" type="hidden" value="{{ @$gig->id }}">
                </div>

                <div class="py-3">
                    <p class="pre-msg-item">👋 @lang('Hey') <span
                            class="seller__name">{{ $gig->user->fullname }}</span>, @lang('can you help me with') </p>
                    <p class="pre-msg-item"> @lang('Do you think you can deliver an order by')</p>
                    <p class="pre-msg-item"> @lang('Would it be possible to get a custom offer for ')</p>
                    <p class="pre-msg-item"> @lang('Can you send me some work samples? ')</p>
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

                    <p> @lang('Please login first for explore gig!')</p>

                    <div class="text-end">
                        <button class="btn btn-dark btn--sm" data-bs-dismiss="modal"
                            type="button">@lang('Cancel')</button>
                        <a class="btn btn--base btn--sm" href="{{ route('user.login') }}">@lang('Login')</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="orderModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <form method="POST">
                        @csrf
                        <div class="d-flex justify-content-between my-2">
                            <h5 class="mb-0">@lang('Confirm Your Order!')</h5>
                            <button class="btn-close fs-20" data-bs-dismiss="modal" type="button"></button>
                        </div>

                        <div class="form-group">
                            <label class="form-label"> @lang('Order Quote')</label>
                            <textarea class="from-control form--control" name="quotes" required placeholder="@lang('Enter order quotes.')" rows="5"
                                cols="55"></textarea>
                        </div>
                        <p>@lang('Are you sure to process this order?')</p>
                        <div class="text-end">
                            <button class="btn btn--base btn--sm confirmation" type="submit" disabled>
                                @lang('Order Now')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('style')
    <style>
        .form-control:focus {
            box-shadow: none !important;
        }

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

            $(document).on('click', '.orderBtn', function() {
                var modal = $('#orderModal');
                let data = $(this).data();
                var action = data.action;
                modal.find('form').attr('action', `${data.action}`);
                var quote = modal.find('textarea[name="quotes"]').empty();
                quote.on('input', function() {
                    if ($(this).val()) {
                        $('.confirmation').removeAttr('disabled');
                    }
                })
                modal.modal('show');
                var btn = modal.find('.confirmation');
                quote.val('');
                quote.on('input', function() {
                    if ($(this).val()) {
                        btn.removeAttr('disabled');
                    } else {
                        btn.attr('disabled', true);
                    }
                });
            });

            $('.confirmation').on('click', function() {
                var btnAfterSubmit = `<div class="spinner-border"></div>`;
                var btn = $(this);
                btn.html(btnAfterSubmit);
            });


            updateDataAction();

            $(".gig-details-tab__body").on("shown.bs.tab", ".nav-item", function(event) {
                var tab = $(event.target);
                var priceId = tab.closest('li').data("price-id");
                updateDataAction(priceId);
            });

            function updateDataAction(priceId = null) {
                priceId = priceId || $('.gig-details-tab__body').find('ul .nav-item').first().data('price-id');
                var action = "{{ route('user.order.process', [':gigId', ':pricingId']) }}";
                $(".dynamic-action-btn").attr('data-action', action.replace(':gigId', "{{ $gig->id }}").replace(
                    ':pricingId', priceId));
            }

            $('.review-section__link-show').on('click', function() {
                var skip = $('.review-list-item').length;
                $.ajax({
                    url: '{{ route('gig.reviews', $gig->id) }}',
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

            $('.compare-packages-link').on('click', function() {
                $('#pills-package-tab').click();
            });

        })(jQuery);
    </script>
@endpush
