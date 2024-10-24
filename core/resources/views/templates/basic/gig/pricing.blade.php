<div class="gig-details-tab mb-4">
    <div class="gig-details-tab__body ">
        <ul class="nav nav-tabs ">
            @foreach ($packages as $package)
                @php
                    $pricing = @$package->gigPricing($gig->id);
                @endphp
                <li class="nav-item" data-price-id="{{ $pricing->id }}">
                    <button class="nav-link @if ($loop->first) active @endif" data-bs-toggle="tab" data-bs-target="#{{ titleToKey($package->name) }}-tab-pane-{{ $device }}" type="button" role="tab" aria-selected="true">
                        {{ __($package->name) }}
                    </button>
                </li>
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach ($packages as $key => $package)
                @php
                    $pricing = @$package->gigPricing($gig->id);
                @endphp
                <div class="basic-tab-pane tab-pane @if ($loop->first) active @endif" id="{{ titleToKey($package->name) }}-tab-pane-{{ $device }}" role="tabpanel">
                    <div class="tab-pane__header">
                        <div class="tab-pane__heading">
                            <h6 class="name">{{ __($pricing->name) }}</h6>
                            <span class="price">{{ gs('cur_sym') . getAmount($pricing->price) }}</span>
                        </div>
                        <p class="tab-pane__desc">{{ __($pricing->description) }}</p>
                    </div>
                    <div class="tab-pane__body">
                        <ul class="info-list" id="tab-pane-expand-{{ $key + 1 }}">
                            @foreach ($pricing->pricing_data as $k => $item)
                                <li class="info-list__item">
                                    <span class="text">{{ __(keyToTitle($k)) }} :</span>
                                    <span class="text--base">
                                        @if (getType($item) == 'array')
                                            {{ implode(',', $item) }}
                                        @else
                                            {{ __($item) }}
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="gig-details-tab__footer">
        <div class="gig-details-tab__buttons">
            <button class="btn @if (auth()->user()) orderBtn @else loginNotify @endif btn--base dynamic-action-btn" data-action="{{ route('user.order.process', [$gig->id, $pricing->id]) }}" type="button" @disabled($gig->user_id == auth()->id())>@lang('Request To Order')</button>
            <button class="btn btn-outline--dark @if (auth()->user()) contactMeBtn @else loginNotify @endif" type="button" @disabled($gig->user->id == auth()->id())> <span>@lang('Contact Seller')</span></button>
        </div>
        <a class="compare-packages-link" href="#pills-package-tab">@lang('Compare Packages') <i class="las la-angle-double-right"></i></a>
    </div>
</div>
