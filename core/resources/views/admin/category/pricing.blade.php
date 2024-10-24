@extends('admin.layouts.app')
@section('panel')
    <div class="submitRequired bg--warning form-change-alert d-none"><i class="fas fa-exclamation-triangle"></i>
        @lang('You\'ve to click on the submit button to apply the changes')</div>
    <div class="row mb-none-30">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg--primary d-flex justify-content-between">
                    <h5 class="text-white">@lang('Pricing Form for Gig')</h5>
                    <button class="btn btn-sm btn-outline-light float-end form-generate-btn" type="button"> <i
                            class="la la-fw la-plus"></i>@lang('Add New')</button>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.category.pricing.store', $category->id) }}">
                        @csrf
                        <x-generated-form :form=$pricing />
                        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-form-generator-modal />
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('#formGenerateModal select option[value="file"]').each(function() {
                var $this = $(this);
                $this.remove();
            });

            $('.select2').trigger('change');
        })(jQuery);
    </script>
@endpush
