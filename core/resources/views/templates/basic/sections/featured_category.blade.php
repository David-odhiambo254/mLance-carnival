@php
    $categoryContent = getContent('featured_category.content', true);
    $categories = App\Models\Category::active()
        ->featured()
        ->with([
            'gigs' => function ($q) {
                $q->approved()->withAvg('reviews', 'rating');
            },
        ])
        ->withCount([
            'gigs' => function ($q) {
                $q->approved();
            },
        ])
        ->get();
@endphp

@if ($categories->count())    
    <section class="popular-categories pb-120">
        <div class="container">
            <div class="section-heading">
                <h3 class="section-heading__title highlight" data-break="-1" data-length="1">{{ __($categoryContent->data_values->heading) }}</h3>
            </div>
            <div class="popular-categories__list category-slider">
                @foreach ($categories as $category)
                    <div class="popular-categories__item">
                        <a class="popular-categories__category" href="{{ route('gig.categories', $category->id) }}"></a>
                        <div class="popular-categories__item-icon">
                            <img src="{{ getImage(getFilePath('category') . '/' . $category->image, getFileSize('category')) }}" alt="@lang('image')">
                        </div>
                        <div class="popular-categories__item-content">
                            <h6 class="popular-categories__title">{{ __($category->name) }}</h6>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
