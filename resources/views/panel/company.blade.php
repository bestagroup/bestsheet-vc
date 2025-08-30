@extends('layouts.base')
@section('title', 'مدیریت شرکت ها')
@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dropzone.min.css') }}"/>
<link rel="stylesheet" href="{{'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'}}" />
<style> table{margin: 0 auto;width: 100% !important;clear: both;border-collapse: collapse;table-layout: auto !important;word-wrap:break-word;white-space: nowrap;} .dt-layout-start{margin-right: 0 !important;} .dt-layout-end{margin-left: 0 !important;}</style>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{$thispage['list']}}</h5>
                @if (auth()->user()->can('can-access', ['project', 'insert']))
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">{{$thispage['add']}}</a>
                @endif
            </div>
            <div class="table-responsive">
                <table id="sample1" class="table table-striped table-bordered yajra-datatable">
                <thead>
                    <tr class="table-light">
                        <th>تغییرات</th>
                        <th>لوگو شرکت</th>
                        <th>نام طرح</th>
                        <th>نام شرکت</th>
                        <th>نام مدیرعامل/ نماینده</th>
                        <th>شماره ثبت</th>
                        <th>شناسه ملی شرکت</th>
                        <th>تاریخ ثبت</th>
                        <th>کد اقتصادی</th>
                        <th>نوع شرکت</th>
                        <th>ادرس وبسایت</th>
                        <th>تغییرات</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title w-100" id="deleteModalLabel">{{ $thispage['delete'] }}</h5>
                    <button type="button" class="btn-close position-absolute start-0 mx-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    آیا از حذف این زیر منو مطمئن هستید؟
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">حذف</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">{{$thispage['add']}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>

                </div>
                <br><br>
                <div class="modal-body">
                        <form id="addform" method="POST" action="{{ route(request()->segment(2).'.store') }}" class="row g-4 mb-4">
                            @csrf
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" name="commercial_name" id="commercial_name" class="form-control" />
                                    <label for="commercial_name">نام طرح</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" name="company_name" id="company_name" class="form-control" />
                                    <label for="company_name">نام شرکت</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" name="ceo_name" id="ceo_name" class="form-control" />
                                    <label for="ceo_name">مدیرعامل / نماینده شرکت</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" name="registration_number" id="registration_number" class="form-control" />
                                    <label for="registration_number">شماره ثبت</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" name="national_id" id="national_id" class="form-control" />
                                    <label for="national_id">شناسه ملی شرکت</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" name="registration_date" id="registration_date" class="form-control" />
                                    <label for="registration_date">تاریخ ثبت</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" name="economic_code" id="economic_code" class="form-control" />
                                    <label for="economic_code">کد اقتصادی</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <select name="legal_type" id="legal_type" class="form-control">
                                        <option value="" selected>انتخاب کنید</option>
                                        <option value="مسئولیت محدود"   >مسئولیت محدود</option>
                                        <option value="سهامی خاص"       >سهامی خاص</option>
                                        <option value="سهامی عام"       >سهامی عام</option>
                                        <option value="تعاونی"          >تعاونی</option>
                                        <option value="موسسه غیر تجاری" >موسسه غیر تجاری</option>
                                    </select>
                                    <label for="legal_type">نوع شرکت</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" name="website" id="website" class="form-control" />
                                    <label for="website">ادرس وبسایت</label>
                                </div>
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
    @foreach($companies as $company)
        <div class="modal fade" id="editModal{{$company->id}}" tabindex="-1" aria-labelledby="editModalLabel{{$company->id}}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{$company->id}}">{{$thispage['edit']}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                    </div>
                    <div class="modal-body">
                            <form id="editform_{{ $company->id }}" method="POST" action="{{ route(request()->segment(2).'.update', $company->id) }}">
                                @csrf
                                @method('PATCH')
                                <div class="row mb-3">
                                <input type="hidden" name="menu_id" id="menu_id_{{$company->id}}" value="{{$company->id}}" />
                                <div class="col-md-4 mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="company_name_{{$company->id}}" name="company_name"
                                               placeholder="نام شرکت" value="{{ $company->company_name }}">
                                        <label for="company_name">نام شرکت</label>
                                    </div>
                                </div>
                                    <div class="col-md-4 mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="commercial_name_{{$company->id}}" name="commercial_name"
                                               placeholder="نام تجاری شرکت" value="{{ $company->commercial_name }}">
                                        <label for="commercial_name">نام تجاری شرکت</label>
                                    </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="registration_number_{{$company->id}}" name="registration_number"
                                                   placeholder="شماره ثبت" value="{{ $company->registration_number }}">
                                            <label for="registration_number">شماره ثبت</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="national_id_{{$company->id}}" name="national_id"
                                                   placeholder="شناسه ملی" value="{{ $company->national_id }}">
                                            <label for="national_id">شناسه ملی</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="economic_code_{{$company->id}}" name="economic_code"
                                                   placeholder="کد اقتصادی" value="{{ $company->economic_code }}">
                                            <label for="economic_code">کد اقتصادی</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-floating form-floating-outline">
                                            <select name="legal_type" id="legal_type_{{$company->id}}" class="form-control">
                                                <option value="" selected>انتخاب کنید</option>
                                                <option value="مسئولیت محدود"   {{$company->legal_type == 'مسئولیت محدود'   ? 'selected' : ''}}>مسئولیت محدود</option>
                                                <option value="سهامی خاص"       {{$company->legal_type == 'سهامی خاص'       ? 'selected' : ''}}>سهامی خاص</option>
                                                <option value="سهامی عام"       {{$company->legal_type == 'سهامی عام'       ? 'selected' : ''}}>سهامی عام</option>
                                                <option value="تعاونی"          {{$company->legal_type == 'تعاونی'          ? 'selected' : ''}}>تعاونی</option>
                                                <option value="موسسه غیر تجاری" {{$company->legal_type == 'موسسه غیر تجاری' ? 'selected' : ''}}>موسسه غیر تجاری</option>
                                            </select>
                                            <label for="legal_type">نوع شرکت</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-floating form-floating-outline">
                                            <select name="user_id" id="user_id_{{$company->id}}" class="form-control">
                                                <option value="" selected>انتخاب کنید</option>
                                                @foreach($users as $user)
                                                <option value="{{$user->id}}" {{$user->id == $company->user_id ? 'selected' : ''}}> {{$user->name}}</option>
                                                @endforeach
                                            </select>
                                            <label for="user_id">مدیرعامل / نماینده شرکت</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" id="website_{{$company->id}}" name="website"
                                                   placeholder="وبسایت" value="{{ $company->website }}">
                                            <label for="website">وبسایت</label>
                                        </div>
                                    </div>
                                </div>
                            <div class="text-end">
                                <button type="button" id="editsubmit_{{$company->id}}" class="btn btn-primary" >ذخیره اطلاعات</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Media Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $thispage['add'] }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('storemedia') }}" enctype="multipart/form-data"
                          class="dropzone" id="fileUploadZone" style="min-height: 200px; border-style: dashed; border: 2px dashed #ccc; padding: 20px; margin-bottom: 30px;">
                        @csrf
                        <input type="hidden" name="record_id" id="recordIdInput">
                        <div class="dz-message text-center text-muted">
                            <div class="mb-3">
                                <i class="bi bi-cloud-arrow-up" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="fw-bold mb-2">برای آپلود فایل، کلیک کنید یا فایل را بکشید اینجا</h5>
                            <p class="small text-secondary mb-0">فرمت‌های مجاز: JPG, PNG, PDF, MP4, DOCX (حداکثر 10 مگابایت)</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{asset('assets/vendor/js/dataTables.min.js')}}"></script>
    <script src="{{'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js'}}"></script>
    <script src="{{'https://cdn.datatables.net/fixedcolumns/5.0.4/js/dataTables.fixedColumns.js'}}"></script>
    <script src="{{'https://cdn.datatables.net/fixedcolumns/5.0.4/js/fixedColumns.dataTables.js'}}"></script>

    <script type="text/javascript">
        $(function () {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, 'desc']],
                scrollX: true,
                scrollCollapse: true,
                ajax: "{{ route(request()->segment(2) . '.index') }}",
                columns: [
                    {data: 'action'                         , name: 'action', orderable: true, searchable: true},
                    {data: 'logo'                           , name: 'logo'},
                    {data: 'commercial_name'                , name: 'commercial_name'},
                    {data: 'company_name'                   , name: 'company_name'},
                    {data: 'ceo_name'                       , name: 'ceo_name'},
                    {data: 'registration_number'            , name: 'registration_number'},
                    {data: 'national_id'                    , name: 'national_id'},
                    {data: 'registration_date'              , name: 'registration_date'},
                    {data: 'economic_code'                  , name: 'economic_code'},
                    {data: 'legal_type'                     , name: 'legal_type'},
                    {data: 'website'                        , name: 'website'},
                    {data: 'action'                         , name: 'action', orderable: true, searchable: true},
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
            let deleteId = null;

            function showToast(message, type = 'success') {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 3000,
                    rtl: true
                };
                toastr[type] ? toastr[type](message) : toastr.success(message);
            }

            // وقتی روی دکمه حذف کلیک شد
            $(document).on('click', '.delete-btn', function () {
                deleteId = $(this).data('id');
                $('#deleteModal').modal('show');
            });

            // وقتی تایید حذف کلیک شد
            $('#confirmDelete').on('click', function (e) {
                const $btn = $(this);
                const originalHtml = $btn.html();

                $btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال حذف...'
                );

                $.ajax({
                    url: "{{ route(request()->segment(2).'.destroy', 0) }}",
                    method: 'DELETE',
                    data: { "_token": "{{ csrf_token() }}", id: deleteId },
                    success: function () {
                        $('#deleteModal').modal('hide');
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const amountInput = document.getElementById('amount');
            if (amountInput) {
                amountInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/,/g, '');
                    if (!isNaN(value) && value.length > 0) {
                        e.target.value = Number(value).toLocaleString('en-US');
                    } else {
                        e.target.value = '';
                    }
                });
            }
        });
    </script>

    <script>
        Dropzone.autoDiscover = false;

        document.addEventListener("DOMContentLoaded", function () {
            const fileFormSelector = "#fileUploadZone";
            let currentRecordId = null;

            const dz = new Dropzone(fileFormSelector, {
                url: "{{ route('storemedia') }}",
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                maxFilesize: 20,
                acceptedFiles: 'image/*,video/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                dictDefaultMessage: "فایل‌ها را اینجا رها کنید یا کلیک کنید برای انتخاب",
                init: function () {
                    this.on("sending", function (file, xhr, formData) {

                        formData.append("record_id", currentRecordId || document.getElementById('recordIdInput').value);
                    });
                    this.on("success", function (file, response) {
                        const extension = file.name.split('.').pop().toLowerCase();
                        previewFile(response.file_path.replace(/^\/+/, ''), extension);
                        showToast("✅ فایل با موفقیت آپلود شد");
                        this.removeFile(file);
                    });
                    this.on("error", function (file, response) {
                        showToast("❌ خطا در آپلود فایل", "danger");
                    });
                }
            });

            $(document).on('click', '.upload-btn', function () {
                currentRecordId = $(this).data('id');
                $('#recordIdInput').val(currentRecordId);

                dz.removeAllFiles(true);

                $('#uploadModal').modal('show');
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let activeInputId = null;
            document.querySelectorAll('.file-selector').forEach(input => {
                input.addEventListener('click', function () {
                    const recordId = this.dataset.recordId;
                    activeInputId = this.dataset.inputId;

                    window.open(`{{ route('selectfile') }}?record_id=${recordId}`, 'FileManager', 'width=800,height=600');
                });
            });
            window.setFileUrl = function (url) {
                if (activeInputId) {
                    document.getElementById(activeInputId).value = url;
                }
            };
        });
    </script>
@endsection
