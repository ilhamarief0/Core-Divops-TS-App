@extends('layout.app')
@section('content')
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Dashboard Utama</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="index.html" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Dashboards</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row">
                    <div class="col-md-4 order-2 order-md-1">
                        <div class="card">
                            <div class="card-header mt-10">

                                <h4 class="">{{ __('customer_site.customer_site') }}</h4>
                            </div>
                            <div class="card-body">
                                {{-- <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td>{{ __('customer_site.name') }}</td>
                                            <td>{{ $customerSite->name }}</td>
                                        </tr>
                                        @auth
                                            <tr>
                                                <td>{{ __('customer_site.url') }}</td>
                                                <td><a target="_blank"
                                                        href="{{ $customerSite->url }}">{{ $customerSite->url }}</a></td>
                                            </tr>
                                        @endauth
                                        <tr>
                                            <td>{{ __('vendor.vendor') }}</td>
                                            <td>{{ $customerSite->vendor->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('customer_site.type') }}</td>
                                            <td>{{ $customerSite->type->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('app.status') }}</td>
                                            <td>{{ $customerSite->is_active }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('customer_site.check_interval') }}</td>
                                            <td>
                                                {{ __('time.every') }}
                                                {{ $customerSite->check_interval }}
                                                {{ trans_choice('time.minutes', $customerSite->check_interval) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('customer_site.priority_code') }}</td>
                                            <td>{{ $customerSite->priority_code }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('customer_site.warning_threshold') }}</td>
                                            <td>{{ $customerSite->warning_threshold }} {{ __('time.miliseconds') }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('customer_site.down_threshold') }}</td>
                                            <td>{{ $customerSite->down_threshold }} {{ __('time.miliseconds') }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('customer_site.notify_user_interval') }}</td>
                                            <td>
                                                {{ __('time.every') }}
                                                {{ $customerSite->notify_user_interval }}
                                                {{ trans_choice('time.minutes', $customerSite->notify_user_interval) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('customer_site.last_check_at') }}</td>
                                            <td>{{ optional($customerSite->last_check_at)->diffForHumans() }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('customer_site.last_notify_user_at') }}</td>
                                            <td>{{ optional($customerSite->last_notify_user_at)->diffForHumans() }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('app.created_at') }}</td>
                                            <td>{{ $customerSite->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('app.updated_at') }}</td>
                                            <td>{{ $customerSite->updated_at }}</td>
                                        </tr>
                                    </tbody>
                                </table> --}}
                            </div>
                            <div class="card-footer">
                                {{-- @can('update', $customerSite)
                                    {{ link_to_route('customer_sites.edit', __('customer_site.edit'), [$customerSite], ['class' => 'btn btn-warning', 'id' => 'edit-customer_site-' . $customerSite->id]) }}
                                @endcan
                                @auth
                                    {{ link_to_route('dashboard.view', __('app.back_to_dashboard'), [], ['class' => 'btn btn-primary']) }}
                                @endauth --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 order-1 order-md-2">
                        <div class="py-4 py-md-0 clearfix">
                            <div class="btn-group mb-3" role="group">
                                {{-- {{ link_to_route(Route::currentRouteName(), '1h', [$customerSite, 'time_range' => '1h'], ['class' => 'px-2 btn btn-outline-primary' . ($timeRange == '1h' ? ' active' : '')]) }}
                                {{ link_to_route(Route::currentRouteName(), '6h', [$customerSite, 'time_range' => '6h'], ['class' => 'px-2 btn btn-outline-primary' . ($timeRange == '6h' ? ' active' : '')]) }}
                                {{ link_to_route(Route::currentRouteName(), '24h', [$customerSite, 'time_range' => '24h'], ['class' => 'px-2 btn btn-outline-primary' . ($timeRange == '24h' ? ' active' : '')]) }}
                                {{ link_to_route(Route::currentRouteName(), '7d', [$customerSite, 'time_range' => '7d'], ['class' => 'px-2 btn btn-outline-primary' . ($timeRange == '7d' ? ' active' : '')]) }}
                                {{ link_to_route(Route::currentRouteName(), '14d', [$customerSite, 'time_range' => '14d'], ['class' => 'px-2 btn btn-outline-primary' . ($timeRange == '14d' ? ' active' : '')]) }}
                                {{ link_to_route(Route::currentRouteName(), '30d', [$customerSite, 'time_range' => '30d'], ['class' => 'px-2 btn btn-outline-primary' . ($timeRange == '30d' ? ' active' : '')]) }}
                                {{ link_to_route(Route::currentRouteName(), '3Mo', [$customerSite, 'time_range' => '3Mo'], ['class' => 'px-2 btn btn-outline-primary' . ($timeRange == '3Mo' ? ' active' : '')]) }}
                                {{ link_to_route(Route::currentRouteName(), '6Mo', [$customerSite, 'time_range' => '6Mo'], ['class' => 'px-2 btn btn-outline-primary' . ($timeRange == '6Mo' ? ' active' : '')]) }} --}}
                            </div>
                            <div class="float-end">
                                <form method="GET" action="" class="row row-cols-lg-auto g-2 align-items-center">
                                    {{-- {{ Form::open(['method' => 'get', 'class' => 'row row-cols-lg-auto g-2 align-items-center']) }} --}}
                                    <div class="col">
                                        {{-- {{ Form::text('start_time', $startTime->format('Y-m-d H:i'), ['class' => 'date_time_select form-control', 'style' => 'width:150px']) }} --}}
                                    </div>
                                    <div class="col">
                                        {{-- {{ Form::text('end_time', $endTime->format('Y-m-d H:i'), ['class' => 'date_time_select form-control', 'style' => 'width:150px']) }} --}}
                                    </div>
                                    <div class="col">
                                        {{-- {{ Form::submit('View Report', ['class' => 'btn btn-info mr-1']) }} --}}
                                        {{-- {{ link_to_route('customer_sites.show', __('app.reset'), $customerSite, ['class' => 'btn btn-secondary']) }} --}}
                                    </div>
                                    {{-- {{ Form::close() }} --}}
                                </form>
                            </div>
                        </div>

                        @auth
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    {{ link_to_route('customer_sites.show', __('monitoring_log.graph'), [$customerSite->id] + request(['time_range', 'start_time', 'end_time']), ['class' => 'nav-link ' . (in_array(Request::segment(3), [null]) ? 'active' : '')]) }}
                                </li>
                                <li class="nav-item">
                                    {{ link_to_route('customer_sites.timeline', __('monitoring_log.monitoring_log'), [$customerSite->id] + request(['time_range', 'start_time', 'end_time']), ['class' => 'nav-link ' . (in_array(Request::segment(3), ['timeline']) ? 'active' : '')]) }}
                                </li>
                            </ul>
                        @else
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    {{ link_to_route('customer_sites.public-show', __('monitoring_log.graph'), [$customerSite->id] + request(['time_range', 'start_time', 'end_time']), ['class' => 'nav-link ' . (in_array(Request::segment(4), [null]) ? 'active' : '')]) }}
                                </li>
                                <li class="nav-item">
                                    {{ link_to_route('customer_sites.public-timeline', __('monitoring_log.monitoring_log'), [$customerSite->id] + request(['time_range', 'start_time', 'end_time']), ['class' => 'nav-link ' . (in_array(Request::segment(3), ['timeline']) ? 'active' : '')]) }}
                                </li>
                            </ul>
                        @endauth

                        @yield('customer_site_content')
                    </div>
                </div>
                <br>
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ url('css/plugins/jquery.datetimepicker.css') }}">
@endpush

@push('scriptsdatapicker')
    <script src="{{ url('js/jquery.min.js') }}"></script>
    <script src="{{ url('js/plugins/jquery.datetimepicker.js') }}"></script>
    <script>
        $('.date_time_select').datetimepicker({
            format: 'Y-m-d H:i',
            closeOnTimeSelect: true,
            scrollInput: false,
            dayOfWeekStart: 1
        });
    </script>
@endpush
