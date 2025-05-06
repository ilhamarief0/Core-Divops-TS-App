@extends('layout.app')

<style>
    .role-label {
    background-color: #0088ff; /* Warna latar belakang hijau */
    color: white;              /* Teks putih */
    padding: 5px 10px;         /* Padding dalam */
    border-radius: 5px;        /* Sudut membulat */
    font-weight: bold;          /* Teks tebal */
    font-size: 1rem;           /* Ukuran teks */
    text-transform: uppercase;
}
</style>

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Dashboard Utama</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="index.html" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-500 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Dashboards</li>
                </ul>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            @if (Auth()->user()->is_password_reseted === 0)
            <!--begin::Alert-->
            <div class="alert alert-dismissible bg-danger d-flex flex-column flex-sm-row p-5 mb-10">
                <div class="d-flex flex-column text-light pe-0 pe-sm-10">
                    <h4 class="mb-2 light">Reset Password Anda!</h4>
                    <span>Untuk menjaga keamanan akun anda, Silahkan melakukan reset password pada halaman edit profile.</span>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <!--end::Alert-->
            @endif

            <div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h1 class="fw-bold">Selamat Datang di Sistem Informasi Divisi Operation TS, {{ Auth()->user()->name }}!</h1>
                            {{-- <h3 class="fw-bold">Anda Login Sebagai <span class="role-label">{{ implode(', ', Auth()->user()->roles->pluck('name')->toArray()) }}</span></h3> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Content-->
</div>
@endsection

