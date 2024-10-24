@php
    $footerContent = getContent('footer.content', true);
@endphp
<footer class="py-4 bg--white">
    <div class="container">
        <div class="footer-content text-center">
            <a class="logo mb-3" href="{{ route('home') }}">
                <img src="{{ siteLogo(null, true) }}" alt="{{ __(gs()->site_name) }}">
            </a>
            <p class="footer-text mx-auto">{{ __($footerContent->data_values->content) }}</p>
            <ul class="footer-links d-flex flex-wrap gap-3 justify-content-center mt-3 mb-3">
                <li><a class="link-color" href="{{ route('home') }}">@lang('Home')</a></li>
                <li><a class="link-color" href="{{ route('gig.owner.profile',auth()->id()) }}">@lang('Profile')</a></li>
                <li><a class="link-color" href="{{ route('gig.index') }}">@lang('Gigs')</a></li>
                <li><a class="link-color" href="{{ route('contact') }}">@lang('Contact')</a></li>
            </ul>
            <p class="copyright-text"> &copy; @lang('Copyright')
                {{ date('Y') }} <a href="{{route('home')}}">{{ __(gs()->site_name) }}.</a>
                @lang('All Rights Reserved')</p>
        </div>
    </div>
</footer>
