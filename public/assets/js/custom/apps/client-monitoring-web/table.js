$(function () {
    const dataTableUrl = '/monitoringweb/client/dataTable'; // Ganti dengan URL untuk endpoint DataTable
    const bulkDeleteUrl = '/monitoringweb/client/bulk-delete'; // Ganti dengan URL untuk endpoint hapus massal

    // Inisialisasi DataTable
    var table = $('.data-tableclient').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dataTableUrl,
            data: function (d) {
                d.search = $('#searchInput').val(); // Mengambil nilai input search
            }
        },
        columns: [
            {
                data: 'id',
                render: function (data) {
                    return `
                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                            <input class="form-check-input checkbox-nim" type="checkbox" value="${data}">
                        </div>`;
                },
                orderable: false,
                searchable: false
            },
            { data: 'name' },
            { data: 'bot_token' },
            { data: 'chat_id' },
            {
                data: 'id',
                render: function (data, type, row) {
                    let actionButtons = '<div class="flex text-end">';

                    // Gunakan tablePermissions untuk menentukan apakah tombol edit harus ditampilkan
                    actionButtons += `
                        <a
                         href="javascript:void(0)" data-id="${data}" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_client">
                            <button class="btn btn-icon btn-active-light-primary w-30px h-30px me-3">
                                <i class="ki-duotone ki-setting-3 fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                </i>
                            </button>
                        </a>`;

                    actionButtons += '</div>';
                    return actionButtons;
                },
                orderable: false,
                searchable: false
            }
        ]
    });

    // Fungsi debounce untuk optimasi pencarian
    function debounce(callback, delay) {
        let timeout;
        return function () {
            clearTimeout(timeout);
            timeout = setTimeout(callback, delay);
        };
    }

    // Event pencarian dengan debounce
    $('#searchInput').on('keyup', debounce(function () {
        table.ajax.reload(); // Reload DataTable saat mengetik
    }, 300));

    // Select All Checkbox
    $('#select-all').on('change', function () {
        $('.checkbox-nim').prop('checked', this.checked);
        toggleDeleteButton();
    });

    // Perubahan pada checkbox
    $(document).on('change', '.checkbox-nim', function () {
        var allChecked = $('.checkbox-nim').length === $('.checkbox-nim:checked').length;
        $('#select-all').prop('checked', allChecked);
        toggleDeleteButton();
    });

    // Tampilkan atau sembunyikan tombol hapus
    function toggleDeleteButton() {
        var selected = $('.checkbox-nim:checked').length > 0;
        $('#delete-action').toggleClass('d-none', !selected);
    }

    // Event hapus data
    $('#delete-selected').on('click', function () {
        var ids = $('.checkbox-nim:checked').map(function () {
            return this.value;
        }).get();

        if (ids.length > 0) {
            Swal.fire({
                text: `Are you sure you want to delete these records?`,
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
                        url: bulkDeleteUrl,
                        method: 'POST',
                        data: {
                            ids: ids,
                            _token: $('meta[name="csrf-token"]').attr('content') // Ambil CSRF token dari meta tag
                        },
                        success: function (response) {
                            Swal.fire({
                                text: response.message,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },
                            }).then(() => {
                                table.ajax.reload();
                            });
                        },
                        error: function (xhr) {
                            const errorMsg = xhr.responseJSON?.error ||
                                "An error occurred while deleting. Please try again.";
                            Swal.fire({
                                text: errorMsg,
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
        }
    });
});
