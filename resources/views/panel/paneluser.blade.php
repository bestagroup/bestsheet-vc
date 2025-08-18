@extends('layouts.base')
@section('title', 'لیست کاربران داشبورد')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
    <link rel="stylesheet" href="{{'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'}}" />
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{$thispage['list']}}</h5>
                @if(Gate::allows('can-access', ['paneluser', 'insert']))
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">{{$thispage['add']}}</a>
                @endif
            </div>

            <div class="table-responsive">
                <style> table{margin: 0 auto;width: 100% !important;clear: both;border-collapse: collapse;table-layout: fixed;word-wrap:break-word;} .dt-layout-start{margin-right: 0 !important;} .dt-layout-end{margin-left: 0 !important;}</style>
                <table id="sample1" class="table table-striped table-bordered yajra-datatable">
                    <thead>
                    <tr class="table-light">
                        <th>نام و نام خانوادگی</th>
                        <th>نوع کاربر</th>
                        <th>ایمیل</th>
                        <th>موبایل</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Delete Modal -->
    @foreach($users as $user)
        <div class="modal fade" id="deleteModal{{$user->id}}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title w-100" id="deleteModalLabel">{{$thispage['delete']}}</h5>
                        <button type="button" class="btn-close position-absolute start-0 mx-3" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        آیا از حذف این منو مطمئن هستید؟
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">انصراف</button>
                        <button type="button" class="btn btn-danger" id="deletesubmit_{{$user->id}}" data-id="{{$user->id}}">حذف</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">{{$thispage['add']}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <form id="addform" method="POST" action="{{ route(request()->segment(2).'.store') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">نام و نام خانوادگی</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="نام و نام خانوادگی را وارد کنید">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">شماره موبایل</label>
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="نام کاربری را وارد کنید">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">آدرس ایمیل</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="آدرس ایمیل را وارد کنید">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">کد ملی</label>
                                <input type="text" name="national_id" id="national_id" class="form-control" placeholder="کد ملی را وارد کنید">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">نوع کاربری</label>
                                <select name="typeuser_id" id="typeuser_id" class="form-control select-lg select2">
                                    <option value="" selected>انتخاب کنید</option>
                                    @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->title_fa}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">تاریخ تولد</label>
                                <input type="text" name="birthday" id="birthday" class="form-control" placeholder="تاریخ تولد را وارد کنید">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">جنسیت</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="" selected>انتخاب کنید</option>
                                    <option value="1" >مرد</option>
                                    <option value="2" >زن</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">رمز عبور</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="رمز عبور را وارد کنید">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">تکرار رمز عبور</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="تکرار رمز عبور">
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" id="submit" class="btn btn-primary">ذخیره اطلاعات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    @foreach($users as $user)
        <div class="modal fade" id="editModal{{$user->id}}" tabindex="-1" aria-labelledby="editModalLabel{{$user->id}}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{$user->id}}">{{$thispage['edit']}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editform_{{ $user->id }}" method="POST" action="{{ route(request()->segment(2).'.update', $user->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="user_id" id="user_id_{{$user->id}}" value="{{$user->id}}" />
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">نام و نام خانوادگی</label>
                                    <input type="text" name="name" id="name_{{$user->id}}" value="{{$user->name}}" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">شماره موبایل</label>
                                    <input type="text" name="phone" id="phone_{{$user->id}}" value="{{$user->phone}}" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">آدرس ایمیل</label>
                                    <input type="email" name="email" id="email_{{$user->id}}" value="{{$user->email}}" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">کد ملی</label>
                                    <input type="text" name="national_id" id="national_id_{{$user->id}}" value="{{$user->national_id}}" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">نوع کاربری</label>
                                    <select name="typeuser_id" id="typeuser_id_{{$user->id}}" class="form-control select-lg select2">
                                        <option value="" selected>انتخاب کنید</option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}" {{$user->role_id == $role->id ? 'selected' : ''}}>{{$role->title_fa}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">تاریخ تولد</label>
                                    <input type="text" name="birthday" id="birthday_{{$user->id}}" value="{{$user->birthday}}" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">جنسیت</label>
                                    <select name="gender" id="gender_{{$user->id}}" class="form-control">
                                        <option value="" selected>انتخاب کنید</option>
                                        <option value="1" {{$user->gender == 1 ? 'selected' : ''}}>مرد</option>
                                        <option value="2" {{$user->gender == 2 ? 'selected' : ''}}>زن</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">وضعیت</label>
                                    <select name="status" id="status_{{$user->id}}" class="form-control">
                                        <option value="" selected>انتخاب کنید</option>
                                        <option value="4" {{$user->status == 4 ? 'selected' : ''}}>فعال</option>
                                        <option value="0" {{$user->status == 0 ? 'selected' : ''}}>غیر فعال</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">نوع دسترسی</label>
                                    <select name="level" id="level_{{$user->id}}" class="form-control">
                                        <option value="" selected>انتخاب کنید</option>
                                        <option value="admin" {{$user->level == 'admin' ? 'selected' : ''}}>کاربر داشبورد</option>
                                        <option value="site"  {{$user->level == 'site' ? 'selected' : '' }}>کاربر سایت</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">رمز عبور</label>
                                    <input type="password" name="password" id="password_{{$user->id}}" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">تکرار رمز عبور</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation_{{$user->id}}" class="form-control">
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" id="editsubmit_{{$user->id}}" class="btn btn-primary" >ذخیره اطلاعات</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@section('script')
    <script src="{{asset('assets/vendor/js/dataTables.min.js')}}"></script>

    <script type="text/javascript">
        $(function () {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route(request()->segment(2).'.index')}}",
                columns: [
                    {data: 'name'           , name: 'name'      },
                    {data: 'title'          , name: 'title'     },
                    {data: 'email'          , name: 'email'     },
                    {data: 'phone'          , name: 'title'     },
                    {data: 'status'         , name: 'status'    },
                    {data: 'action'         , name: 'action', orderable: true, searchable: true},
                ],
                language: {
                    url: "{{asset('assets/vendor/js/fa.json')}}"
                }
            });

        });
    </script>
    <script>
        jQuery(function($){
            function showToast(message, type = 'success') {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 3000,
                    rtl: true
                };

                if (toastr[type]) {
                    toastr[type](message);
                } else {
                    toastr.success(message);
                }
            }
            $('#submit').on('click', function(e){
                e.preventDefault();
                var $btn  = $(this);
                var $form = $('#addform');
                var originalHtml = $btn.html();

                $btn.prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route(request()->segment(2).'.store') }}",
                    method: 'POST',
                    data: $form.serialize(),
                    success: function (data) {
                        if (data.success) {
                            const modalEl = document.getElementById('addModal');
                            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);

                            modalEl.addEventListener('hidden.bs.modal', function handler(){
                                modalEl.removeEventListener('hidden.bs.modal', handler);
                                $('.yajra-datatable').DataTable().ajax.reload(null, false);
                            }, { once: true });

                            modal.hide();
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            $('body').css('padding-right', '');
                            showToast('آیتم با موفقیت افزوده شد!', 'success');
                        } else {
                            swal(data.subject, data.message, data.flag);
                        }
                    },
                    error: function () {
                        swal('خطا', 'مشکلی پیش آمد. لطفاً دوباره تلاش کنید.', 'error');
                    },
                    complete: function () {
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });
        });
    </script>
    <script>
        jQuery(function($){
            function showToast(message, type = 'success') {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-center",
                    timeOut: 3000,
                    rtl: true
                };

                if (toastr[type]) {
                    toastr[type](message);
                } else {
                    toastr.success(message);
                }
            }

            $(document).on('click', '[id^=editsubmit_]', function(e){
                e.preventDefault();
                const $btn = $(this);
                const id = this.id.split('_')[1];
                const $form = $('#editform_' + id);

                if (!$form.length) {
                    console.error('فرم editform_' + id + ' پیدا نشد!');
                    return;
                }

                const url = $form.attr('action'); // استفاده از URL داینامیک
                const originalHtml = $btn.html();
                disableBtnWithSpinner($btn);

                $.ajax({
                    url: url,
                    method: 'PATCH',
                    data: $form.serialize(),
                    success: function (data) {
                        if (data.success) {
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
                            swal(data.subject, data.message, data.flag);
                        }
                    },
                    error: function () {
                        swal('خطا', 'مشکلی پیش آمد. لطفاً دوباره تلاش کنید.', 'error');
                    },
                    complete: function () {
                        restoreBtn($btn, originalHtml);
                    }
                });
            });

            function disableBtnWithSpinner($btn){
                $btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...'
                );
            }
            function restoreBtn($btn, html){
                $btn.prop('disabled', false).html(html);
            }
        });
    </script>
    <script>
        jQuery(function ($) {
            function showToast(message, type = 'success') {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 3000,
                    rtl: true
                };

                if (toastr[type]) {
                    toastr[type](message);
                } else {
                    toastr.success(message);
                }
            }
            $(document).on('click', '[id^=deletesubmit_]', function (e) {
                e.preventDefault();
                const $btn = $(this);
                const id = $btn.data('id');
                const originalHtml = $btn.html();
                $btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال حذف...'
                );

                $.ajax({
                    url: "{{ route(request()->segment(2).'.destroy', 0) }}",
                    method: 'DELETE',
                    data: { "_token": "{{ csrf_token() }}", id },
                    success: function (data) {
                        const modalEl = document.getElementById('deleteModal' + id);
                        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modal.hide();
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open').css('padding-right', '');
                        $('.yajra-datatable').DataTable().ajax.reload(null, false);
                        showToast('آیتم با موفقیت حذف شد!', 'success');
                    },
                    error: function () {
                        showToast('مشکلی پیش آمد. لطفاً دوباره تلاش کنید.', 'error');
                    },
                    complete: function () {
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });
        });
    </script>

@endsection
