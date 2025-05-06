@extends('layout.app')
{{-- @push('token')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush --}}

@push('styles')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush


@section('content')
    <x-molecules.card>
        <x-slot:header class="pt-6 border-0">
            <x-slot:title>
                <div class="my-1 d-flex align-items-center position-relative me-5">
                    <x-atoms.icon class="position-absolute ms-5" icon="magnifier" path="2" size="3" />
                    <x-atoms.input data-kt-workdays-table-filter="search" class="w-250px ps-13"
                        placeholder="Cari hari kerja" />
                </div>
            </x-slot:title>
            <x-slot:toolbar>
                <div class="d-flex justify-content-end">
                    <x-atoms.button id="button_add_workday" class="me-4" color="primary" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_add_workday">
                        <x-atoms.icon class="fs-3" icon="plus-square" path="3" />
                        Tambah Data
                    </x-atoms.button>
                </div>
            </x-slot:toolbar>
        </x-slot:header>
        <x-slot:body class="py-4">
            <input type="hidden" id="table-url" value="{{ route('clientwebsitemonitoring.datatable') }}">
            <x-molecules.table class="mb-0 fs-6 gy-5" id="kt_workdays_table">
                <x-slot:head>
                    <tr class="text-gray-400 text-start fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">Nama</th>
                        <th class="min-w-125px">URL Website</th>
                        <th class="min-w-125px text-end">Status</th>
                        <th class="min-w-125px text-end">Client</th>
                        <th class="min-w-125px text-end">Type</th>
                        <th class="text-end min-w-100px">Actions</th>
                    </tr>
                </x-slot:head>
                <x-slot:body>
                </x-slot:body>
            </x-molecules.table>
        </x-slot:body>
    </x-molecules.card>

    <!--begin::Modal Add Workday-->
    <x-molecules.modal id="kt_modal_add_workday" class="mw-650px">
        <x-slot:title>Tambah Hari Kerja</x-slot:title>
        <x-slot:body>
            <form id="kt_modal_add_workday_form" class="form" action="#">
                <div class="fv-row mb-7">
                    <x-atoms.label value="Tahun Bulan" class="mb-2 fs-6 fw-semibold" />
                    <x-atoms.input id="add-month-year-picker" name="month_year" placeholder="Masukkan bulan tahun" />
                </div>
                <div class="fv-row mb-7">
                    <x-atoms.label class="mb-2 fs-6 fw-semibold" value="Jumlah Hari Kerja" />
                    <x-atoms.input type="number" name="amount" placeholder="Masukkan jumlah hari kerja" />
                </div>
                <div class="text-center pt-15">
                    <x-atoms.button type="reset" class="me-3" color="light"
                        data-kt-workdays-modal-action="close">Close</x-atoms.button>
                    <x-atoms.button type="submit" id="submit_add_workday" color="primary"
                        data-url="{{ route('clientwebsitemonitoring.store') }}" data-kt-workdays-modal-action="submit"></x-atoms.button>
                </div>
            </form>
        </x-slot:body>
    </x-molecules.modal>
    <!--end::Modal Add Workday-->

    <!--begin::Modal Edit Workday-->
    <x-molecules.modal id="kt_modal_edit_workday" class="mw-650px">
        <x-slot:title>Edit Hari Kerja</x-slot:title>
        <x-slot:body>
            <form id="kt_modal_edit_workday_form" class="form" method="POST" enctype="multipart/form-data">
                <div class="fv-row mb-7">
                    <x-atoms.label value="Tahun Bulan" class="mb-2 fs-6 fw-semibold" />
                    <x-atoms.input id="edit-month-year-picker" name="month_year" placeholder="Masukkan bulan tahun" />
                </div>
                <div class="fv-row mb-7">
                    <x-atoms.label class="mb-2 fs-6 fw-semibold" value="Jumlah Hari Kerja" />
                    <x-atoms.input type="number" name="amount" min="1" max="31"
                        placeholder="Masukkan jumlah hari kerja" />
                </div>
                <div class="text-center pt-15">
                    <x-atoms.button type="reset" class="me-3" color="light"
                        data-kt-workdays-modal-action="close">Close</x-atoms.button>
                    <x-atoms.button type="submit" id="submit_edit_workday" color="primary" data-id=""
                        data-kt-workdays-modal-action="submit"></x-atoms.button>
                </div>
            </form>
        </x-slot:body>
    </x-molecules.modal>
    <!--end::Modal Edit Workday-->

    @push('scripts')
        <script src="{{ asset('assets/js/custom/apps/website-monitoring-web/table.js') }}"></script>
    @endpush
@endsection
