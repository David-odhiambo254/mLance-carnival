@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                @include($activeTemplate . 'partials.profile_header')
                <div class="p-0 bg--white">
                    <div class="card custom--card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title">{{ __($pageTitle) }}</h5>
                                <button class="btn btn--sm btn--base portfolioModalBtn" data-modal_title="@lang('Add New Portfolio')">
                                    <i class="las la-plus"></i>@lang('Add New')
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (!blank($portfolios))
                                <div class="table-responsive--sm table-responsive">
                                    <table class="table table--light style--two custom-data-table">
                                        <thead>
                                            <tr>
                                                <th class="eight-percent">@lang('Image')</th>
                                                <th class="thirty-percent">@lang('Title')</th>
                                                <th>@lang('Description')</th>
                                                <th class="eight-percent">@lang('Action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($portfolios as $portfolio)
                                                <tr>
                                                    <td>
                                                        <div class="avatar">
                                                            <img src="{{ getImage(getFilePath('userPortfolio') . '/' . @$portfolio->image, getFileSize('userPortfolio')) }}"
                                                                alt="@lang('image')">
                                                        </div>
                                                    </td>
                                                    <td>

                                                        {{ strLimit(__($portfolio->title), 50) }}
                                                    </td>

                                                    <td>
                                                        @php echo __(strLimit(strip_tags($portfolio->description), 150));@endphp
                                                    </td>
                                                    @php
                                                        $portfolio->image_with_path = getImage(
                                                            getFilePath('userPortfolio') . '/' . $portfolio->image,
                                                            getFileSize('userPortfolio'),
                                                        );
                                                    @endphp
                                                    <td>
                                                        <div class="btn-group">
                                                            <a class="invest-details-link" data-bs-toggle="dropdown"
                                                                href="#">
                                                                <i class="las la-ellipsis-v"></i>
                                                            </a>
                                                            <ul class="dropdown-menu px-2">
                                                                <li>
                                                                    <button class="dropdown-item portfolioModalBtn"
                                                                        data-modal_title="@lang('Update Portfolio')"
                                                                        data-resource="{{ $portfolio }}">
                                                                        <i class="las la-pen"></i> @lang('Edit')
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <button class="dropdown-item deleteBtn"
                                                                        data-action="{{ route('user.profile.portfolio.delete', $portfolio->id) }}">
                                                                        <i class="las la-trash"></i> @lang('Delete')
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                @include($activeTemplate . 'partials.empty', [
                                    'message' => 'No portfolios added yet',
                                ])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $portfolioImage = getImage(getFilePath('userPortfolio'), getFileSize('userPortfolio'));
    @endphp

    <div class="modal" id="portfolioModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('user.profile.portfolio.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0 modal-title"></h5>
                            <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                        </div>

                        <div class="form-group mt-4">
                            <div class="profile-thumb-wrapper">
                                <div class="profile-thumb justify-content-center">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview"
                                            style="background-image: url({{ $portfolioImage }});">
                                        </div>
                                        <div class="avatar-edit">
                                            <input class="profilePicUpload" id="profilePicUpload1" name="image"
                                                type='file' accept=".png, .jpg, .jpeg" />
                                            <label class="btn btn--base mb-0" for="profilePicUpload1"><i
                                                    class="la la-camera"></i></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>@lang('Title')</label>
                            <input class="form--control" name="title" type="text" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Description')</label>
                            <textarea class="form--control nicEdit" name="description"></textarea>
                        </div>

                        <div class="text-end">
                            <button class="btn btn--sm btn--base" type="submit">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="confirmDeleteModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0"> <i class="las la-trash text--danger"></i> @lang('Confirmation Alert!')</h5>
                            <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                        </div>
                        <p class="py-2"><small>@lang('Are you certain about deleting this portfolio? Once confirmed, this action can\'t be undone!')</small></p>
                        <div class="text-end">
                            <button class="btn btn--sm btn-dark" data-bs-dismiss="modal"
                                type="button">@lang('No')</button>
                            <button class="btn btn--sm btn--base deleteBtn" type="submit">@lang('Yes')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .eight-percent {
            width: 12%;
        }

        .thirty-percent {
            width: 30%;
        }

        .avatar img {
            width: 35px;
            height: 35px;
            border-radius: 5px;
        }

        .avatar-preview .profilePicPreview {
            border-radius: 5px !important;
        }

        .nicEdit-main {
            outline: none !important;
            width: 100% !important
        }

        .nicEdit-custom-main {
            border-right-color: #cacaca73 !important;
            border-bottom-color: #cacaca73 !important;
            border-left-color: #cacaca73 !important;
            border-radius: 0 0 5px 5px !important;
            width: 100% !important;
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

        .panel-custom-width {
            width: 100% !important;
        }

        .table-responsive {
            min-height: 300px;
            background: transparent
        }

        .card {
            box-shadow: none;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/nicEdit.js') }}"></script>
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
                    $('.nicEdit-panelContain').parent('div').addClass('panel-custom-width')
                });
            });

            //delete-modal
            $(document).on('click', '.deleteBtn', function() {
                let action = $(this).data('action');
                var modal = $("#confirmDeleteModal");
                let form = modal.find("form");
                form.attr('action', action);
                modal.modal('show');
            });

            //update
            let portfolioModal = $("#portfolioModal");
            let form = portfolioModal.find("form");
            const action = form[0] ? form[0].action : null;
            $(document).on("click", ".portfolioModalBtn", function() {
                let data = $(this).data();
                let resource = data.resource ?? null;
                portfolioModal.find(".modal-title").text(`${data.modal_title}`);

                if (!resource) {
                    form[0].reset();
                    form[0].action = `${action}`;
                    portfolioModal.find('.nicEdit-main').html(''); // Clear NicEdit content

                }
                if (resource) {
                    form[0].action = `${action}/${resource.id}`;
                    // If form has image
                    if (resource.image_with_path) {
                        portfolioModal
                            .find(".profilePicPreview")
                            .css("background-image", `url(${resource.image_with_path})`);
                    }
                    portfolioModal.find("[name='title']").val(resource.title);
                    portfolioModal.find(".nicEdit-main").html(resource.description);
                }
                portfolioModal.modal("show");
            });


            //image preview
            function proPicURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var preview = $(input).parents('.profile-thumb').find('.profilePicPreview');
                        $(preview).css('background-image', 'url(' + e.target.result + ')');
                        $(preview).addClass('has-image');
                        $(preview).hide();
                        $(preview).fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $(".profilePicUpload").on('change', function() {
                proPicURL(this);
            });

            $('#portfolioModal').on('hidden.bs.modal', function() {
                $(this).find('.profilePicPreview').css('background-image', `url('{{ $portfolioImage }}')`)
            })


        })(jQuery);
    </script>
@endpush
