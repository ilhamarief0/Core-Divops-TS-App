<!--begin::Modal - Edit Customer-->
<div class="modal fade" id="kt_modal_edit_client" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_edit_client_header">
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
                <form id="kt_modal_edit_client_form" class="form">
                    <input type="hidden" name="id" id="id">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_edit_client_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_edit_client_header" data-kt-scroll-wrappers="#kt_modal_edit_client_scroll" data-kt-scroll-offset="300px">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mb-2"><span class="required">Nama Client</span></label>
                            <input type="text" class="form-control form-control-solid" id="name" name="name" placeholder="Masukan Nama Website Anda" />
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mb-2"><span class="required">Deskripsi</span></label>
                            <input type="text" class="form-control form-control-solid" id="description" name="description" placeholder="Masukan Deskripsi Client Anda" />
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mb-2"><span class="required">Bot Telegram</span></label>
                            <input type="text" class="form-control form-control-solid" id="bot_token" name="bot_token" placeholder="Masukan Bot Token Anda" />
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mb-2"><span class="required">Chat Id</span></label>
                            <input type="text" class="form-control form-control-solid" id="chat_id" name="chat_id" placeholder="Masukan Chat Id Anda" />
                        </div>


                    </div>
                    <div class="text-center pt-10">
                        <button type="reset" class="btn btn-light me-3" data-kt-reseteditcustomer-modal-action="cancel">Discard</button>
                        <button type="submit" class="btn btn-primary" data-kt-editclient-modal-action="submit">
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
