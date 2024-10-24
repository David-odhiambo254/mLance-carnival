@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#overview" type="button">@lang('Gig Overview')</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pricing" type="button">@lang('Pricing')</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#faq" type="button">@lang('FAQ')</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#gallery" type="button">@lang('Gallery')</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="overview">
                            <div class="mt-4">
                                <h5>@lang('Basic Details')</h5>
                                <hr>
                                <div class="mb-4">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <span class="text--muted">@lang('Gig Owner')</span>
                                            <h6 class="text--primary"><a
                                                   href="{{ route('admin.users.detail', $gig->user_id) }}">{{ $gig->user->username }}</a>
                                            </h6>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="text--muted">@lang('Title')</span>
                                            <h6>{{ __($gig->title) }}</h6>
                                        </li>

                                        <li class="list-group-item">
                                            <span class="text--muted">@lang('Category')</span>
                                            <h6>{{ __($gig->category->name) }}</h6>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="text--muted">@lang('Subcategory')</span>
                                            <h6>{{ __($gig->subcategory->name) }}</h6>
                                        </li>

                                        @if ($gig->tags)
                                            <li class="list-group-item">
                                                <span>@lang('Tags')</span>

                                                <div>
                                                    @foreach ($gig->tags as $tags)
                                                        <span class="badge badge--primary">{{ $tags }}</span>
                                                    @endforeach

                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                                <h5>@lang('Description')</h5>
                                <hr>
                                <div class="mb-4">
                                    @php  echo $gig->description; @endphp
                                </div>
                                <h5>@lang('Requirments')</h5>
                                <hr>
                                <div class="mb-4">
                                    @php  echo $gig->requirement; @endphp
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pricing">
                            <div class="mt-4">
                                <div class="accordion" id="pricingAccordion">
                                    @foreach ($packages as $package)
                                        @php
                                            $pricing = @$package->gigPricing($gig->id);
                                        @endphp
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}" type="button">
                                                    {{ $pricing->name }} <small>({{ showAmount($pricing->price) }})</small>
                                                </button>
                                            </h2>
                                            <div class="accordion-collapse collapse" id="collapse{{ $loop->index }}" data-bs-parent="#pricingAccordion" aria-labelledby="heading{{ $loop->index }}">
                                                <div class="accordion-body">
                                                    <ul class="list-group list-group-flush">
                                                        @foreach ($pricing->pricing_data as $k => $item)
                                                            <li class="list-group-item">
                                                                <span class="text--muted">{{ __(keyToTitle($k)) }}</span>
                                                                <h6>
                                                                    @if (getType($item) == 'array')
                                                                        {{ implode(',', $item) }}
                                                                    @else
                                                                        {{ __($item) }}
                                                                    @endif
                                                                </h6>
                                                            </li>
                                                        @endforeach
                                                        <li class="list-group-item">
                                                            <span class="text--muted">@lang('Description')</span>
                                                            <h6>
                                                                {{ __($pricing->description) }}
                                                            </h6>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="faq">
                            <div class="mt-4">
                                <div class="accordion" id="faq">
                                    @foreach ($gig->faqs->question as $key => $faq)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button {{ !$loop->first ? 'collapsed' : null }}" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $key + 1 }}" type="button" aria-expanded="{{ !$loop->first ? 'false' : 'true' }}">
                                                    {{ $gig->faqs->question[$key] }}
                                                </button>
                                            </h2>
                                            <div class="accordion-collapse {{ $loop->first ? 'show' : null }} collapse" id="collapse-{{ $key + 1 }}" data-bs-parent="#faq">
                                                <div class="accordion-body">
                                                    {{ $gig->faqs->answer[$key] }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="gallery">
                            <div class="mt-4">
                                <div class="row gy-4">
                                    @foreach ($gig->images as $image)
                                        <div class="col-md-3">
                                            <div class="gallery-card">
                                                <a class="view-btn" data-rel="lightcase:myCollection" href="{{ getImage(getFilePath('gig') . '/' . $image->name, getFileSize('gig')) }}"><i class="las la-image"></i></a>
                                                <img class="w-100" src="{{ getImage(getFilePath('gig') . '/' . $image->name, getFileSize('gig')) }}" alt="@lang('image')">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    @if ($gig->status == Status::GIG_PENDING || $gig->status == Status::GIG_APPROVED)
        <button class="btn btn-outline--danger confirmationBtn" data-action="{{ route('admin.gig.reject', $gig->id) }}" data-question="@lang('Are you sure to reject this gig')?" type="button">
            <i class="la la-times"></i> @lang('Reject')
        </button>
    @endif
    @if ($gig->status == Status::GIG_REJECTED || $gig->status == Status::GIG_PENDING)
        <button class="btn btn-outline--success confirmationBtn" data-action="{{ route('admin.gig.approve', $gig->id) }}" data-question="@lang('Are you sure to approve this gig')?" type="button">
            <i class="la la-check"></i> @lang('Approve')
        </button>
    @endif
@endpush

@push('style-lib')
    <link href="{{ asset('assets/admin/css/vendor/lightcase.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/lightcase.js') }}"></script>
@endpush

@push('style')
    <style>
        .list-group-item {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            padding: .8rem 0;
            border: 1px solid #f1f1f1;
        }

        .accordion-button:not(.collapsed) {
            box-shadow: none !important;
        }

        .gallery-card {
            position: relative;
        }

        .gallery-card:hover .view-btn {
            opacity: 1;
            visibility: visible;
        }

        .gallery-card .view-btn {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.364);
            color: #f0e9e9;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            font-size: 42px;
            opacity: 0;
            visibility: none;
            -webkit-transition: all 0.3s;
            -o-transition: all 0.3s;
            transition: all 0.3s;
        }

        .thumb i {
            font-size: 22px;
        }

        .lightcase-icon-prev:before {
            content: '\f104' !important;
            font-family: 'Line Awesome Free' !important;
            font-weight: 900 !important;
        }

        .lightcase-icon-next:before {
            content: '\f105' !important;
            font-family: 'Line Awesome Free' !important;
            font-weight: 900 !important;
        }

        .lightcase-icon-close:before {
            content: '\f00d' !important;
            font-family: 'Line Awesome Free' !important;
            font-weight: 900 !important;
        }

        .lightcase-icon-prev,
        .lightcase-icon-next,
        .lightcase-icon-close {
            border: 1px solid #ddd;
            font-size: 22px !important;
            width: 50px !important;
            height: 50px !important;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            background-color: #ffffff0f;
        }
    </style>
@endpush
@push('script')
    <script>
        'use strict';
        $('a[data-rel^=lightcase]').lightcase();
    </script>
@endpush
