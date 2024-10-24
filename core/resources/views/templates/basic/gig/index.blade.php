@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="sub-cat-details py-60">
        <div class="container">

            @include($activeTemplate . 'gig.breadcrumb')

            <div class="sub-cat-details__header">
                <div class="sub-cat-details__header-bottom">
                    <div class="wrapper">

                        <div class="d-flex gap-2 align-items-center">
                            <div class="d-block d-xl-none">
                                <button type="button" class="filter-task btn btn--sm btn--base bg--base d-inline">
                                    <i class="las la-filter"></i> @lang('Filter') </button>
                            </div>
                            <span class="results">
                                {{ getAmount($gigs->total()) }} @lang('Services available')
                            </span>
                        </div>

                        <form class="filter-select sorting">
                            <span class="label">@lang('Sort by'):</span>
                            <select class="form-select sorting select2-basic" data-minimum-results-for-search="-1"
                                name="sorting">
                                <option value="0">@lang('All')</option>
                                <option value="best_selling" @selected(request()->sorting == 'best_selling')>@lang('Best Selling')</option>
                                <option value="top_rating" @selected(request()->sorting == 'top_rating')>@lang('Best Rating')</option>
                                <option value="top_reviews" @selected(request()->sorting == 'top_reviews')>@lang('Most Reviewed')</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row gy-4 gy-sm-5">
                <div class="col-xl-3">
                    <form>
                        <div class="filter-sidebar">
                            <button class="side-sidebar-close-btn d-block d-xl-none" type="button"><i
                                    class="las la-times"></i></button>
                            <div class="widget">
                                <div class="widget-header">
                                    <h5 class="title">@lang('Search')</h5>
                                </div>
                                <div class="widget-body">
                                    <div class="form-group">
                                        <div class="d-flex flex-column flex-wrap gap-2">
                                            <input type="text" value="{{ request()->search }}"
                                                class="form--control  form-control" placeholder="@lang('Search...')"
                                                name="search">
                                            <div class="d-flex gap-2">
                                                <div class="flex-fill">
                                                    <input type="number" value="{{ request()->min_price }}"
                                                        class="form--control  form-control" placeholder="@lang('Min Price')"
                                                        name="min_price">
                                                </div>
                                                <div class="flex-fill">
                                                    <input type="number" value="{{ request()->max_price }}"
                                                        class="form--control  form-control" placeholder="@lang('Max Price')"
                                                        name="max_price">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn--base btn--sm  w-100"><i class="las la-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="widget">
                                <div class="widget-header">
                                    <h5 class="title">@lang('Filter by Category')</h5>
                                </div>
                                <div class="widget-body">
                                    <ul class="filter-category filter-overflow">
                                        <li>
                                            <a href="{{ route('gig.index') }}?category=all"
                                                class="{{ request()->category == 'all' ? 'active' : '' }}"><i
                                                    class="las la-angle-right"></i> @lang('All Categories')</a>
                                        </li>
                                        @php
                                            $categories = App\Models\Category::active()->get();
                                        @endphp
                                        @foreach ($categories as $category)
                                            <li>
                                                <a href="{{ route('gig.categories', $category->id) }}"
                                                    class="{{ request()->id == $category->id ? 'active' : '' }}"><i
                                                        class="las la-angle-right"></i> {{ __($category->name) }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="widget">
                                <div class="widget-header">
                                    <h5 class="title">@lang('Rating')</h5>
                                </div>
                                <div class="widget-body">
                                    <div class="product-filter__card">
                                        <ul class="list">
                                            <li>
                                                <div class="widget-check-group">
                                                    <input class="form-check-input form--check rating-change" name="rating"
                                                        type="radio" value="all" @checked(request()->rating == 'all')
                                                        id="all-star">
                                                    <label class="form-check-label" for="all-star">
                                                        <span class="list list--row rating-list">@lang('All Rating')</span>
                                                    </label>
                                                </div>
                                            </li>

                                            @for ($i = 5; $i > 0; $i--)
                                                <li>
                                                    <div class="widget-check-group">
                                                        <input class="form-check-input custom--check rating-change"
                                                            name="rating" type="radio" value="{{ $i }}"
                                                            @checked(request()->rating == $i) id="star-{{ $i }}"
                                                            data-bs-toggle="tooltip"
                                                            title="{{ $i }} star{{ $i > 1 ? 's' : '' }} {{ $i < 5 ? 'or above' : '' }}">
                                                        <label class="form-check-label" for="star-{{ $i }}">
                                                            <span class="list list--row rating-list">
                                                                <span
                                                                    class="rating-list__icon {{ $i > 0 ? 'rating-list__icon-active' : 'rating-list__icon-disable' }}">
                                                                    <i class="fas fa-star"></i>
                                                                </span>
                                                                <span
                                                                    class="rating-list__icon {{ $i > 1 ? 'rating-list__icon-active' : 'rating-list__icon-disable' }}">
                                                                    <i class="fas fa-star"></i>
                                                                </span>
                                                                <span
                                                                    class="rating-list__icon {{ $i > 2 ? 'rating-list__icon-active' : 'rating-list__icon-disable' }}">
                                                                    <i class="fas fa-star"></i>
                                                                </span>
                                                                <span
                                                                    class="rating-list__icon {{ $i > 3 ? 'rating-list__icon-active' : 'rating-list__icon-disable' }}">
                                                                    <i class="fas fa-star"></i>
                                                                </span>
                                                                <span
                                                                    class="rating-list__icon {{ $i > 4 ? 'rating-list__icon-active' : 'rating-list__icon-disable' }}">
                                                                    <i class="fas fa-star"></i>
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </li>
                                            @endfor


                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-xl-9">
                    <div class="row gy-4  justify-content-lg-start justify-content-center">
                        @forelse ($gigs as $gig)
                            @include($activeTemplate . 'partials.gig', [
                                'cols' => 'col-12 col-sm-6 col-md-4 col-lg-4 col-xsm-6',
                            ])
                        @empty
                            @include($activeTemplate . 'partials.empty', ['message' => 'Gig not found!'])
                        @endforelse
                    </div>
                </div>
            </div>

            @if ($gigs->hasPages())
                <ul class="pagination">
                    {{ $gigs->links() }}
                </ul>
            @endif
        </div>
    </section>
@endsection

@push('script')
    <script>
        $('select').on('change', function() {
            let form = $('.sorting');
            form.submit();
        });

        $('.rating-change').on('click', function(e) {
            $(this).closest('form').submit();
        });

        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
