@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="section py-120">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-8">
                    <div class="blog-post">
                        <img class="img-fluid blog-post__img"
                            src="{{ frontendImage('blog', @$blog->data_values->image, '1000x500') }}"
                            alt="@lang('image')" />
                        <div class="blog-post__body">
                            <h3>
                                {{ __($blog->data_values->title) }}
                            </h3>

                            <div class="blog-post__meta-wrapper mb-3">
                                <ul class="blog-post-meta">
                                    <li class="blog-post-meta__item">
                                        <i class="las la-calendar"></i>
                                        <span class="text">{{ showDateTime($blog->created_at) }}</span>
                                    </li>
                                </ul>

                                <ul class="social-list">
                                    <li class="social-list__item">
                                        <span class="social-list__text">@lang('Share Now'):</span>
                                    </li>
                                    <li class="social-list__item"><a class="social-list__link  facebook flex-center"
                                            target="_blank"
                                            href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"><i
                                                class="fab fa-facebook-f"></i></a>
                                    </li>
                                    <li class="social-list__item"><a class="social-list__link  twitter flex-center"
                                            target="_blank"href="https://twitter.com/intent/tweet?text= {{ __(strLimit($blog->data_values->title, 150)) }}&amp;url={{ urlencode(url()->current()) }}">
                                            <i class="fa-brands fa-x-twitter"></i></a>
                                    </li>
                                    <li class="social-list__item"><a class="social-list__link  linkedin flex-center"
                                            target="_blank"
                                            href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ __(strLimit($blog->data_values->title, 150)) }}&amp;summary={{ __(strLimit(strip_tags(@$blog->data_values->description_nic), 300)) }}">
                                            <i class="fab fa-linkedin-in"></i></a>
                                    </li>
                                </ul>
                            </div>

                            <div class="blog-post__content">
                                @php echo @$blog->data_values->description_nic @endphp
                            </div>
                        </div>
                    </div>
                    @push('fbComment')
                        @php echo loadExtension('fb-comment') @endphp
                    @endpush
                </div>

                <div class="col-lg-4">
                    <div class="blog-sidebar-wrapper">
                        <div class="blog-sidebar">
                            <h5 class="blog-sidebar__title"> @lang('Latest Blog') </h5>
                            @foreach ($latestBlogs as $blog)
                                <div class="latest-blog">
                                    <div class="latest-blog__thumb">
                                        <a href="{{ route('blog.details', $blog->slug) }}">
                                            <img class="fit-image"
                                                src="{{ frontendImage('blog', @$blog->data_values->image, '500x250', thumb: true) }}"
                                                alt="@lang('image')"></a>
                                    </div>
                                    <div class="latest-blog__content">
                                        <h6 class="latest-blog__title"><a
                                                href="{{ route('blog.details', $blog->slug) }}">{{ __(strLimit($blog->data_values->title, 70)) }}</a>
                                        </h6>
                                        <span class="latest-blog__date fs-13"> <i class="las la-calendar"></i>
                                            {{ showDateTime($blog->created_at, 'd F Y') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
