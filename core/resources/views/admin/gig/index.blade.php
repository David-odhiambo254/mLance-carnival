@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Gig Title')</th>
                                    <th>@lang('Owner')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Is Published')</th>
                                    <th>@lang('Step On')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($gigs as $gig)
                                    <tr>
                                        @php
                                            $thumbnaul = $gig->images?->first();
                                        @endphp
                                        <td class="text-start">
                                            <div class="d-flex flex-wrap justify-content-end justify-content-lg-start">
                                                <span class="avatar avatar--xs me-2">
                                                    <img
                                                        src="{{ getImage(getFilePath('gig') . '/' . @$thumbnaul->name, getFileSize('gig')) }}">
                                                </span>
                                                <span>
                                                    <span class="fw-bold">{{ strLimit(__($gig->title), 50) }}</span>
                                                    <br>
                                                    <em class="text-muted">
                                                        {{ __($gig->subcategory->name) }}
                                                    </em>
                                                </span>
                                            </div>
                                        </td>
                                        <td>{{ __($gig->user->fullname) }} <br>
                                            <span class="small">
                                                <a
                                                    href="{{ route('admin.users.detail', $gig->user->id) }}"><span>@</span>{{ $gig->user->username }}</a>
                                            </span>
                                        </td>

                                        <td>{{ __($gig->category->name) }}</td>

                                        <td>@php echo $gig->publishingStatusBadge; @endphp</td>
                                        <td>
                                            @if ($gig->step < 5)
                                                <span class="fw-bold text--primary">@lang('Step')
                                                    {{ $gig->step }}</span>
                                            @else
                                                <span class="badge badge--primary">@lang('Completed')</span>
                                            @endif
                                        </td>
                                        <td>@php  echo $gig->statusBadge; @endphp</td>

                                        <td>
                                            <div class="button--group">
                                                <a class="btn btn-sm btn-outline--primary @if ($gig->step < 5) disabled @endif"
                                                    href="@if ($gig->step < 5) # @else {{ route('admin.gig.details', $gig->id) }} @endif">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($gigs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($gigs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form gigIsPublished="yes" placeholder="search..." />
@endpush

@push('script')
    <script>
        $('select').on('change', function() {
            let form = $('.filter');
            form.submit();
        });
    </script>
@endpush
