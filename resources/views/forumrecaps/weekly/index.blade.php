@extends('layout.app')
@section('content')
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Account Overview</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="index.html" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Account</li>
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
    <div id="kt_app_content_container" class="app-container container-xxl d-flex justify-content-center">
        <!--begin::Row-->
        <div class="row gy-5 g-xl-10 w-100">
            <!--begin::Col-->
            <div class="col-xl-8 mx-auto">
                <!--begin::Table Widget 5-->
                <div class="card card-flush h-xl-100">
                    <!--begin::Card header-->
                    <div class="card-header pt-7">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span id="card-title" class="card-label fw-bold text-gray-900">Recap Mingguan</span>
                            <span id="card-subtitle" class="text-gray-500 mt-1 fw-semibold fs-6">Minggu 1 Bulan Agustus 2024</span>
                        </h3>
                        <!--end::Title-->

                        <!--begin::Actions-->
                        <div class="card-toolbar">
                            <!--begin::Filters-->
                            <div class="d-flex flex-stack flex-wrap gap-4">
                                <div class="d-flex align-items-center fw-bold">
                                    <div class="text-muted fs-7 me-2">Minggu</div>
                                    <select id="filter-minggu" class="form-select form-select-transparent text-gray-900 fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option">
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                    </select>
                                </div>
                                <div class="d-flex align-items-center fw-bold">
                                    <div class="text-muted fs-7 me-2">Bulan</div>
                                    <select id="filter-bulan" class="form-select form-select-transparent text-gray-900 fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option">
                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                                <div class="d-flex align-items-center fw-bold">
                                    <div class="text-muted fs-7 me-2">Tahun</div>
                                    <select id="filter-tahun" class="form-select form-select-transparent text-gray-900 fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option">
                                        <option>2024</option>
                                        <option>2025</option>
                                        <option>2026</option>
                                    </select>
                                </div>
                                <button id="filter-tampilkan" class="btn btn-light btn-sm">Tampilkan</button>
                            </div>
                            <!--end::Filters-->
                        </div>
                        <!--end::Actions-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-3 data-tableforumweeklyview">
                            <!--begin::Table head-->
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-150px">Divisi</th>
                                    <th class="text-end pe-3 min-w-100px">Total Postingan</th>
                                </tr>
                            </thead>
                            <!--end::Table head-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Table Widget 5-->
            </div>
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

@push('scripts')
<script type="text/javascript">
$(function () {
    var table = $('.data-tableforumweeklyview').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('weeklyrecaps.index') }}",
            data: function (d) {
                d.minggu = $('#filter-minggu').val();
                d.bulan = $('#filter-bulan').val();
                d.tahun =   $('#filter-tahun').val();
            },
            dataSrc: function (json) {
                var minggu = $('#filter-minggu').find(":selected").text();
                var bulan = $('#filter-bulan').find(":selected").text();
                var tahun = $('#filter-tahun').find(":selected").text();

                // Update title and subtitle
                $('#card-title').text('Recap Mingguan');
                $('#card-subtitle').text('Minggu ' + minggu + ' Bulan ' + bulan + ' ' + tahun);

                return json.data;
            }
        },
        columns: [
            { data: 'divisi', name: 'divisi' },
            { data: 'total_postingan', name: 'total_postingan' }
        ]
    });

    $('#filter-tampilkan').click(function() {
        table.draw(); // Refresh DataTable dengan filter baru
    });
});

</script>
@endpush
