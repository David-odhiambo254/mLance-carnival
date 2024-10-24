@extends($activeTemplate . 'layouts.gig')
@section('gig-page')
    <div class="gig-overview">
        <form id="overviewForm">
            <div class="gig-overview-space">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="gig-overview__title">
                            <h6>@lang('Gig Title')</h6>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="gig-overview__form mb-2">
                            <div class="form-group">
                                <input class="form--control" name="title" type="text"
                                    value="{{ old('title', @$gig->title) }}" title="title"
                                    placeholder="@lang('I will')..." required>
                                <p class="mt-1">@lang('As your Gig storefront'), <b>@lang('your title is the most important place')</b>
                                    @lang('to include keywords that buyers would likely use to search for a service like yours')</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="gig-overview-space">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="gig-overview__title">
                            <h6>@lang('Gig Description')</h6>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="gig-overview__form">
                            <textarea class="form--control nicEdit" id="dsc" placeholder="@lang('Write a description')">{{ old('description', @$gig->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="gig-overview-space">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="gig-overview__title">
                            <h6>@lang('Category')</h6>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="gig-overview__category">
                            <div class="row gy-2">
                                <div class="col-md-6">
                                    <select class="form-select form--control select2-basic" name="category_id" required>
                                        <option value="">@lang('Select Categories')</option>
                                        @foreach ($categories as $category)
                                            <option data-subcategories='@json($category->subcategories)'
                                                value="{{ $category->id }}" @selected($category->id == @$gig->category_id)>
                                                {{ __($category->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="gig-overview__sub-category">
                                        <select class="form-select form--control select2-basic" id="gigSubcategory"
                                            name="subcategory_id">
                                            <option value="">@lang('Select Subcategories')</option>
                                        </select>
                                    </div>
                                </div>
                                <p class="mt-1">@lang('Choose the category and subcategory most suitable for your gig').</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gig-overview-space">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="gig-overview__title">
                            <h6>@lang('Search Tags')</h6>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="gig-overview__title">
                            <div class="form--group ">
                                <select class="form--control select2-auto-tokenize" name="tags[]" multiple="multiple"
                                    required>
                                    @if (@$gig->tags)
                                        @foreach ($gig->tags as $option)
                                            <option value="{{ $option }}" selected>{{ __($option) }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <small class="mt-2">@lang('Separate multiple keywords by') <code>,</code>(@lang('comma'))
                                    @lang('or') <code>@lang('enter')</code> @lang('key').</small>
                                <p class="mt-1">@lang('Tag your Gig with buzz words that are relevant to the services you offer. Use all 5 tags to get found').</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/nicEdit.js') }}"></script>
@endpush

@push('style')
    <style>
        .nicEdit-main {
            outline: none !important;
        }

        .nicEdit-custom-main {
            border-right-color: #cacaca73 !important;
            border-bottom-color: #cacaca73 !important;
            border-left-color: #cacaca73 !important;
            border-radius: 0 0 5px 5px !important;
        }

        .nicEdit-panelContain {
            border-color: #cacaca73 !important;
            border-radius: 5px 5px 0 0 !important;
            background-color: #fff !important
        }

        .nicEdit-buttonContain div {
            background-color: #fff !important;
            border: 0 !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 10px !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";


            bkLib.onDomLoaded(function() {
                $(".nicEdit").each(function(index) {
                    $(this).attr("id", "nicEditor" + index);

                    new nicEditor({
                        fullPanel: true
                    }).panelInstance('nicEditor' + index, {
                        hasPanel: true
                    });
                    $('.nicEdit-main').parent('div').addClass('nicEdit-custom-main')
                });
            });

            $.each($('.select2-auto-tokenize'), function() {
                $(this)
                    .wrap(`<div class="position-relative"></div>`)
                    .select2({
                        tags: true,
                        tokenSeparators: [','],
                        dropdownParent: $(this).parent()
                    });
            });

            var gigSubcategoryId = `{{ @$gig->subcategory_id }}`

            $('select[name="category_id"]').on('change', function() {
                let subcategories = $(this).find(`option:selected`).data(`subcategories`);
                let html = `<option value="">@lang('Select One')</option>`;
                $.each(subcategories, function(i, subcategory) {
                    let isSelected = gigSubcategoryId == subcategory.id ? 'selected' : '';
                    html +=
                        `<option value="${subcategory.id}" ${isSelected}>${subcategory.name}</option>`;
                });
                $(`select[name=subcategory_id]`).html(html);
            }).change();


            //Ajax
            $('#saveAndContinue').on('click', function() {

                var btnAfterSubmit = `<div class="spinner-border"></div> @lang('Saving')...`;
                var btnName = `@lang('Save & Continue') <i class="las la-angle-right"></i>`;
                var btn = $(this);
                btn.html(btnAfterSubmit);
                btn.attr('disabled', true);

                //store
                var formData = new FormData($('#overviewForm')[0]);

                var nicInstance = nicEditors.findEditor('nicEditor0');
                var nicContent = nicInstance.getContent();

                var url = '{{ route('user.gig.store.overview', @$gig->id ?? '') }}';
                var token = '{{ csrf_token() }}';
                formData.append('_token', token);
                formData.append('description', nicContent);

                setTimeout(() => {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                @if (!$gig)
                                    window.location.href = response.redirect_url
                                @else
                                    notify('success', `@lang('Gig overview updated successfully')`);
                                    btn.html(btnName);
                                    btn.removeAttr('disabled');
                                @endif
                            } else {
                                notify('error', response.message);
                                btn.html(btnName);
                                btn.removeAttr('disabled');
                            }
                        },
                        error: function(xhr, status, error) {
                            notify('error', error);
                            btn.html(btnName);
                            btn.removeAttr('disabled');
                        }
                    });
                }, 1000);
            });

        })(jQuery);
    </script>
@endpush
