@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="sticky-order-header">
                    <div class="order-details-tabs">
                        <a class="{{ menuActive('user.project.pending') }}" href="{{ route('user.project.pending') }}">
                            <i class="las la-spinner"></i> @lang('Pending')</a>
                        <a class="{{ menuActive('user.project.accepted') }}" href="{{ route('user.project.accepted') }}">
                            <i class="las la-check-circle"></i> @lang('Accepted')</a>
                        <a class="{{ menuActive('user.project.rejected') }}" href="{{ route('user.project.rejected') }}"> <i
                               class="las la-times-circle"></i>
                            @lang('Rejected')</a>
                        <a class="{{ menuActive('user.project.reported') }}" href="{{ route('user.project.reported') }}"> <i
                               class="las la-ban"></i>
                            @lang('Reported')</a>
                        <a class="{{ menuActive('user.project.completed') }}" href="{{ route('user.project.completed') }}"><i class="las la-check-double"></i>
                            @lang('Completed')</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="p-0 bg--white">
                    @if (!blank($projects))
                        <div class="table-responsive--sm table-responsive">
                            <table class="table table--light style--two custom-data-table">
                                <thead>
                                    <tr>
                                        <th>@lang('Project Title')</th>
                                        <th>@lang('Buyer / Seller')</th>
                                        <th>@lang('Deadline')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $project)
                                        @php
                                            $order = $project->order;
                                            $deadline = optional($order->deadline)->instanceOf(\Carbon\Carbon::class) ? $order->deadline : Carbon\Carbon::parse($order->deadline);
                                            $daysLeft = $deadline ? $deadline->diffInDays(now()) : null;
                                        @endphp
                                        <tr>
                                            <td><a class="project-title border-effect" href="{{ route('gig.explore', $project->gig->id) }}">
                                                    {{ strLimit(__($project->gig->title), 50) }}
                                                </a>
                                            </td>
                                            <td><a class="border-effect-bottom" href="{{ route('user.project.details', $project->id) }}">
                                                    @if ($project->buyer_id == auth()->id())
                                                        {{ $project->seller->fullname }} <small>(@lang('Seller'))</small>
                                                    @else
                                                        {{ $project->buyer->fullname }} <small>(@lang('Buyer'))</small>
                                                    @endif
                                                </a>
                                            </td>
                                            <td>
                                                <span class="fw-bold">
                                                    @if (@$order->status == Status::ORDER_PENDING)
                                                        <span><small>@lang('Expected deadline will come after accepting the order')</small></span>
                                                    @elseif(@$order->status == Status::ORDER_REJECTED)
                                                        @lang('N/A')
                                                    @else
                                                        <span class="value">{{ showDateTime(@$order->deadline, 'd M, Y') }}</span>
                                                        <br>
                                                        @if (now() > @$order->deadline)
                                                            <small class="text--warning"> {{ diffForHumans($order->deadline) }}</small>
                                                        @else
                                                            <small class="text--primary"> {{ diffForHumans($order->deadline) }}</small>
                                                        @endif
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    echo $project->statusBadge;
                                                @endphp
                                            </td>
                                            <td>
                                                <a class="invest-details-link" href="{{ route('user.project.details', $project->id) }}">
                                                    <i class="las la-angle-right"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($projects->hasPages())
                            <div class="pb-3 mx-4">
                                {{ $projects->links() }}
                            </div>
                        @endif
                    @else
                        @include($activeTemplate . 'partials.empty', ['message' => 'Project not found'])
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
