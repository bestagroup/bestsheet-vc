@extends('layouts.base')

@section('title', 'مدیریت دسترسی های داشبورد')

@push('head')
    <link rel="stylesheet" href="{{ asset('https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css') }}" />
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">لیست دسترسی های داشبورد</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">+ افزودن نقش داشبورد</button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered text-center" id="rolesTable">
                    <thead class="table-light">
                    <tr>
                        <th>ردیف</th>
                        <th>نام نقش</th>
                        <th>لیبل نقش</th>
                        <th>دسترسی نقش</th>
                        <th>تغییر</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>۱</td>
                        <td>ادمین</td>
                        <td>superadmin</td>
                        <td>panel | menu-dashboard | dashboard-manage</td>
                        <td>
                            <button class="btn btn-sm btn-icon btn-outline-danger"><i class="mdi mdi-delete-outline"></i></button>
                            <button class="btn btn-sm btn-icon btn-outline-primary"><i class="mdi mdi-pencil-outline"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>۲</td>
                        <td>مشاور</td>
                        <td>Consultant</td>
                        <td>dashboard | profile | reports</td>
                        <td>
                            <button class="btn btn-sm btn-icon btn-outline-danger"><i class="mdi mdi-delete-outline"></i></button>
                            <button class="btn btn-sm btn-icon btn-outline-primary"><i class="mdi mdi-pencil-outline"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal افزودن -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ایجاد نقش داشبورد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">نام نقش</label>
                                <input type="text" class="form-control" placeholder="مثلاً مدیر" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">لیبل نقش</label>
                                <input type="text" class="form-control" placeholder="مثلاً manager" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">آدرس نقش</label>
                                <input type="text" class="form-control" placeholder="panel | reports | ..." />
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">ذخیره اطلاعات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal ویرایش -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش نقش داشبورد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">نام نقش</label>
                                <input type="text" class="form-control" id="editRoleName" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">لیبل نقش</label>
                                <input type="text" class="form-control" id="editRoleLabel" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">آدرس نقش</label>
                                <input type="text" class="form-control" id="editRoleAccess" />
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // راه‌اندازی دیتاتیبل با زبان فارسی
            const table = new DataTable('#rolesTable', {
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.5/i18n/fa.json"
                },
                responsive: true,
                pageLength: 10
            });

            // دکمه حذف
            document.querySelectorAll('.btn-outline-danger').forEach((btn) => {
                btn.addEventListener('click', function () {
                    const row = btn.closest('tr');
                    Swal.fire({
                        title: 'حذف نقش',
                        text: 'آیا از حذف این نقش اطمینان دارید؟',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'بله، حذف شود!',
                        cancelButtonText: 'لغو'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            row.remove(); // حذف از DOM (فرانت)
                            Swal.fire('حذف شد!', 'نقش موردنظر حذف شد.', 'success');
                            // اینجا اگر حذف از سرور هم نیاز هست باید AJAX بفرستی
                        }
                    });
                });
            });

            // دکمه ویرایش
            document.querySelectorAll('.btn-outline-primary').forEach((btn) => {
                btn.addEventListener('click', function () {
                    const row = btn.closest('tr');
                    document.getElementById('editRoleName').value = row.children[1].innerText.trim();
                    document.getElementById('editRoleLabel').value = row.children[2].innerText.trim();
                    document.getElementById('editRoleAccess').value = row.children[3].innerText.trim();
                    const modal = new bootstrap.Modal(document.getElementById('editRoleModal'));
                    modal.show();
                });
            });
        });
    </script>
@endpush
