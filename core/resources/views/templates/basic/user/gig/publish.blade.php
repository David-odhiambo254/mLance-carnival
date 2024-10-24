@extends($activeTemplate . 'layouts.gig')
@section('gig-page')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="gig-publish text-center">
                <div class="gig-publish-icon text--success">
                    <i class="far fa-check-circle"></i>
                </div>

                @if ($gig->is_published == 1)
                    <h4 class="mb-3 draft-notify">@lang('Congratulations! Gig is published.')</h4>
                @else
                    <h4 class="mb-3 published-notify"> @lang('Almost Ready for Publishing')</h4>
                    <p class="mb-3 info">@lang('Your anticipation is appreciated as we put the finishing touches on our project. Exciting things are on the horizon. Are you ready to publish?')
                    </p>
                    <button class="btn btn--base" id="gigPublished" type="button">@lang('Publish Your Gig Now')</button>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $('#saveAndDraft').on('click', function() {
                gigFinalAction(0, $(this));
            });

            $('#gigPublished').on('click', function() {
                gigFinalAction(1, $(this));
            });

            function gigFinalAction(isPublished, btn) {
                var btnText = btn.text();
                var spinnerHtml = '<div class="spinner-border"></div>';
                var url = '{{ route('user.gig.store.publish', $gig->id) }}';
                var token = '{{ csrf_token() }}';
                btn.html(spinnerHtml + ' ' + (isPublished ? '@lang('Publishing')' : '@lang('Drafting')') + '...');
                btn.attr('disabled', true);

                var data = {
                    is_published: isPublished,
                    _token: token
                };
                setTimeout(() => {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: data,
                        success: function(response) {
                            if (response.success) {
                                if (response.status == 0) {
                                    notify('success', `@lang('Gig save and drafted successfully')`);
                                    $('.draft-notify').text(`@lang('Gig is Drafted')`);
                                } else {
                                    $('.published-notify').text(`@lang('Congratulations!')`);
                                }
                                setTimeout(() => {
                                    btn.hide();
                                    $('.info').hide();
                                    window.location.href = response.redirect_url;
                                }, 1000);
                            } else {
                                notify('error', response.message);
                            }
                        }
                    });
                }, 1000);
            }
        })(jQuery);
    </script>
@endpush
