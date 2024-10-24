@php
    $content = getContent('blog.content', true);
    $blogs = getContent('blog.element', limit: 3);
@endphp

<section class="container pb-120">
    <div class="section-heading flex-between gap-3">
        <h3 class="section-heading__title highlight" data-break="-1" data-length="1">{{ __(@$content->data_values->heading) }}</h3>
        <a class="view-more-link btn btn-outline--base btn--sm" href="{{ route('blog') }}">
            <span>@lang('View more')</span>
            <i class="fas fa-long-arrow-alt-right"></i>
        </a>
    </div>
    <div class="row g-4 justify-content-center">
        @foreach ($blogs as $blog)
            <div class="col-sm-6 col-lg-4">
                <div class="blog-post">
                    <a class="blog-post__img" href="{{ route('blog.details', $blog->slug) }}">
                        <img class="blog-post__img-is"
                            src="{{ frontendImage('blog', @$blog->data_values->image, '500x250', thumb:true) }}"
                            alt="@lang('image')">
                    </a>
                    <div class="blog-post__body">
                        <ul class="blog-post-meta mb-3">
                            <li class="blog-post-meta__item">
                                <i class="las la-calendar text--base fs-20"></i>
                                <span class="text">{{ showDateTime($blog->created_at) }}</span>
                            </li>
                        </ul>

                        <h5 class="mb-3">
                            <a class="blog-post__title" href="{{ route('blog.details', $blog->slug) }}">
                                {{ __($blog->data_values->title) }}
                            </a>
                        </h5>
                        <p class="blog-post__article mt-3">
                            @php echo __(strLimit(strip_tags($blog->data_values->description_nic), 85));@endphp
                        </p>
                        <div class="text-list flex-align mt-3">
                            <a href="{{ route('blog.details', $blog->slug) }}"
                                class="read-more-btn">@lang('Read More') <i class="las la-arrow-right"></i></a>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
