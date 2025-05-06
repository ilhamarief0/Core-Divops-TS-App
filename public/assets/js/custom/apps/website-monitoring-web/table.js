"use strict";

let KTWorkday = (function () {
    let datatable, table;

    let initWorkdays = () => {
        datatable = $(table).DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[0, "asc"]],
            ajax: {
                url: $("#table-url").val(),
            },
            info: false,
            columns: [
                { data: "name" },
                { data: "url" },
                { data: "is_active" },
                { data: "client" },
                { data: "type" },
                { data: "action", orderable: false, searchable: false },
            ],
            columnDefs: [
                {
                    targets: -1,
                    className: "text-end",
                    render: function (data, type, row) {
                        return `
                        <button class="edit-workday btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_workday" data-id="${data}">
                            <i class="ki-duotone ki-pencil fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </button>
                        <button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" data-kt-workdays-table-filter="delete_row" data-id="${data}">
                            <i class="ki-duotone ki-trash fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </button>`;
                    },
                },
            ],
        });

        window.datatable = datatable;

        datatable.on("draw", function () {
            handleDeleteRows();
            KTMenu.createInstances();
        });
    };

    let initFlatpickerLocale = () => {
        flatpickr.localize(flatpickr.l10ns.id);
    };

    let handleSearchDatatable = () => {
        const filterSearch = document.querySelector(
            '[data-kt-workdays-table-filter="search"]'
        );

        let searchTimeout;
        filterSearch.addEventListener("input", function (e) {
            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(() => {
                datatable.search(e.target.value).draw();
            }, 700);
        });
    };

    let handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = table.querySelectorAll(
            '[data-kt-workdays-table-filter="delete_row"]'
        );

        deleteButtons.forEach((d) => {
            // Delete button on click
            d.addEventListener("click", function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest("tr");
                const className = parent.querySelectorAll("td")[0].innerText;

                const classId = $(this).data("id");
                Swal.fire({
                    text: "Are you sure you want to delete " + className + "?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary",
                    },
                }).then(function (result) {
                    if (result.value) {
                        Swal.fire({
                            text: "Deleting " + className,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            showConfirmButton: false,
                            buttonsStyling: false,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();

                                const errorSwal = {
                                    showConfirmButton: true,
                                    timer: undefined,
                                    timerProgressBar: false,
                                    confirmButtonText: "Close",
                                    customClass: {
                                        confirmButton:
                                            "btn fw-bold btn-primary",
                                    },
                                };

                                $.ajax({
                                    url: `/workdays/${classId}`,
                                    type: "DELETE",
                                    cache: false,
                                    data: {
                                        _token: token,
                                    },
                                })
                                    .done((response) => {
                                        Swal.fire(
                                            Object.assign(
                                                {
                                                    text: response.message,
                                                    icon: response.status,
                                                    buttonsStyling: false,
                                                    showConfirmButton: false,
                                                    timer: 1000,
                                                    timerProgressBar: true,
                                                },
                                                response.status == "error"
                                                    ? errorSwal
                                                    : {}
                                            )
                                        ).then(() => {
                                            datatable.draw();
                                        });
                                    })
                                    .fail((jqXHR, textStatus, error) => {
                                        Swal.fire(
                                            Object.assign(
                                                {
                                                    text:
                                                        "Failed delete " +
                                                        className +
                                                        "!. " +
                                                        jqXHR.responseJSON
                                                            .message,
                                                    icon: "error",
                                                },
                                                errorSwal
                                            )
                                        );
                                    });
                            },
                        });
                    } else if (result.dismiss === "cancel") {
                        Swal.fire({
                            text: className + " was not deleted.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        });
                    }
                });
            });
        });
    };

    return {
        init: function () {
            table = document.querySelector("#kt_workdays_table");

            if (!table) {
                return;
            }

            initWorkdays();
            initFlatpickerLocale();
            handleDeleteRows();
            handleSearchDatatable();
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    KTWorkday.init();
});
