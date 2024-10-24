@php
    $language = App\Models\Language::all();
    $selectedLang = $language->where('code', session('lang'))->first();
    $categories = App\Models\Category::active()
        ->with([
            'subcategories' => function ($query) {
                $query->active();
            },
        ])
        ->get();
@endphp

<header class="header @if (!request()->routeIs('home')) internal-page-header @endif" id="header">
    <div class="header-top">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand logo me-auto order-1" href="{{ route('home') }}">
                    <img src="{{ siteLogo(null, true) }}" alt="{{ __(gs()->site_name) }}">
                </a>
                @auth
                    <div class="dropdown dropdown--profile order-2 order-lg-4 me-4 ms-lg-4 me-lg-0">
                        <div class="dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="{{ avatar(auth()->user()->image ? getFilePath('userProfile') . '/' . auth()->user()->image : null) }}"
                                alt="@lang('image')">
                        </div>

                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-link" href="{{ route('user.home') }}">
                                    <span class="icon"><i class="las la-user-circle"></i></span>
                                    <span class="text">@lang('Dashboard')</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-link" href="{{ route('user.profile.setting') }}">
                                    <span class="icon"><i class="las la-user-circle"></i></span>
                                    <span class="text">@lang('My Profile')</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-link" href="{{ route('user.change.password') }}">
                                    <span class="icon"><i class="la la-lock"></i></span>
                                    <span class="text">@lang('Password')</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-link" href="{{ route('user.twofactor') }}">
                                    <span class="icon"> <i class="la la-key"></i></span>
                                    <span class="text">@lang('2FA Security')</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-link" href="{{ route('ticket.index') }}">
                                    <span class="icon"> <i class="la la-ticket-alt"></i></span>
                                    <span class="text">@lang('Support')</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-link" href="{{ route('user.logout') }}">
                                    <span class="icon"><i class="las la-sign-out-alt"></i></span>
                                    <span class="text"> @lang('Logout')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                @endauth
                <button class="navbar-toggler header-button order-3" data-bs-toggle="collapse"
                    data-bs-target="#navbarContent" type="button" aria-controls="navbarContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="fas la-bars"></i>
                </button>

                <div class="collapse navbar-collapse order-4 order-lg-2" id="navbarContent">
                    <div class="w-100 d-flex flex-column align-items-start flex-lg-row align-items-lg-center">
                        <ul class="navbar-nav nav-menu ms-auto align-items-lg-center">
                            <li class="nav-item">
                                <a class="nav-link {{ menuActive('home') }}"
                                    href="{{ route('home') }}">@lang('Home')</a>
                            </li>

                            @if (@$pages)
                                @foreach ($pages as $k => $data)
                                    <li class="nav-item">
                                        <a class="nav-link {{ menuActive('pages', null, $data->slug) }}"
                                            href="{{ route('pages', [$data->slug]) }}">{{ __($data->name) }}</a>
                                    </li>
                                @endforeach
                            @endif
                            <li class="nav-item">
                                <a class="nav-link {{ menuActive('blog') }}"
                                    href="{{ route('blog') }}">@lang('Blogs')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ menuActive('contact') }}"
                                    href="{{ route('contact') }}">@lang('Contact')</a>
                            </li>
                            <li class="nav-item d-lg-none">
                                <a class="nav-link" data-bs-toggle="collapse" data-bs-target="#catagoriesContent"
                                    href="#" role="button">
                                    @lang('Categories')
                                    <span class="nav-item__icon"><i class="las la-angle-down"></i></span>
                                </a>
                                <div class="collapse" id="catagoriesContent">
                                    @foreach ($categories as $category)
                                        <div class="dropdown">
                                            <a class="nav-link" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                href="{{ route('gig.categories', $category->id) }}"
                                                aria-expanded="false">
                                                {{ __($category->name) }}
                                                @if ($category->subcategories->count())
                                                    <span class="nav-item__icon"><i
                                                            class="las la-angle-down"></i></span>
                                                @endif
                                            </a>
                                            <ul class="dropdown-menu">
                                                @foreach ($category->subcategories as $subcategory)
                                                    <li class="dropdown-menu__list"><a
                                                            class="dropdown-item dropdown-menu__link"
                                                            href="{{ route('gig.subcategories', $subcategory->id) }}">{{ __($subcategory->name) }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </li>
                        </ul>
                        <div class="navbar-action">
                            @if (gs()->multi_language)
                                <div class="dropdown-lang dropdown mt-0 d-block">
                                    <a href="#" class="language-btn dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <img class="flag"
                                            src="{{ getImage(getFilePath('language') . '/' . @$selectedLang->image, getFileSize('language')) }}"
                                            alt="us">
                                        <span class="language-text">{{ @$selectedLang->name }}</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        @foreach ($language as $lang)
                                            <li><a href="{{ route('lang', $lang->code) }}"><img class="flag"
                                                        src="{{ getImage(getFilePath('language') . '/' . @$lang->image, getFileSize('language')) }}"
                                                        alt="@lang('image')">
                                                    {{ @$lang->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @guest
                                <div class="navbar-buttons">
                                    <a class="btn pill btn--base" href="{{ route('user.login') }}">@lang('Sign In')</a>
                                    <a class="btn pill btn-outline--base"
                                        href="{{ route('user.register') }}">@lang('Sign Up')</a>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <div class="header-bottom d-none d-lg-block">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light navbar--categories">
                <button class="navbar__prev" type="button">
                    <i class="las la-angle-left"></i>
                </button>
                <ul class="navbar-nav nav-menu align-items-lg-center primary-nav">
                    @foreach ($categories as $category)
                        @php
                            $activeClass = '';
                            if (request()->routeIs('gig.categories') && request()->segment(3) == $category->id) {
                                $activeClass = 'active';
                            } else {
                                foreach ($category->subcategories as $subcategory) {
                                    if (
                                        request()->routeIs('gig.subcategories') &&
                                        request()->segment(3) == $subcategory->id
                                    ) {
                                        $activeClass = 'active';
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <li class="nav-item dropdown {{ $activeClass }}">
                            <a class="nav-link" href="{{ route('gig.categories', $category->id) }}"
                                aria-expanded="false">{{ __($category->name) }}</a>
                            @if ($category->subcategories->count())
                                <ul class="dropdown-menu">
                                    @foreach ($category->subcategories as $subcategory)
                                        <li class="dropdown-menu__list">
                                            <a class="dropdown-item dropdown-menu__link {{ request()->routeIs('gig.subcategories') && request()->segment(3) == $subcategory->id ? 'menuActive' : '' }}"
                                                href="{{ route('gig.subcategories', $subcategory->id) }}">{{ __($subcategory->name) }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
                <button class="navbar__next" type="button">
                    <i class="las la-angle-right"></i>
                </button>
            </nav>
        </div>
    </div>


</header>


@push('script')
    <script>
        "use strict";
        (function($) {
            let navbarWrapper = $('.navbar--categories .navbar__wrapper');
            let navbarMenu = $('.navbar--categories .nav-menu');
            let navbarMenuItems = navbarMenu.find('.nav-item');
            let navbarMenuFirstItem = navbarMenu.find('.nav-item:first-child')[0];
            let navbarMenuLastItem = navbarMenu.find('.nav-item:last-child')[0];
            let navbarMenuPrev = $('.navbar__prev');
            let navbarMenuNext = $('.navbar__next');
            let count = 0;
            let observerOptions = {
                root: $('.navbar--categories')[0],
                rootMargin: "1px",
                threshold: 1
            }

            function moveTrack(action = '') {

                //store the length of button in track
                let totalItems = navbarMenuItems.length;

                //generate a avg width for track
                let navbarMenuAvgWidth = Math.ceil(navbarMenu[0].clientWidth / totalItems);

                //increment || decrement = next || prev
                count = action == 'next' ? count + 1 : count - 1;

                //change the css transform value
                navbarMenu.css('transform', `translateX(-${(navbarMenuAvgWidth * count)}px)`);
            }

            function setIntersectionObserver(element, ctrlBtn) {

                let observer = new IntersectionObserver((entries) => {

                    entries.forEach(entry => {

                        if (entry.intersectionRatio >= 1) {

                            //disappear the prev button when the first button appears entirely
                            $(ctrlBtn).removeClass('d-block');

                        } else {

                            //appear the prev button when the first button starts disapearing
                            $(ctrlBtn).addClass('d-block');
                        }

                    });

                }, observerOptions);

                return observer.observe(element);
            }

            setIntersectionObserver(navbarMenuFirstItem, navbarMenuPrev[0]);
            setIntersectionObserver(navbarMenuLastItem, navbarMenuNext[0]);

            $(navbarMenuPrev).on('click', function() {
                moveTrack()
            })

            $(navbarMenuNext).on('click', function() {
                moveTrack('next')
            })

        })(jQuery);
    </script>
@endpush
