@php
    $gigContent = getContent('popular_gig.content', true);
    $subcategories = App\Models\Subcategory::active()
        ->whereHas('category', function ($q) {
            $q->active();
        })
        ->popular()
        ->hasGigs()
        ->with([
            'gigs' => function ($q) {
                $q->approved();
            },
            'gigs.user',
            'gigs.reviews',
            'gigs.images',
            'gigs.gigPricing',
        ])
        ->get(['id', 'name']);
@endphp
@foreach ($subcategories as $subcategory)
    <section class="popular-gigs pb-120">
        <div class="container">
            <div class="section-heading flex-between gap-3">
                <h3 class="section-heading__title">
                    {{ __($gigContent->data_values->heading) }} <span>{{ __($subcategory->name) }}</span>
                </h3>
                <a class="view-more-link btn btn-outline--base btn--sm" href="{{ route('gig.subcategories', $subcategory->id) }}">
                    <span>@lang('View more')</span>
                    <i class="fas fa-long-arrow-alt-right"></i>
                </a>
            </div>
            <div class="row g-4">
                @foreach ($subcategory->gigs->take(4) as $gig)
                    @include($activeTemplate . 'partials.gig', ['cols' => 'col-xsm-6 col-sm-6 col-lg-4 col-xl-3'])
                @endforeach
            </div>
        </div>
    </section>
@endforeach
