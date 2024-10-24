@extends($activeTemplate . 'layouts.gig')
@section('gig-page')
    <div class="gig-create__price__inner pb-20">
        <form id="pricingForm">
            <table class="table pricing__table gig-create__price">
                <thead>
                    <tr>
                        <th><span class="gig-package">@lang('Packages')</span></th>
                        @foreach ($packages as $package)
                            <th>
                                <span class="gig-package">{{ __($package->name) }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>@lang('Name')</td>
                        @foreach ($packages as $package)
                            <td>
                                <div class="gig-price__item-packname-form">
                                    <input class="form--control" name="name[]" type="text" value="{{ @$package->gigPricing($gig->id)->name }}" placeholder="@lang('Name your package')">
                                </div>
                            </td>
                        @endforeach
                    </tr>

                    <tr>
                        <td>@lang('Describe')</td>
                        @foreach ($packages as $package)
                            <td>
                                <div class="gig-price__item-packname-form">
                                    <textarea class="form--control" name="description[]" placeholder="@lang('Describe the details of your offering')">{{ @$package->gigPricing($gig->id)->description }}</textarea>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                    <x-viser-gig-form identifier="id" identifierValue="{{ $gig->category->form_id }}" packages="{{ $packages }}" />
                        
                    <tr>
                        <td>@lang('Price')</td>
                        @foreach ($packages as $package)
                            <td>
                                @php
                                    $price = '';
                                    if (getAmount(@$package->gigPricing($gig->id)->price)) {
                                        $price = getAmount(@$package->gigPricing($gig->id)->price);
                                    }
                                @endphp
                                <div class="input-group">
                                    <span class="input-group-text">{{ gs('cur_sym') }}</span>
                                    <input class="form--control form-control" name="price[]" type="number" value="{{ $price }}" aria-label="Dollar amount (with dot and two decimal places)" step="any" step="any" placeholder="@lang('Enter package price')">
                                </div>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>

        </form>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $('#saveAndContinue').on('click', function() {

                var btnAfterSubmit = `<div class="spinner-border"></div> @lang('Saving')...`;
                var btnName = `@lang('Save & Continue') <i class="las la-angle-right"></i>`;
                var btn = $(this);
                btn.html(btnAfterSubmit);
                btn.attr('disabled', true);

                //store
                var formData = new FormData($('#pricingForm')[0]);
                var url = '{{ route('user.gig.store.pricing', $gig->id) }}';
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
                                    notify('success', `@lang('Gig pricing updated successfully')`);
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
