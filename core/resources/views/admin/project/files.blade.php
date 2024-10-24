@extends('admin.layouts.app')
@section('panel')
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="order-details">

                        @include('admin.project.info')

                        @php
                            $projectActivities = $project->projectActivities->whereNotNull('files')->sortByDesc('created_at');
                        @endphp
                        @if ($project->status == Status::PROJECT_REPORTED)
                            <div class="chating_message mt-4">
                                <form class="form" id="filesForm" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <div class="input-images pb-3"></div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn--primary files-send-btn" type="button"> <i class="las la-cloud-upload-alt"></i>
                                            @lang('Upload')</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        <div class="order-messages-area mt-4">
                            <div class="order-messages-box">
                                @foreach ($projectActivities as $activity)
                                    <div class="order-messages-item">
                                        <div class="order-messagfe-top d-flex align-items-center justify-content-between">
                                            <span class="date">{{ showDateTime($activity->created_at, 'd M Y H:i A') }}</span>
                                            @if (!$activity->user_id && auth()->guard('admin') && $project->status == Status::PROJECT_REPORTED)
                                                <button class="btn btn--sm btn--danger text-white confirmationBtn" data-action="{{ route('admin.project.history.delete', $activity->id) }}" data-question="@lang('Are you sure to delete this file?')" type="button">
                                                    <i class="las la-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                        <div class="order-messages-item-content">
                                            <div class="order-messages-item__thumb">
                                                @if ($activity->user_id)
                                                    <img src="{{ avatar(@$activity->user->image ? getFilePath('userProfile') . '/' . @$activity->user->image : null) }}" alt="@lang('image')">
                                                @else
                                                    <img src="{{ avatar(getFilePath('adminProfile') . '/' . @$activity->admin->image) }}" alt="@lang('image')">
                                                @endif
                                            </div>
                                            <div class="order-messages-item__content">
                                                <h6 class="mb-0">
                                                    @if ($activity->user_id)
                                                        {{ @$activity->user->fullname }}
                                                    @else
                                                        {{ @$activity->admin->name }}
                                                    @endif
                                                </h6>
                                            </div>
                                        </div>
                                        <div class="mt-2 fs-14">
                                            <div class="order-files-wrapper">
                                                @foreach ($activity->files as $file)
                                                    <?php $extension = pathinfo($file, PATHINFO_EXTENSION); ?>
                                                    <a href="{{ route('admin.project.file.download', [$activity->project_id, encrypt($file)]) }}">
                                                        <i class="fas {{ getFileIcon(strtolower($extension)) }}"></i>
                                                        @lang('Download') {{ ucfirst($extension) }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/dont-edit-image-uploader.min.js') }}"></script>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/global/css/image-uploader.min.css') }}" rel="stylesheet">
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            //Image-file uploader-dropbox//

            let preloaded = [];
            $('.input-images').imageUploader({
                preloaded: preloaded,
                imagesInputName: 'files',
                preloadedInputName: 'old_file',
                extensions: ['.jpg', '.jpeg', '.png', '.gif', '.svg', '.zip', '.rar', '.pdf', '.3gp', '.mpeg3',
                    '.x-mpeg-3', '.mp4', '.mpeg', '.mpkg', '.doc', '.docx', '.gif', '.txt', '.wav', '.xls',
                    '.xlsx', '.7z'
                ],
                mimes: ['image/jpeg', 'image/png', 'image/gif', 'application/x-zip-compressed',
                    'application/zip', 'application/vnd.rar', 'audio/mpeg', 'audio/wav', 'video/mp4',
                    'video/mpeg', 'application/pdf', 'text/plain', 'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/x-7z-compressed', 'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ]
            });

            //files-store-ajax
            $('.files-send-btn').on('click', function() {
                var btnAfterSubmit = `<div class="spinner-border"></div>`;
                var btn = $(this);
                btn.html(btnAfterSubmit);
                btn.attr('disabled', true);

                //store
                var formData = new FormData($('#filesForm')[0]);
                var url = '{{ route('admin.project.upload.files', $project->id) }}';
                var token = '{{ csrf_token() }}';
                formData.append('enctype', 'multipart/form-data');
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
                                btn.html(
                                    `<i class="fas fa-upload"></i> @lang('Upload')`
                                );
                                btn.removeAttr('disabled');
                                location.reload();
                            } else {
                                notify('error', response.message);
                                btn.html(
                                    `<i class="fas fa-upload"></i> @lang('Upload')`
                                );
                                btn.removeAttr('disabled');
                            }
                        },
                        error: function(xhr, status, error) {
                            notify('error', error);
                        }
                    });
                }, 1000);
            });


        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .spinner-border {
            width: 1rem;
            height: 1rem;
        }
    </style>
@endpush
