@if (@$subcategory || @$category)
    <div class="sub-cat-details__header-top">
        <div class="breadcrumb">
            <ul class="breadcrumb__list">
                <li class="breadcrumb__item">
                    <a class="breadcrumb__link" href="{{ route('gig.index') }}"><i class="las la-home fs-18"></i></a>
                </li>
                <li class="breadcrumb__item">
                    @if (@$category ?? [])
                        <a class="breadcrumb__link"
                            href="{{ route('gig.categories', $category->id) }}">{{ $category->name }}</a>
                    @else
                        <a class="breadcrumb__link"
                            href="{{ route('gig.categories', $subcategory->category->id) }}">{{ $subcategory->category->name }}</a>
                    @endif
                </li>
                @if (@$subcategory)
                    <li class="breadcrumb__item">
                        <span class="breadcrumb__text">{{ $subcategory->name }}</span>
                    </li>
                @endif
            </ul>
        </div>
        <div class="section-heading">
            @if (@$subcategory)
                <h2 class="section-heading__title">{{ $subcategory->name }}</h2>
            @else
                <h2 class="section-heading__title">{{ $category->name }}</h2>
            @endif
        </div>
    </div>
@elseif(request()->search || request()->category == 'all')
    <div class="sub-cat-details__header-top">
        <div class="breadcrumb">
            <ul class="breadcrumb__list">
                <li class="breadcrumb__item">
                    <a class="breadcrumb__link" href="{{ route('gig.index') }}"><i class="las la-home fs-18"></i></a>
                </li>
                <li class="breadcrumb__item">
                    @if (request()->search)
                        <span>@lang('Search')</span>
                    @elseif(request()->category == 'all')
                        <span>@lang('All Categories')</span>
                    @endif
                </li>
            </ul>
        </div>
    </div>
@endif
