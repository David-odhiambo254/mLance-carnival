@extends($activeTemplate . 'layouts.gig')
@section('gig-page')
    <form id="FAQForm">
        <div class="row gy-4">
            @if (!$gig->faqs)
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label class="form--label">@lang('Question')</label>
                        <input class="form--control" name="question[]" type="text" required>
                    </div>
                    <div class="form-group">
                        <label class="form--label">@lang('Answer')</label>
                        <textarea class="form--control" name="answer[]" required></textarea>
                    </div>
                    <button class="btn btn--danger btn--sm remove-btn w-100" type="button" disabled>@lang('Remove')</button>
                </div>
            @endif
            @foreach ($gig->faqs->question ?? [] as $key => $faq)
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label class="form--label">@lang('Question')</label>
                        <input class="form--control" name="question[]" type="text" value="{{ $gig->faqs->question[$key] }}">
                    </div>
                    <div class="form-group">
                        <label class="form--label">@lang('Answer')</label>
                        <textarea class="form--control" name="answer[]">{{ $gig->faqs->answer[$key] }}</textarea>
                    </div>
                    <button class="btn btn--danger btn--sm w-100 remove-btn" type="button">@lang('Remove')</button>
                </div>
            @endforeach
            <div class="col-lg-4 col-md-6 addFaqArea">
                <div class="add-new-faq addNewFAQ">
                    <div class="add-new-faq-box">
                        <i class="las la-plus-circle"></i>
                        <p>@lang('Add New')</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.addNewFAQ').on('click', function() {
                $(".addFaqArea").before(`
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <label class="form--label">@lang('Question')</label>
                        <input type="text" name="question[]" class="form--control">
                    </div>
                    <div class="form-group">
                        <label class="form--label">@lang('Answer')</label>
                        <textarea name="answer[]" class="form--control"></textarea>
                    </div>
                <button type="button" class="btn btn--danger btn--sm w-100 remove-btn">@lang('Remove')</button>
            </div>
                `)
                disableRemoveFaq()
            });
            $(document).on('click', '.remove-btn', function() {
                $(this).closest('div').remove();
                disableRemoveFaq()
            });

            function disableRemoveFaq() {
                if ($(document).find('.remove-btn').length == 1) {
                    $(document).find('.remove-btn').attr('disabled', true);
                } else {
                    $(document).find('.remove-btn').removeAttr('disabled');
                }
            }



            $('#saveAndContinue').on('click', function() {

                var btnAfterSubmit = `<div class="spinner-border"></div> @lang('Saving')...`;
                var btnName = `@lang('Save & Continue') <i class="las la-angle-right"></i>`;
                var btn = $(this);
                btn.html(btnAfterSubmit);
                btn.attr('disabled', true);

                //store
                var formData = new FormData($('#FAQForm')[0]);
                var url = '{{ route('user.gig.store.faqs', $gig->id) }}';
                var token = '{{ csrf_token() }}';
                formData.append('_token', token);

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
                                    notify('success', 'Gig faqs updated successfully');
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
