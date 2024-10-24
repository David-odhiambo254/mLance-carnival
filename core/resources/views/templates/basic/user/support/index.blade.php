@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="project-area">
                    <div class="card custom--card">
                        <div class="card-header d-flex justify-content-between align-items-center gap-3 flex-wrap">
                            <h6 class="mb-0 py-2">{{ __($pageTitle) }}</h6>
                            <a class="btn btn-sm btn--base mb-2" href="{{ route('ticket.open') }}"> <i class="fa fa-plus"></i>
                                @lang('New Ticket')</a>
                        </div>
                        @if (!blank($supports))
                            <div class="table-responsive--sm table-responsive">
                                <table class="table table--light style--two custom-data-table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Subject')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Priority')</th>
                                            <th>@lang('Last Reply')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($supports as $support)
                                            <tr>
                                                <td> <a href="{{ route('ticket.view', $support->ticket) }}" class="fw-bold">
                                                        [@lang('Ticket')#{{ $support->ticket }}]
                                                        {{ __($support->subject) }} </a>
                                                </td>
                                                <td>
                                                    @php echo $support->statusBadge; @endphp
                                                </td>
                                                <td>
                                                    @if ($support->priority == Status::PRIORITY_LOW)
                                                        <span class="badge badge--dark">@lang('Low')</span>
                                                    @elseif($support->priority == Status::PRIORITY_MEDIUM)
                                                        <span class="badge  badge--warning">@lang('Medium')</span>
                                                    @elseif($support->priority == Status::PRIORITY_HIGH)
                                                        <span class="badge badge--danger">@lang('High')</span>
                                                    @endif
                                                </td>
                                                <td>{{ diffForHumans($support->last_reply) }} </td>

                                                <td>
                                                    <a href="{{ route('ticket.view', $support->ticket) }}"
                                                        class="btn btn--base btn-sm">
                                                        <i class="la la-desktop"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            @include($activeTemplate . 'partials.empty', ['message' => 'No ticket found'])
                        @endif

                        @if ($supports->hasPages())
                            <div class="card-footer">
                                {{ paginateLinks($supports) }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
