@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Is Featured')</th>
                                    <th>@lang('Is Popular')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subcategories as $subcategory)
                                    <tr>
                                        <td>
                                            {{ __($subcategory->name) }}
                                        </td>
                                        <td>
                                            {{ __($subcategory->category->name) }}
                                        </td>
                                        <td>
                                            @if ($subcategory->is_featured == Status::SUBCATEGORY_FEATURED)
                                                <span class="badge badge--info"> @lang('Yes')</span>
                                            @else
                                                <span class="badge badge--warning"> @lang('No')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($subcategory->is_popular == Status::SUBCATEGORY_POPULAR)
                                                <span class="badge badge--info"> @lang('Yes')</span>
                                            @else
                                                <span class="badge badge--warning"> @lang('No')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php echo $subcategory->statusBadge; @endphp
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end flex-wrap gap-1">
                                                <button class="btn btn-outline--primary editBtn cuModalBtn btn-sm" data-modal_title="@lang('Update Subcategory')" data-resource="{{ $subcategory }}">
                                                    <i class="las la-pen"></i>@lang('Edit')
                                                </button>

                                                <button class="btn btn-outline--info btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="las la-ellipsis-v"></i> @lang('More')
                                                </button>
                                                <ul class="dropdown-menu px-2">
                                                    <li>
                                                        @if ($subcategory->status == Status::ENABLE)
                                                            <span class="dropdown-item cursor-pointer confirmationBtn" data-question="@lang('Are you sure to disable this subcategory?')" data-action="{{ route('admin.subcategory.status', $subcategory->id) }}">
                                                                <i class="la la-eye-slash"></i> @lang('Disable')
                                                            </span>
                                                        @else
                                                            <span class="dropdown-item cursor-pointer confirmationBtn" data-question="@lang('Are you sure to enable this subcategory?')" data-action="{{ route('admin.subcategory.status', $subcategory->id) }}">
                                                                <i class="la la-eye"></i> @lang('Enable')
                                                            </span>
                                                        @endif
                                                    </li>
                                                    <li>
                                                        @if ($subcategory->is_featured == Status::CATEGORY_FEATURED)
                                                            <span class="dropdown-item cursor-pointer confirmationBtn" data-question="@lang('Are you sure to unfeature this subcategory?')" data-action="{{ route('admin.subcategory.feature', $subcategory->id) }}">
                                                                <i class="las la-star-half-alt"></i> @lang('Unfeature')
                                                            </span>
                                                        @else
                                                            <span class="dropdown-item cursor-pointer confirmationBtn" data-question="@lang('Are you sure to feature this subcategory?')" data-action="{{ route('admin.subcategory.feature', $subcategory->id) }}">
                                                                <i class="las la-star"></i> @lang('Feature')
                                                            </span>
                                                        @endif
                                                    </li>
                                                    <li>
                                                        @if ($subcategory->is_popular == Status::SUBCATEGORY_POPULAR)
                                                            <span class="dropdown-item cursor-pointer confirmationBtn" data-question="@lang('Are you sure to unpopular this subcategory?')" data-action="{{ route('admin.subcategory.popular', $subcategory->id) }}">
                                                                <i class="las la-heart-broken"></i> @lang('Unpopular')
                                                            </span>
                                                        @else
                                                            <span class="dropdown-item cursor-pointer confirmationBtn" data-question="@lang('Are you sure to popular this subcategory?')" data-action="{{ route('admin.subcategory.popular', $subcategory->id) }}">
                                                                <i class="las la-heart"></i> @lang('Popular')
                                                            </span>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($subcategories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($subcategories) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!--Cu Modal -->
    <div class="modal fade" id="cuModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.subcategory.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Category')</label>
                            <select class="form-control select2" name="category_id">
                                <option value="" disabled selected>@lang('Select One')</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ __($category->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input class="form-control" name="name" type="text" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form />
    <button class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Add Subcategory')">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/cu-modal.js') }}"></script>
@endpush

@push('script')
    <script>
        (function ($) {
            'use strict'
            $('.editBtn').on('click',function () {
                var resource = $(this).data('resource');
                $('.select2').val(resource.category_id).trigger('change');

            });
        })(jQuery);
    </script>
@endpush
