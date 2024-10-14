@extends('layout.app')
@push('styles')
    <link rel="stylesheet" href="{{ url('css/plugins/jquery.datetimepicker.css') }}">
@endpush
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
                        <li class="breadcrumb-item text-muted">
                            <a href="index.html" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Dashboards</li>
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

                                <h4 class="">Website : {{ $customerSite->name }}</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td>Site Name</td>
                                            <td>{{ $customerSite->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Site Url</td>
                                            <td><a target="_blank"
                                                    href="{{ $customerSite->url }}">{{ $customerSite->url }}</a></td>
                                        </tr>
                                        <tr>
                                            <td>Site Client</td>
                                            <td>{{ $customerSite->client->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Site Type</td>
                                            <td>{{ $customerSite->type->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Site Status</td>
                                            <td>{{ $customerSite->is_active }}</td>
                                        </tr>
                                        <tr>
                                            <td>Site Check Interval</td>
                                            <td>
                                                Every {{ $customerSite->check_interval }} Minutes
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Site Priority Code</td>
                                            <td>{{ $customerSite->priority_code }}</td>
                                        </tr>
                                        <tr>
                                            <td>Site Warning Treshold</td>
                                            <td>{{ $customerSite->warning_threshold }} ms
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Site Down Treshold</td>
                                            <td>{{ $customerSite->down_threshold }} ms</td>
                                        </tr>
                                        <tr>
                                            <td>Site Notify User Interval</td>
                                            <td>
                                                Every {{ $customerSite->notify_user_interval }} Minutes
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Site Last Check At</td>
                                            <td>{{ optional($customerSite->last_check_at)->diffForHumans() }}</td>
                                        </tr>
                                        <tr>
                                            <td>Site Last Notify User At</td>
                                            <td>{{ optional($customerSite->last_notify_user_at)->diffForHumans() }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Site Created At</td>
                                            <td>{{ $customerSite->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <td>Site Updated At</td>
                                            <td>{{ $customerSite->updated_at }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                {{-- <a href="{{ route('customer_sites.edit', $customerSite) }}" class="btn btn-warning">
                                    {{ __('customer_site.edit') }}
                                </a> --}}
                                {{-- <a href="{{ route('dashboard.view') }}" class="btn btn-primary">
                                    {{ __('app.back_to_dashboard') }}
                                </a> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 order-1 order-md-2">
                        <div class="py-4 py-md-0 clearfix">
                            <div class="btn-group mb-3" role="group">
                                <a href="{{ route(Route::currentRouteName(), [Crypt::encryptString($customerSite->id), 'time_range' => '1h']) }}"
                                    class="px-2 btn btn-outline-primary {{ $timeRange == '1h' ? 'active' : '' }}">1h</a>
                                <a href="{{ route(Route::currentRouteName(), [Crypt::encryptString($customerSite->id), 'time_range' => '6h']) }}"
                                    class="px-2 btn btn-outline-primary {{ $timeRange == '6h' ? 'active' : '' }}">6h</a>
                                <a href="{{ route(Route::currentRouteName(), [Crypt::encryptString($customerSite->id), 'time_range' => '24h']) }}"
                                    class="px-2 btn btn-outline-primary {{ $timeRange == '24h' ? 'active' : '' }}">24h</a>
                                <a href="{{ route(Route::currentRouteName(), [Crypt::encryptString($customerSite->id), 'time_range' => '7d']) }}"
                                    class="px-2 btn btn-outline-primary {{ $timeRange == '7d' ? 'active' : '' }}">7d</a>
                                <a href="{{ route(Route::currentRouteName(), [Crypt::encryptString($customerSite->id), 'time_range' => '14d']) }}"
                                    class="px-2 btn btn-outline-primary {{ $timeRange == '14d' ? 'active' : '' }}">14d</a>
                                <a href="{{ route(Route::currentRouteName(), [Crypt::encryptString($customerSite->id), 'time_range' => '30d']) }}"
                                    class="px-2 btn btn-outline-primary {{ $timeRange == '30d' ? 'active' : '' }}">30d</a>
                                <a href="{{ route(Route::currentRouteName(), [Crypt::encryptString($customerSite->id), 'time_range' => '3Mo']) }}"
                                    class="px-2 btn btn-outline-primary {{ $timeRange == '3Mo' ? 'active' : '' }}">3Mo</a>
                                <a href="{{ route(Route::currentRouteName(), [Crypt::encryptString($customerSite->id), 'time_range' => '6Mo']) }}"
                                    class="px-2 btn btn-outline-primary {{ $timeRange == '6Mo' ? 'active' : '' }}">6Mo</a>
                            </div>
                            <div class="float-end">
                                <form method="GET" class="row row-cols-lg-auto g-2 align-items-center">
                                    <div class="col">
                                        <input type="text" name="start_time"
                                            value="{{ $startTime->format('Y-m-d H:i') }}"
                                            class="date_time_select form-control" id="start_time_picker"
                                            style="width:150px">
                                    </div>
                                    <div class="col">
                                        <input type="text" name="end_time" value="{{ $endTime->format('Y-m-d H:i') }}"
                                            class="date_time_select form-control" id="end_time_picker" style="width:150px">
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-info mr-1">View Report</button>
                                    </div>
                                </form>
                            </div>


                        </div>
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
@push('scripts')
    <script src="{{ url('js/jquery.min.js') }}"></script>
    <script src="{{ url('js/plugins/jquery.datetimepicker.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.date_time_select').datetimepicker({
                format: 'Y-m-d H:i',
                closeOnTimeSelect: true,
                scrollInput: false,
                dayOfWeekStart: 1
            });
        });
    </script>
@endpush
