@extends('layout.app')
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxxl">
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1 me-5">
                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <input type="text" id="searchInput" class="form-control form-control-solid w-250px ps-13" placeholder="Cari Client" />

                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">

                                    <div class="d-none" id="delete-action">
                                        <button type="button" class="btn btn-danger me-3" id="delete-selected">Delete
                                            Selected</button>
                                    </div>

                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_add_client">
                                        <i class="ki-duotone ki-plus fs-2"></i> Tambah Data Client
                                    </button>

                            </div>
                        </div>
                    </div>
                    <div class="card-body py-4">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 data-tableclient">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-150px">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                            <label class="form-check-label" for="select-all">Select All</label>
                                        </div>
                                    </th>
                                    <th class="min-w-150px">Nama Client</th>
                                    <th class="min-w-150px">Bot Token</th>
                                    <th class="min-w-150px">Chat Id</th>
                                    <th class="text-epnd min-w-100px">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                @include('components.client.add-client-monitoring')
                @include('components.client.edit-client-monitoring')
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/custom/apps/client-monitoring-web/table.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/client-monitoring-web/add.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/client-monitoring-web/edit.js') }}"></script>
@endpush
