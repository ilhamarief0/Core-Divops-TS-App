@extends('layout.app')
@section('content')
<!--begin::Content wrapper-->
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
     <!--begin::Content-->
     <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!--begin::Row-->
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <!--begin::Col-->
                @foreach ($theCustomerSites as $customerSites)
                    <div class="col-xl-14">
                        <!--begin::Table widget 14-->
                        <div class="card card-flush h-md-100">
                            <!--begin::Header-->
                            <div class="card-header pt-7">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">{{ $customerSites['vendor'] }}</span>



                                </h3>
                                <!--end::Title-->

                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body pt-6">
                                <!--begin::Table container-->
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                                        <!--begin::Table head-->
                                        <thead>
                                            <tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
                                                <th class="p-0 pb-3 min-w-175px text-start">ITEM</th>
                                                {{-- <th class="p-0 pb-3 min-w-100px text-end">BUDGET</th>
                                <th class="p-0 pb-3 min-w-100px text-end">PROGRESS</th>
                                <th class="p-0 pb-3 min-w-175px text-end pe-12">STATUS</th> --}}
                                                <th class="p-0 pb-3 w-125px text-end pe-7">CHART</th>
                                                <th class="p-0 pb-3 w-50px text-end">VIEW</th>
                                            </tr>
                                        </thead>
                                        <!--end::Table head-->
                                        @foreach ($customerSites['data'] as $customerSite)
                                            <!--begin::Table body-->
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="d-flex justify-content-start flex-column">
                                                                <a href="{{ route('clientwebsitemonitoring.show', ['customerSite' => Crypt::encryptString($customerSite->id)]) }}"
                                                                    class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">
                                                                    {{ $customerSite->name }}
                                                                </a>
                                                                <span
                                                                    class="text-gray-500 fw-semibold d-block fs-7">{{ $customerSite->url }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end pe-0">
                                                        @livewire('uptime-badge', [
                                                            'customerSite' => $customerSite,
                                                            'uptimePoll' => request('uptime_poll', 0),
                                                        ])
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="{{ route('clientwebsitemonitoring.show', ['customerSite' => Crypt::encryptString($customerSite->id)]) }}"
                                                            class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                            <i class="ki-duotone ki-black-right fs-2 text-gray-500"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <!--end::Table body-->
                                        @endforeach


                                    </table>
                                </div>
                                <!--end::Table-->
                            </div>
                            <!--end: Card Body-->

                        </div>
                        <!--end::Table widget 14-->
                    </div>
                @endforeach
                <!--end::Col-->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>
<!--end::Content wrapper-->
@endsection
