@extends($activeTemplate . 'layouts.gig')
@section('gig-page')
    <div class="gig-requirments">
        <form id="requirmentForm">
            <h4 class="mb-2">@lang('Get all The information you need form buyers to get started')</h4>
            <p class="mb-4">@lang('Please provide your relevant requirements for your gig in a clear and understandable manner')</p>
            <div class="form-group">
                <textarea class="form--control nicEdit" placeholder="@lang('Write your requirement here')" required>{{ @$gig->requirement }}</textarea>
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

            $('#saveAndContinue').on('click', function() {

                var btnAfterSubmit = `<div class="spinner-border"></div> @lang('Saving')...`;
                var btnName = `@lang('Save & Continue') <i class="las la-angle-right"></i>`;
                var btn = $(this);
                btn.html(btnAfterSubmit);
                btn.attr('disabled', true);

                //store
                var formData = new FormData($('#requirmentForm')[0]);

                var nicInstance = nicEditors.findEditor('nicEditor0');
                var nicContent = nicInstance.getContent();

                var url = '{{ route('user.gig.store.requirement', $gig->id) }}';
                var token = '{{ csrf_token() }}';
                formData.append('_token', token);
                formData.append('requirement', nicContent);

                setTimeout(() => {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                if (!response.is_update) {
                                    window.location.href = response.redirect_url
                                } else {
                                    notify('success',`@lang('Gig requirement updated successfully')`);
                                        btn.html(btnName);
                                    btn.removeAttr('disabled');
                                }
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
