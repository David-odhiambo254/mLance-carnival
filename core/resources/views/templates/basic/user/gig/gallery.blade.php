@extends($activeTemplate . 'layouts.gig')
@section('gig-page')
    <form id="galleryForm">
        <div class="row gy-4">
            @if (!count($gig->images))
                <div class="col-lg-4 col-md-6">
                    <div class="box mb-3">
                        <div class="js--image-preview mainFile"></div>
                        <div class="upload-options firstUploadOption">
                            <label>
                                <input class="image-upload" name="gallery[]" type="file" accept=".jpg, .png, .jpeg">
                            </label>
                        </div>
                    </div>
                    <button class="btn btn--danger remove-btn btn--sm w-100 mt-2" type="button" disabled><i
                            class="las la-trash"></i> @lang('Remove')</button>
                </div>
            @endif
            @foreach ($gig->images ?? [] as $image)
                <div class="col-lg-4 col-md-6">
                    <div class="box mb-3">
                        <div class="js--image-preview mainFile js--no-default"
                            style="background-image: url({{ getImage(getFilePath('gig') . '/' . @$image->name) }})"></div>
                        <div class="upload-options firstUploadOption">
                            <label>
                                <input class="image-upload" name="gallery[]" type="file" accept=".jpg, .png, .jpeg">
                            </label>
                        </div>
                    </div>
                    <button class="btn btn--danger btn--sm w-100 removeImage" type="button"
                        data-id="{{ $image->id }}"><i class="las la-trash"></i> @lang('Remove')</button>
                </div>
            @endforeach
            <div class="col-lg-4 col-md-6 addImageArea">
                <div class="add-new-faq addNewImage">
                    <div class="add-new-faq-box">
                        <i class="las la-plus-circle"></i>
                        <p>@lang('Add New')</p>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <div class="modal" id="confirmModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0"> <i class="las la-trash text--danger"></i> @lang('Confirmation Alert!')</h5>
                            <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                        </div>
                        <p class="py-2"><small>@lang('Are you sure to remove this gig image')?</small></p>
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

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.addNewImage').on('click', function() {
                $(".addImageArea").before(`
                <div class="col-lg-4 col-md-6">
                    <div class="box mb-3">
                        <div class="js--image-preview mainFile"></div>
                        <div class="upload-options firstUploadOption">
                            <label>
                                <input type="file" class="image-upload" name="gallery[]" accept=".jpg, .png, .jpeg, .mp3, .mp4">
                            </label>
                        </div>
                    </div>
                    <button type="button" class="btn btn--danger remove-btn btn--sm w-100">@lang('Remove')</button>
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
                var btnName = `@lang('Save & Continue')<i class="las la-angle-right"></i>`;
                var btn = $(this);
                btn.html(btnAfterSubmit);
                btn.attr('disabled', true);
                //store
                var formData = new FormData($('#galleryForm')[0]);
                var url = '{{ route('user.gig.store.gallery', $gig->id) }}';
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
                                    notify('success', `@lang('Gig gallery images updated successfully')`);
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

            $('.removeImage').on('click', function(e) {
                let modal = $('#confirmModal');
                let action = "{{ route('user.gig.remove.gallery', ':id') }}";
                let id = $(this).data('id');

                modal.find('form').attr('action', action.replace(':id', id));
                modal.modal('show');

            });
        })(jQuery);
    </script>
@endpush
