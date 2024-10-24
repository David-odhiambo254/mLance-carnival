@php
    $footerContent    = getContent('footer.content', true);
    $subscribeContent = getContent('subscribe.content', true);
    $iconElement      = getContent('social_icon.element', false, null, true);
    $policyPages      = getContent('policy_pages.element', false, null, true);
    $contactElement   = getContent('contact_us.element', false, 3, true);
    $categories       = App\Models\Category::active()
        ->featured()
        ->orderBy('id', 'DESC')
        ->limit(3)
        ->get();
@endphp

<footer class="footer pt-60">
    <div class="container">
        <div class="row gy-4 gy-sm-5">
            <div class="col-sm-6 col-lg-3 order-1 order-lg-1">
                <div class="footer-item">
                    <a class="footer-item__logo" href="{{ route('home') }}">
                        <img src="{{ siteLogo(null, true) }}" alt="{{ __(gs()->site_name) }}">
                    </a>
                    <p class="footer-item__description">
                        {{ __($footerContent->data_values->content) }}
                    </p>
                </div>
            </div>

            <div class="col-lg-6 order-3 order-lg-2">
                <div class="footer-item-wrapper">
                    <div class="footer-item">
                        <h6 class="footer-item__title">@lang('Categories')</h6>
                        <ul class="footer-menu">
                            @foreach ($categories as $category)
                                <li class="footer-menu__item"><a class="footer-menu__link" href="{{ route('gig.categories', $category->id) }}">{{ __($category->name) }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="footer-item">
                        <h6 class="footer-item__title">@lang('Quick Links')</h6>
                        <ul class="footer-menu">
                            <li class="footer-menu__item"><a class="footer-menu__link" href="{{ route('user.login') }}">@lang('Sign in')</a></li>
                            <li class="footer-menu__item"><a class="footer-menu__link" href="{{ route('user.register') }}">@lang('Sign Up')</a></li>
                            <li class="footer-menu__item"><a class="footer-menu__link" href="{{ route('blog') }}">@lang('Blogs')</a></li>
                        </ul>
                    </div>

                    <div class="footer-item">
                        <h6 class="footer-item__title">@lang('Important Links')</h6>
                        <ul class="footer-menu">
                            @foreach ($policyPages as $link)
                                <li class="footer-menu__item"><a class="footer-menu__link" href="{{ route('policy.pages', $link->slug) }}">{{ __($link->data_values->title) }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 order-2 order-lg-3">
                <div class="footer-item">
                    <h6 class="footer-item__title"> {{ __($subscribeContent->data_values->heading) }}</h6>
                    <p class="footer-item__description">
                        {{ __($subscribeContent->data_values->subheading) }}
                    </p>
                    <form class="subscribe-form" id="subscribeForm">
                        @csrf
                        <input class="form--control" id="subscriber" name="email" type="email" autocomplete="off" placeholder="@lang('Enter email address')" required>
                        <button class="btn btn--sm btn--base" type="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="copyright-text"> &copy; @lang('Copyright')
                {{ date('Y') }} <a href="{{route('home')}}">{{ __(gs()->site_name) }}.</a>
                @lang('All Rights Reserved')</p>
                <div class="">
                    <ul class="social-list">
                        @foreach ($iconElement as $icon)
                            <li class="social-list__item">
                                <a class="social-list__link flex-center" target="_blank" href="{{ @$icon->data_values->url }}">
                                    @php echo @$icon->data_values->social_icon; @endphp
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
        </div>
    </div>
</footer>

@push('script')
    <script>
        "use strict";
        (function($) {
            var form = $("#subscribeForm");
            form.on('submit', function(e) {
                e.preventDefault();
                var data = form.serialize();
                $.ajax({
                    url: `{{ route('subscribe') }}`,
                    method: 'post',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            form.find('input[name=email]').val('');
                            form.find('button[type=submit]').attr('disabled', false);
                            notify('success', response.message);
                        } else {
                            notify('error', response.error);
                        }
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
