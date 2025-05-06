@props(['title' => null, 'message' => null])

<div id="kt_docs_toast_stack_container" class="top-0 p-3 toast-container position-fixed end-0 z-index-110">
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-kt-docs-toast="stack">
        <div class="toast-header">
            <x-atoms.icon id="toast-icon" class="me-3 text-success" icon="check-circle" path="2" />
            <strong id="toast-title" class="me-auto">{{ $title }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div id="toast-message" class="toast-body">
            {{ $message }}
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script>
        const toastContainer = $("#kt_docs_toast_stack_container");
        const toastIconElement = $("#toast-icon");
        const toastIconClass = toastIconElement.attr("class");
        const toastElement = document.querySelector('[data-kt-docs-toast="stack"]');

        // Open toast element
        const openToast = (icon, status) => {
            const newClass = toastIconClass
                .replace(/(?<=ki-)[^-]*(?=-circle)/, icon)
                .replace(/(?<=text-)[^-]*/, status);
            toastIconElement.attr("class", newClass);

            const newToast = toastElement.cloneNode(true);
            toastContainer.append(newToast);

            const toast = bootstrap.Toast.getOrCreateInstance(newToast);

            toast.show();
        };
    </script>
@endPushOnce
