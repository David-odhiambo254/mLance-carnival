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
                                <button class="btn btn--base btn--sm addEdu " type="button">
                                    <i class="las la-plus"></i> @lang('Add')
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="register" method="post">
                                @csrf
                                <div class="table-responsive--sm table-responsive">
                                    <table class="table table--light style--two custom-data-table">
                                        <thead>
                                            <tr>
                                                <th>@lang('College/Univertsity')</th>
                                                <th>@lang('Title')</th>
                                                <th class="text-start">@lang('Year')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (blank($user->educations))
                                                <tr>
                                                    <td><input class="form--control" name="educations[0][institute]"
                                                            type="text" required /></td>
                                                    <td><input class="form--control" name="educations[0][title]"
                                                            type="text" required /></td>
                                                    <td><input class="form--control has-remove" name="educations[0][year]"
                                                            type="number" required /></td>
                                                </tr>
                                            @else
                                                @foreach ($user->educations as $key => $education)
                                                    <tr>
                                                        <td><input class="form--control"
                                                                name="educations[{{ $key }}][institute]"
                                                                type="text" value="{{ $education->institute }}"
                                                                required /></td>
                                                        <td><input class="form--control"
                                                                name="educations[{{ $key }}][title]" type="text"
                                                                value="{{ $education->title }}" required /></td>
                                                        <td class="has-remove">
                                                            <input class="form--control "
                                                                name="educations[{{ $key }}][year]" type="number"
                                                                value="{{ $education->year }}" required />
                                                            @if (!$loop->first)
                                                                <span class="remove-btn">
                                                                    <i class="las la-times"></i>
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group text-end mt-4">
                                    <button class="btn btn--base" type="submit">@lang('Submit')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .has-remove {
            position: relative;
        }

        .has-remove .remove-btn {
            position: absolute;
            content: '';
            display: flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            background: hsl(var(--danger));
            top: 6px;
            right: 0px;
            color: hsl(var(--white));
            border-radius: 50%;
            font-size: 10px;
            cursor: pointer;
        }

        .table thead tr th:last-child,
        .table tbody tr td:last-child {
            padding-right: 8px;
        }

        tbody,
        td,
        tfoot,
        tr {
            border-style: none;
        }

        .table tbody tr td {
            padding-top: 11px;
            padding-bottom: 0px;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            function areRowInputsFilled(row) {
                var allFilled = true;
                row.find('input[required]').each(function() {
                    if ($(this).val() === '') {
                        allFilled = false;
                        return false;
                    }
                });
                return allFilled;
            }

            function updateAddButtonState() {
                var lastRow = $('.table--light tbody tr:last');
                var addButton = $('.addEdu');

                if (areRowInputsFilled(lastRow)) {
                    addButton.prop('disabled', false);
                } else {
                    addButton.prop('disabled', true);
                }
            }

            // Initially enable the "Add" button
            updateAddButtonState();


            $('.table--light tbody').on('input', 'tr:last input[required]', function() {
                updateAddButtonState();
            });


            $('.addEdu').on('click', function() {
                let length = $('.table--light tbody tr').length;
                var newRow = $(`
                                <tr>
                                    <td><input class="form--control" name="educations[${length}][institute]" type="text" required /></td>
                                    <td><input class="form--control" name="educations[${length}][title]" type="text" required /></td>
                                    <td class="has-remove disabled">
                                        <input class="form--control" name="educations[${length}][year]" type="number" required />
                                            <span class="has-remove-btn remove-btn">
                                                <i class="las la-times"></i>
                                            </span>
                                    </td>
                                </tr>
                            `);
                $('.table--light tbody').append(newRow);
                $(this).prop('disabled', true);
            });


            $(document).on('click', '.remove-btn', function() {
                var row = $(this).closest('tr');
                row.remove();
                updateAddButtonState();
            });
        })(jQuery);
    </script>
@endpush
