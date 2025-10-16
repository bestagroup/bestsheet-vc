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
     *  لود فرم ویرایش به صورت داینامیک
     * ------------------------------------------- */
    $(document).on('click', '.edit-btn', function () {
        const url = $(this).data('url');
        const $modal = $('#editModal');
        const $body = $('#editModalBody');

        $body.html('<div class="text-center text-muted py-5">در حال بارگذاری...</div>');
        $modal.modal('show');

        $.ajax({
            url: url,
            method: 'GET',
            success: function (html) {
                $body.html(html);
            },
            error: function () {
                $body.html('<div class="alert alert-danger m-3">خطا در بارگذاری فرم ویرایش</div>');
            }
        });
    });

    $(document).on('submit', 'form[data-type="update"]', function (e) {
        e.preventDefault();

        const $form = $(this);
        const $btn = $form.find('button[type="submit"]');
        const originalHtml = $btn.html();

        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> در حال ذخیره...');

        $.ajax({
            url: $form.attr('action'),
            method: 'PATCH',
            data: $form.serialize(),
            success: function (data) {
                if (data.success) {
                    $('#editModal').modal('hide');
                    $('.yajra-datatable').DataTable().ajax.reload(null, false);
                    showToast('اطلاعات با موفقیت به‌روزرسانی شد!', 'success');
                } else {
                    showToast(data.message || 'خطایی رخ داد', 'error');
                }
            },
            error: function () {
                showToast('مشکلی در ذخیره تغییرات رخ داد', 'error');
            },
            complete: function () {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

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
                    const modalEl = document.getElementById('showModal' + id);

                    if (modalEl) {
                        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);

                        modalEl.addEventListener('hidden.bs.modal', function handler() {
                            modalEl.removeEventListener('hidden.bs.modal', handler);
                            $('.yajra-datatable').DataTable().ajax.reload(null, false);
                            showToast('آیتم با موفقیت ویرایش شد!', 'success');
                        }, {once: true});

                        modal.hide();
                    } else {
                        // در صورتی که مودال پیدا نشه (برای خطایابی)
                        console.error('Modal element not found for id:', id);
                        showToast('آیتم با موفقیت ویرایش شد! (مودال پیدا نشد)', 'success');
                        $('.yajra-datatable').DataTable().ajax.reload(null, false);
                    }
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
    function handleDelete(id) {
        const $btn = $('#confirmDelete');
        const originalHtml = $btn.html();

        $btn.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال حذف...'
        );

        const baseUrl = window.location.pathname.split('/')[1]; // بخش دوم URL مثل 'projects'
        const deleteUrl = `/${baseUrl}/destroy/${id}`;

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
    let deleteId = null;

    $(document).on('click', '.delete-btn', function () {
        deleteId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function () {
        if (deleteId) handleDelete(deleteId);
    });

});
