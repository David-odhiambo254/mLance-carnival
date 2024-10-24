<div class="gig-sticky-header">
    <div class="step-header">
        <div class="stepform">
            <ul class="nav progressbar nav-pills project-setup-menu">
                <li class="nav-item active" role="presentation">
                    <a class="nav-btn" href="{{ route('user.gig.overview', @$gig->id) }}">@lang('Overview')
                    
                    </a>
                </li>
                <li class="nav-item @if (@$gig->step >= 1) active @endif">
                    <a class="nav-btn" href="@if ($gig) {{ route('user.gig.pricing', $gig->id) }}@else # @endif">@lang('Pricing')</a>
                </li>
                <li class="nav-item @if (@$gig->step >= 2) active @endif">
                    <a class="nav-btn" href="@if ($gig && @$gig->step >= 2) {{ route('user.gig.requirement', $gig->id) }}@else # @endif">@lang('Requirement')</a>
                </li>
                <li class="nav-item @if (@$gig->step >= 3) active @endif">
                    <a class="nav-btn" href="@if ($gig && @$gig->step >= 3) {{ route('user.gig.faqs', $gig->id) }}@else # @endif">@lang('FAQ')</a>
                </li>
                <li class="nav-item @if (@$gig->step >= 4) active @endif">
                    <a class="nav-btn" href="@if ($gig && @$gig->step >= 4) {{ route('user.gig.gallery', $gig->id) }}@else # @endif">@lang('Gallery')</a>
                </li>
                <li class="nav-item @if (@$gig->step >= 5) active @endif">
                    <a class="nav-btn" href="@if ($gig && @$gig->step >= 5) {{ route('user.gig.publish', $gig->id) }}@else # @endif">@lang('Publish')</a>
                </li>
            </ul>
        </div>
    </div>
    @if ($gig && @$gig->step == 5 && request()->routeIs('user.gig.publish', $gig->id))
        <div class="gig-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">@lang('Publish the Gig')</h5>
            <button class="btn btn--base" id="saveAndDraft">@lang('Save as Draft') <i
                   class="las la-angle-right"></i></button>
        </div>
    @else
        <div class="gig-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">{{ __($pageTitle) }}</h5>
                @if (@$gig->step == 5 && request()->routeIs('user.gig.gallery'))
                    <span class="fs-12">@lang('Supported files'): <b>@lang('jpeg'), @lang('jpg'), @lang('png')</b> @lang(' & image will be resized into') <b>{{ getFileSize('gig') }} @lang('px')</b> </span>
                @endif
            </div>
            <button class="btn btn--base" id="saveAndContinue" type="button">
                @lang('Save & Continue') <i class="las la-angle-right"></i>
            </button>
        </div>
    @endif

</div>
@push('style')
    <style>
        .spinner-border {
            width: 1rem;
            height: 1rem;
        }
    </style>
@endpush
