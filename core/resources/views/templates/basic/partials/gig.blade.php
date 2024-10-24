@php
    $minPrice = $gig->gigPricing->pluck('price')->min();
@endphp
<div class="{{ $cols ?? '' }}">
    <div class="gig-card h-100">
        <div class="gig-card__header">
            <div class="gig-card__thumbs">
                @foreach ($gig->images as $image)
                    <a class="gig-card__thumb" href="{{ route('gig.explore', $gig->id) }}">
                        <img src="{{ getImage(getFilePath('gig') . '/' . $image->name, getFileSize('gig')) }}" alt="@lang('image')" >
                    </a>
                @endforeach
            </div>
        </div>
        <div class="gig-card__body">
            <div class="gig-card__info">
                <div class="gig-card__user">
                    <div class="avatar">
                        <img class="avatar__img" src="{{ avatar(@$gig->user->image ? getFilePath('userProfile') . '/' . @$gig->user->image : null) }}" alt="@lang('image')" >
                    </div>
                    <a class="name" href="{{ route('gig.owner.profile', $gig->user_id) }}">{{ __($gig->user->fullname) }}</a>
                </div>
            </div>

            <h6 class="gig-card__title">
                <a href="{{ route('gig.explore', $gig->id) }}">{{ __(strLimit($gig->title, 60)) }}</a>
            </h6>
        </div>

        <div class="gig-card__footer">
            <div class="gig-card__rating">
                <span class="icon">
                    <i class="fas fa-star"></i>
                    <span>{{ $gig->avg_rating }}</span>
                </span>

                <span class="number">({{ @$gig->reviews->count() }})</span>
            </div>
            <span class="gig-card__price"><img src="{{ asset($activeTemplateTrue.'/images/metamask.png') }}" alt="@lang('img')">@lang('From') {{ showAmount($minPrice) }}</span>
        </div>
    </div>
</div>
