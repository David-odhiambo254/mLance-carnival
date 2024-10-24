@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('Project Title')</th>
                                    <th>@lang('Buyer')</th>
                                    <th>@lang('Seller')</th>
                                    <th>@lang('Deadline')</th>
                                    <th>@lang('Order Price')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($projects as $project)
                                    @php
                                        $order = $project->order;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="user">
                                                <div class="thumb">
                                                    <img src="{{ getImage(getFilePath('gig') . '/' . thumbnail($project->gig->id)->name, getFileSize('gig')) }}"
                                                        alt="{{ __($project->gig->title) }}" class="plugin_bg">
                                                </div>
                                                <span class="name">{{ strLimit(__($project->gig->title), 50) }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="fw-bold">{{ $project->buyer->fullname }}</span>
                                            <br>
                                            <small>
                                                <a
                                                    href="{{ route('admin.users.detail', $project->buyer->id) }}"><span>@</span>{{ $project->buyer->username }}</a>
                                            </small>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $project->seller->fullname }}</span>
                                            <br>
                                            <small>
                                                <a
                                                    href="{{ route('admin.users.detail', $project->seller->id) }}"><span>@</span>{{ $project->seller->username }}</a>
                                            </small>
                                        </td>
                                        <td>
                                            <span class="fw-bold">
                                                @if (@$order->status == Status::ORDER_PENDING)
                                                    <span class="cursor-pointer" data-bs-toggle="tooltip"
                                                        title="@lang('Expected deadline will come after accepting the order')">-</span>
                                                @elseif(@$order->status == Status::ORDER_REJECTED)
                                                    @lang('N/A')
                                                @else
                                                    <span
                                                        class="value">{{ showDateTime(@$order->deadline, 'd M, Y h:i A') }}</span>
                                                    <br>
                                                    @if (now() > @$order->deadline)
                                                        <small class="text--warning">
                                                            {{ diffForHumans($order->deadline) }}</small>
                                                    @else
                                                        <small class="text--primary">
                                                            {{ diffForHumans($order->deadline) }}</small>
                                                    @endif
                                                @endif
                                            </span>
                                        </td>
                                        <td>{{ showAmount($project->order->price) }}</td>

                                        <td>@php echo $project->statusBadge; @endphp</td>

                                        <td>
                                            <div class="button--group">
                                                <a class="btn btn-sm btn-outline--primary"
                                                    href="{{ route('admin.project.details', $project->id) }}">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($projects->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($projects) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <x-search-form dateSearch="yes" placeholder="Title/Username" />
@endpush
