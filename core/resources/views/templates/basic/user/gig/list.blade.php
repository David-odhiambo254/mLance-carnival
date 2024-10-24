@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card custom--card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 py-2">{{ __($pageTitle) }}</h6>
                        <a class="btn btn--base btn--sm" href="{{ route('user.gig.overview') }}"> <i class="las la-plus"></i>
                            @lang('Create Gig')</a>
                    </div>
                    <div class="card-body p-0">
                        @if (!blank($gigs))
                            <div class="table-responsive--sm table-responsive">
                                <table class="table table--light style--two custom-data-table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Gig Title')</th>
                                            <th class="text-center">@lang('Gig Views')</th>
                                            <th class="text-center">@lang('Category')</th>
                                            <th class="text-center">@lang('Is Published')</th>
                                            <th class="text-center">@lang('Status')</th>
                                            <th class="text-center">@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($gigs as $gig)
                                            <tr>
                                                @php
                                                    $thumbnaul = $gig->images?->first();
                                                @endphp
                                                <td>
                                                    <div class="avatar avatar--xs">
                                                        <img src="{{ getImage(getFilePath('gig') . '/' . @$thumbnaul->name, getFileSize('gig')) }}"
                                                            alt="@lang('image')">
                                                        <a class="project-title border-effect"
                                                            href="{{ route('gig.explore', $gig->id) }}">{{ strLimit(__($gig->title), 50) }}</a>
                                                    </div>
                                                </td>
                                                <td class="text-center"><span
                                                        class="badge badge--primary">{{ __($gig->views) }}</span></td>
                                                <td class="text-center"><em>{{ __($gig->category->name) }}</em> <br>
                                                    {{ __($gig->subcategory->name) }}</td>

                                                <td class="text-center">@php echo $gig->publishingStatusBadge; @endphp</td>
                                                <td class="text-center">@php  echo $gig->statusBadge; @endphp</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a class="invest-details-link" data-bs-toggle="dropdown"
                                                            href="#">
                                                            <i class="las la-ellipsis-v"></i>
                                                        </a>
                                                        <ul class="dropdown-menu px-2">
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('user.gig.overview', $gig->id) }}"> <i
                                                                        class="la la-pencil"></i> @lang('Edit')</a>
                                                            </li>
                                                            <li>
                                                                @if ($gig->is_published == Status::GIG_PUBLISHED)
                                                                    <a class="dropdown-item cursor-pointer confirmationBtn"
                                                                        data-action="{{ route('user.gig.published.status', $gig->id) }}"
                                                                        data-question="@lang('Are you sure to draft this gig?')"
                                                                        href="javascript:void(0)">
                                                                        <i class="la la-eye-slash"></i> @lang('Draft')
                                                                    </a>
                                                                @else
                                                                    <a class="dropdown-item cursor-pointer confirmationBtn"
                                                                        data-action="{{ route('user.gig.published.status', $gig->id) }}"
                                                                        data-question="@lang('Are you sure to publish this gig?')"
                                                                        href="javascript:void(0)">
                                                                        <i class="la la-eye"></i> @lang('Publish')
                                                                    </a>
                                                                @endif
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('user.project.completed', $gig->id) }}">
                                                                    <i class="las la-cart-arrow-down"></i>
                                                                    @lang('Total Sales')
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            @include($activeTemplate . 'partials.empty', [
                                'message' => 'No gig created yet',
                            ])
                        @endif

                    </div>
                    @if ($gigs->hasPages())
                        <div class="card-footer">
                            {{ $gigs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="confirmationModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="completeForm" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0"> @lang('Confirmation alert!')</h5>
                            <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                        </div>
                        <p class="py-3 question"></p>
                        <div class="text-end">
                            <button class="btn btn--sm btn-dark" data-bs-dismiss="modal"
                                type="button">@lang('No')</button>
                            <button class="btn btn--sm btn--base" type="submit">@lang('Yes')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .table-responsive {
            min-height: 300px;
            background: transparent
        }

        .card {
            box-shadow: none;
        }

        .gig-more-btn {
            color: #707070;
            font-size: 20px;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).on('click', '.confirmationBtn', function() {
                var modal = $('#confirmationModal');
                let data = $(this).data();
                modal.find('.question').text(`${data.question}`);
                modal.find('form').attr('action', `${data.action}`);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
