<!--begin::Modal - Edit Customer-->
<div class="modal fade" id="kt_modal_edit_website" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_edit_website_header">
                <h2 class="fw-bold">Edit Client</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-resetataseditcustomer-modal-action="cancel">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body px-5 my-7">
                <form id="kt_modal_edit_website_form" class="form">
                    <input type="hidden" name="id" id="id">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_edit_website_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_edit_website_header" data-kt-scroll-wrappers="#kt_modal_edit_website_scroll" data-kt-scroll-offset="300px">
                       <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="fs-6 fw-semibold form-label mb-2">
                            <span class="required">Nama Wesbite</span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" placeholder="Masukan Nama Client Anda" id="name" name="name" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                       <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="fs-6 fw-semibold form-label mb-2">
                            <span>URL Website</span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" placeholder="Masukan URL Website Anda" id="url" name="url" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold form-label mb-2">Client Website</label>
                        <select class="form-select form-select-solid" id="client_monitoring_id" name="client_monitoring_id"  aria-label="Floating label select example">
                            @foreach ($client as $clients )
                            <option value="{{ $clients->id }}">{{ $clients->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold form-label mb-2">Type Website</label>
                        <select class="form-select form-select-solid" id="website_monitoring_type_id" name="website_monitoring_type_id"  aria-label="Floating label select example">
                            @foreach ($websitetype as $websitetypes )
                            <option value="{{ $websitetypes->id }}">{{ $websitetypes->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="fs-6 fw-semibold form-label mb-2">
                            <span class="required">Warning Treshold</span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" placeholder="Masukan Warning Treshold Website Anda" id="warning_threshold" name="warning_treshold" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="fs-6 fw-semibold form-label mb-2">
                            <span class="required">Down Treshold</span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" placeholder="Masukan Down Treshold Website Anda" id="down_threshold" name="down_treshold" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="fs-6 fw-semibold form-label mb-2">
                            <span class="required">Interval Notifikasi Ke User</span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" class="form-control form-control-solid" placeholder="Tiap berapa menit anda mau memberikan notifikasi ke user?" id="notify_user_interval" name="notify_user_interval" />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold form-label mb-2">Active?</label>
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="is_active" name="is_active" />
                            <label class="form-check-label" for="is_active"></label>
                        </div>
                    </div>
                    <!--end::Input group-->


                    </div>
                    <div class="text-center pt-10">
                        <button type="reset" class="btn btn-light me-3" data-kt-reseteditcustomer-modal-action="cancel">Discard</button>
                        <button type="submit" class="btn btn-primary" data-kt-editwebsite-modal-action="submit">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Edit Customer-->
