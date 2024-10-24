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
                                    <th>@lang('Is Featured')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td class="text-start">
                                            <div class="d-flex flex-wrap justify-content-end justify-content-lg-start">
                                                <span class="avatar avatar--xs me-2">
                                                    <img
                                                        src="{{ getImage(getFilePath('category') . '/' . $category->image, getFileSize('category')) }}">
                                                </span>
                                                <span class="mt-1">
                                                    {{ __($category->name) }}
                                                </span>
                                            </div>
                                        </td>

                                        <td>
                                            @if ($category->is_featured == Status::CATEGORY_FEATURED)
                                                <span class="badge badge--info"> @lang('Yes')</span>
                                            @else
                                                <span class="badge badge--warning"> @lang('No')</span>
                                            @endif
                                        </td>

                                        <td>
                                            @php echo $category->statusBadge; @endphp
                                        </td>
                                        @php
                                            $category->image_with_path = getImage(
                                                getFilePath('category') . '/' . $category->image,
                                                getFileSize('category'),
                                            );
                                        @endphp
                                        <td>
                                            <div class="btn-group">
                                                <div class="d-flex justify-content-end flex-wrap gap-1">
                                                    <button class="btn btn-outline--primary editBtn cuModalBtn btn-sm"
                                                        data-modal_title="@lang('Update Category')"
                                                        data-resource="{{ $category }}" data-edit="true">
                                                        <i class="las la-pen"></i>@lang('Edit')
                                                    </button>
                                                    <a class="btn btn-outline--warning btn-sm"
                                                        href="{{ route('admin.category.pricing', $category->id) }}"
                                                        title="@lang('Dynamic pricing head generation')">
                                                        <i class="las la-cogs"></i>@lang('Pricing')
                                                    </a>

                                                    <button class="btn btn-outline--info btn-sm dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                        <i class="las la-ellipsis-v"></i> @lang('More')
                                                    </button>
                                                    <ul class="dropdown-menu px-2">
                                                        <li>
                                                            @if ($category->status == Status::ENABLE)
                                                                <span class="dropdown-item cursor-pointer confirmationBtn"
                                                                    data-question="@lang('Are you sure to disable this category?')"
                                                                    data-action="{{ route('admin.category.status', $category->id) }}">
                                                                    <i class="la la-eye-slash"></i> @lang('Disable')
                                                                </span>
                                                            @else
                                                                <span class="dropdown-item cursor-pointer confirmationBtn"
                                                                    data-question="@lang('Are you sure to enable this category?')"
                                                                    data-action="{{ route('admin.category.status', $category->id) }}">
                                                                    <i class="la la-eye"></i> @lang('Enable')
                                                                </span>
                                                            @endif
                                                        </li>
                                                        <li>
                                                            @if ($category->is_featured == Status::CATEGORY_FEATURED)
                                                                <span class="dropdown-item cursor-pointer confirmationBtn"
                                                                    data-question="@lang('Are you sure to unfeature this category?')"
                                                                    data-action="{{ route('admin.category.feature', $category->id) }}">
                                                                    <i class="las la-star-half-alt"></i> @lang('Unfeature')
                                                                </span>
                                                            @else
                                                                <span class="dropdown-item cursor-pointer confirmationBtn"
                                                                    data-question="@lang('Are you sure to feature this category?')"
                                                                    data-action="{{ route('admin.category.feature', $category->id) }}">
                                                                    <i class="las la-star"></i> @lang('Feature')
                                                                </span>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>

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
                @if ($categories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($categories) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @php
        $categoryImage = getImage(getFilePath('category'), getFileSize('category'));
    @endphp
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
                <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Image')</label>
                            <x-image-uploader name="image" :imagePath="$categoryImage" :size="getFileSize('category')" class="w-100"
                                id="profilePicUpload" :required=false />
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
    <button class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Add Category')">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/cu-modal.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.cuModalBtn').on('click', function() {
                var isEdit = $(this).data('edit');
                var imageInput = $('#cuModal').find('[name=image]');

                if (isEdit) {
                    imageInput.removeAttr('required');
                    imageInput.closest('.form-group').find('label').first().removeClass('required');
                } else {
                    imageInput.attr('required', 'required');
                    imageInput.closest('.form-group').find('label').first().addClass('required');
                }
            });

            $('#cuModal').on('hidden.bs.modal', function() {
                $(this).find('.profilePicPreview').css('background-image', `url('{{ $categoryImage }}')`)
            })

        })(jQuery);
    </script>
@endpush
