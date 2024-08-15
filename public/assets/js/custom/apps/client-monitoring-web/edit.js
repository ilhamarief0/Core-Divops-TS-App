$(document).ready(function () {
    // Handle the modal open and populate the form
    $("body").on("click", 'a[data-bs-toggle="modal"]', function () {
        var modalTarget = $(this).data("bs-target");
        let clientId = $(this).data("id");

        $.ajax({
            url: `/monitoringweb/client/getData/${clientId}`,
            type: "GET",
            cache: false,
            success: function (response) {
                if (response && response.data) {
                    $("#id").val(response.data.id);
                    $("#name").val(response.data.name);
                    $("#description").val(response.data.description);
                    $("#bot_token").val(response.data.bot_token);
                    $("#chat_id").val(response.data.chat_id);
                    // Open modal
                    $(modalTarget).modal("show");
                }
            },
            error: function (xhr) {
                console.error(
                    "An error occurred while fetching customer data: ",
                    xhr.responseText
                );
            },
        });
    });

    // Handle the form submission
    const submitButton = document.querySelector(
        '[data-kt-editclient-modal-action="submit"]'
    );
    const form = document.getElementById("kt_modal_edit_client_form");

    if (submitButton) {
        submitButton.addEventListener("click", function (event) {
            event.preventDefault();

            // Get customer_id from the hidden input
            let clientId = $("#id").val();
            let clientName = $("#name").val();

            const handleFormSubmit = function () {
                submitButton.setAttribute("data-kt-indicator", "on");
                submitButton.disabled = true;

                // AJAX request to submit the form data
                $.ajax({
                    type: "POST",
                    url: `/monitoringweb/client/update/${clientId}`,
                    data: $(form).serialize(),
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    success: function (response) {
                        submitButton.removeAttribute("data-kt-indicator");
                        submitButton.disabled = false;
                        Swal.fire({
                            text: `Client ${clientName} Berhasil Di Update!`,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                form.reset();
                                $("#kt_modal_edit_client").modal("hide");
                                $(".data-tableclientview")
                                    .DataTable()
                                    .ajax.reload();
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        submitButton.removeAttribute("data-kt-indicator");
                        submitButton.disabled = false;
                        Swal.fire({
                            text: "Terjadi Kesalahan!, Silahkan Cek Data Anda Kembali",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        });
                    },
                });
            };

            // Validate form if validation library is being used
            if (typeof o !== "undefined" && o.validate) {
                o.validate().then(function (status) {
                    if (status == "Valid") {
                        handleFormSubmit();
                    } else {
                        Swal.fire({
                            text: "Terjadi Kesalahan!, Silahkan Cek Data Anda Kembali",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        });
                    }
                });
            } else {
                // If no validation library is used, proceed with submission
                handleFormSubmit();
            }
        });
    } else {
        console.error("Submit button not found");
    }

    const resetButton = document.querySelector(
        '[data-kt-reseteditcustomer-modal-action="cancel"]'
    );

    resetButton.addEventListener("click", function (event) {
        event.preventDefault();
        Swal.fire({
            text: "Are you sure you would like to cancel?",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes, cancel it!",
            cancelButtonText: "No, return",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-active-light",
            },
        }).then(function (result) {
            form.reset();
            $("#kt_modal_edit_client").modal("hide");
        });
    });
    const resetatasButton = document.querySelector(
        '[data-kt-resetataseditcustomer-modal-action="cancel"]'
    );

    resetatasButton.addEventListener("click", function (event) {
        event.preventDefault();
        Swal.fire({
            text: "Are you sure you would like to cancel?",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes, cancel it!",
            cancelButtonText: "No, return",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-active-light",
            },
        }).then(function (result) {
            form.reset();
            $("#kt_modal_edit_client").modal("hide");
        });
    });
});
