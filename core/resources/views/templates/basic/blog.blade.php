@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="container py-120">
        <div class="row g-4">
            @foreach ($blogs as $blog)
                <div class="col-sm-6 col-lg-4">
                    <div class="blog-post">
                        <a class="blog-post__img" href="{{ route('blog.details', @$blog->slug) }}">
                            <img class="blog-post__img-is" src="{{ frontendImage('blog', @$blog->data_values->image, '500x250', thumb:true) }}" alt="@lang('image')" >
                        </a>
                        <div class="blog-post__body">
                            <ul class="blog-post-meta mb-3">
                                <li class="blog-post-meta__item">
                                    <i class="las la-calendar text--base fs-20"></i>
                                    <span class="text">{{ showDateTime($blog->created_at) }}</span>
                                </li>
                            </ul>

                            <h5 class="mb-3">
                                <a class="blog-post__title" href="{{ route('blog.details', @$blog->slug) }}">
                                    {{ __(@$blog->data_values->title) }}
                                </a>
                            </h5>
                            <p class="blog-post__article mt-3">
                                @php echo __(strLimit(strip_tags(@$blog->data_values->description_nic), 85));@endphp
                            </p>
                            <div class="text-list flex-align mt-3">
                                <a href="{{ route('blog.details', @$blog->slug) }}" class="read-more-btn">@lang('Read More') <i class="las la-arrow-right"></i></a>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach

            @if ($blogs->hasPages())
                <div class="col-12">
                    <ul class="list list--row justify-content-center align-items-center t-mt-60">
                        {{ paginateLinks($blogs) }}
                    </ul>
                </div>
            @endif
        </div>
    </section>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
