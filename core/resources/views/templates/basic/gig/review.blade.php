@forelse ($reviews as $review)
    <li class="review-list-item">
        <img class="user-img" src="{{ avatar(@$review->user->image ? getFilePath('userProfile') . '/' . @$review->user->image : null) }}" alt="@lang('image')">
        <div class="review-list-item__wrapper">
            <a class="username" href="{{ route('gig.owner.profile', $review->user_id) }}">{{ $review->user->fullname }}</a>
            <ul class="meta-list">
                <li class="meta-list__item">
                    <ul class="rating-list gap-0 text--warning">
                            @php echo avgRating($review->rating); @endphp
                        <li class="rating-list__item"><span class="rating-list__text">&nbsp; {{ @$review->rating }}</span></li>
                    </ul>
                </li>
                <li class="meta-list__item">
                    <span class="meta-list__text">{{ diffForHumans($review->created_at) }}</span>
                </li>
            </ul>
            <p class="review-text">
                {{ __($review->review) }}
            </p>
        </div>
    </li>
@empty
    @include($activeTemplate . 'partials.empty', ['message' => 'No review found for this gig!'])
@endforelse
