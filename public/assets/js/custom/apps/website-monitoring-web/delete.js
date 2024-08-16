$(document).ready(function () {
    // Event delegation for delete button
    $(document).on("click", ".delete-customer", function (e) {
        e.preventDefault();

        const websiteId = $(this).data("id"); // Get ID from data attribute
        const websiteName = $(this).data("name"); // Get name from data attribute
        const deleteUrl = `/monitoringweb/website/delete/${websiteId}`; // Your delete route

        Swal.fire({
            text: `Are you sure you want to delete ${websiteName}?`,
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-active-light",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteUrl,
                    type: "DELETE",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"), // Include CSRF token
                    },
                    success: function (response) {
                        Swal.fire({
                            text: `${websiteName} has been successfully deleted!`,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        }).then(() => {
                            // Reload DataTable
                            $(".data-tablewebsiteview")
                                .DataTable()
                                .ajax.reload();
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            text: "An error occurred while deleting the user. Please try again.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        });
                    },
                });
            }
        });
    });
});
