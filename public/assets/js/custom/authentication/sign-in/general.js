    "use strict";

    var KTSigninGeneral = (function () {
        var form, submitButton, validator;

        return {
            init: function () {
                form = document.querySelector("#kt_sign_in_form");
                submitButton = document.querySelector("#kt_sign_in_submit");

                validator = FormValidation.formValidation(form, {
                    fields: {
                        username: {
                            validators: {
                                notEmpty: {
                                    message: "The username is required",
                                },
                            },
                        },
                        password: {
                            validators: {
                                notEmpty: {
                                    message: "The password is required",
                                },
                            },
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: "",
                        }),
                    },
                });

                submitButton.addEventListener("click", function (event) {
                    event.preventDefault();

                    validator.validate().then(function (status) {
                        if (status === "Valid") {
                            submitButton.setAttribute("data-kt-indicator", "on");
                            submitButton.disabled = true;

                            // AJAX request
                            $.ajax({
                                url: form.getAttribute("action"),
                                type: "POST",
                                data: $(form).serialize(),
                                headers: {
                                    "X-CSRF-TOKEN": $(
                                        'meta[name="csrf-token"]'
                                    ).attr("content"),
                                },
                                success: function (response) {
                                    if (response.success) {
                                        Swal.fire({
                                            text: "You have successfully logged in!",
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary",
                                            },
                                        }).then(function (result) {
                                            if (result.isConfirmed) {
                                                window.location.href =
                                                    response.redirect_url;
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            text: response.message,
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary",
                                            },
                                        });
                                    }
                                },
                                error: function (xhr) {
                                    let errorMessage = "Terjadi kesalahan yang tidak diketahui.";
                                    
                                    // Coba ambil pesan dari response JSON
                                    try {
                                        const response = JSON.parse(xhr.responseText);
                                        errorMessage = response.message || errorMessage;
                                    } catch (e) {
                                        // Jika parsing JSON gagal, ambil responseText biasa
                                        errorMessage = xhr.responseText || errorMessage;
                                    }
                                
                                    Swal.fire({
                                        text: errorMessage,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary",
                                        },
                                    });
                                },
                                
                                complete: function () {
                                    submitButton.removeAttribute(
                                        "data-kt-indicator"
                                    );
                                    submitButton.disabled = false;
                                },
                            });
                        } else {
                            Swal.fire({
                                text: "Terjadi Kesalahan Saat Mencoba Login, Silahkan Cek Username & Password Anda.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },
                            });
                        }
                    });
                });
            },
        };
    })();

    KTUtil.onDOMContentLoaded(function () {
        KTSigninGeneral.init();
    });
