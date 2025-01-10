$(document).ready(function () {
    // Handle the modal open and populate the form
    $("body").on("click", 'a[data-bs-toggle="modal"]', function () {
        var modalTarget = $(this).data("bs-target");
        let websiteId = $(this).data("id");

        $.ajax({
            url: `/monitoringweb/website/getData/${websiteId}`,
            type: "GET",
            cache: false,
            success: function (response) {
                if (response && response.data) {
                    $("#id").val(response.data.id);
                    $("#name").val(response.data.name);
                    $("#url").val(response.data.url);
                    $("#client_monitoring_id").val(
                        response.data.client_monitoring_id
                    );
                    $("#website_monitoring_type_id").val(
                        response.data.website_monitoring_type_id
                    );
                    $("#warning_threshold").val(
                        response.data.warning_threshold
                    );
                    $("#down_threshold").val(response.data.down_threshold);
                    $("#notify_user_interval").val(
                        response.data.notify_user_interval
                    );

                    if (response.data.is_active == 1) {
                        $("#is_active").prop("checked", true);
                    } else {
                        $("#is_active").prop("checked", false);
                    }

                    // console.log(response.data.is_active);

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
        '[data-kt-editwebsite-modal-action="submit"]'
    );
    const form = document.getElementById("kt_modal_edit_website_form");

    if (submitButton) {
        submitButton.addEventListener("click", function (event) {
            event.preventDefault();

            // Get customer_id from the hidden input
            let websiteId = $("#id").val();
            let websiteName = $("#name").val();

            const handleFormSubmit = function () {
                submitButton.setAttribute("data-kt-indicator", "on");
                submitButton.disabled = true;

                // AJAX request to submit the form data
                $.ajax({
                    type: "POST",
                    url: `/monitoringweb/website/update/${websiteId}`,
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
                            text: `Client ${websiteName} Berhasil Di Update!`,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                form.reset();
                                $("#kt_modal_edit_website").modal("hide");
                                $(".data-tablewebsitemonitoring")
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
