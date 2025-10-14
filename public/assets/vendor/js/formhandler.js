document.addEventListener('DOMContentLoaded', () => {

    /* -------------------------------------------
     *  تابع Toast عمومی
     * ------------------------------------------- */
    function showToast(message, type = 'success') {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 3000,
            rtl: true
        };
        if (toastr[type]) toastr[type](message);
        else toastr.success(message);
    }

    /* -------------------------------------------
     *  هندل کردن CREATE
     * ------------------------------------------- */
    function handleCreate(form) {
        const $form = $(form);
        const $btn  = $form.find('[type="submit"]');
        const originalHtml = $btn.html();

        $btn.prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...');

        $.ajax({
            url: $form.attr('action'),
            method: $form.attr('method') || 'POST',
            data: $form.serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
            success: function (data) {
                if (data.success) {
                    const modalEl = document.querySelector('#addModal');
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);

                    modalEl.addEventListener('hidden.bs.modal', function handler() {
                        modalEl.removeEventListener('hidden.bs.modal', handler);
                        $('.yajra-datatable').DataTable().ajax.reload(null, false);
                    }, { once: true });

                    modal.hide();
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open').css('padding-right', '');
                    showToast('آیتم با موفقیت افزوده شد!', 'success');
                } else {
                    swal(data.subject || 'خطا', data.message || 'عملیات انجام نشد.', data.flag || 'error');
                }
            },
            error: function (xhr) {
                let message = 'مشکلی پیش آمد. لطفاً دوباره تلاش کنید.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                swal('خطا', message, 'error');
            },
            complete: function () {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    }

    /* -------------------------------------------
     *  هندل کردن UPDATE
     * ------------------------------------------- */
    function handleUpdate(form) {
        const $form = $(form);
        const $btn  = $form.find('[type="submit"]');
        const originalHtml = $btn.html();
        const url = $form.attr('action');

        $btn.prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...');

        $.ajax({
            url: url,
            method: 'PATCH',
            data: $form.serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
            success: function (data) {
                if (data.success) {
                    const id = $form.data('id') || '';
                    const modalEl = document.getElementById('editModal' + id);
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);

                    modalEl.addEventListener('hidden.bs.modal', function handler(){
                        modalEl.removeEventListener('hidden.bs.modal', handler);
                        $('.yajra-datatable').DataTable().ajax.reload(null, false);
                        showToast('آیتم با موفقیت ویرایش شد!', 'success');
                    }, { once: true });

                    modal.hide();
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open').css('padding-right', '');
                } else {
                    swal(data.subject || 'خطا', data.message || 'عملیات انجام نشد.', data.flag || 'error');
                }
            },
            error: function (xhr) {
                let message = 'مشکلی پیش آمد. لطفاً دوباره تلاش کنید.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                swal('خطا', message, 'error');
            },
            complete: function () {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    }

    /* -------------------------------------------
     *  هندل کردن DELETE
     * ------------------------------------------- */
    function handleDelete(deleteUrl) {
        const $btn = $('#confirmDelete');
        const originalHtml = $btn.html();

        $btn.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال حذف...'
        );

        $.ajax({
            url: deleteUrl,
            method: 'DELETE',
            data: { "_token": $('meta[name="_token"]').attr('content') },
            success: function (data) {
                $('#deleteModal').modal('hide');
                $('.yajra-datatable').DataTable().ajax.reload(null, false);
                showToast(data.message || 'آیتم با موفقیت حذف شد!', 'success');
            },
            error: function (xhr) {
                let message = 'مشکلی پیش آمد. لطفاً دوباره تلاش کنید.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showToast(message, 'error');
            },
            complete: function () {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    }

    /* -------------------------------------------
     *  اتصال رویدادها به فرم‌ها
     * ------------------------------------------- */
    $('form[data-type="create"]').on('submit', function(e) {
        e.preventDefault();
        handleCreate(this);
    });

    $('form[data-type="update"]').on('submit', function(e) {
        e.preventDefault();
        handleUpdate(this);
    });

    /* -------------------------------------------
     *  رویدادهای حذف
     * ------------------------------------------- */
    let deleteUrl = null;

    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        const baseUrl = $(this).data('url'); // مسیر حذف را به دکمه بده مثل data-url="{{ route('users.destroy', id) }}"
        deleteUrl = baseUrl;
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function () {
        if (deleteUrl) handleDelete(deleteUrl);
    });

});
