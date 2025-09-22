@extends('layouts.base')
@section('title', 'مدیریت فایل‌ها')
<style> table{margin: 0 auto;width: 100% !important;clear: both;border-collapse: collapse;table-layout: fixed;word-wrap:break-word;} .dt-layout-start{margin-right: 0 !important;} .dt-layout-end{margin-left: 0 !important;}</style>
<link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
<link href="{{'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css'}}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css"/>
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">{{$thispage['list']}}</h5>
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">{{$thispage['add']}}</a>
            </div>

            <div class="table-responsive">
                <table id="sample1" class="table table-striped table-bordered yajra-datatable">
                    <thead>
                    <tr class="table-light">
                        <th> فایل</th>
                        <th>نام فایل</th>
                        <th>نام اصلی فایل</th>
                        <th>نوع فایل</th>
                        <th>سایز فایل</th>
                        <th>تاریخ آپلود</th>
                        <th>پروژه</th>
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
    @foreach($mediafiles as $mediafile)
        <div class="modal fade" id="deleteModal{{$mediafile->id}}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                        <button type="button" class="btn btn-danger" id="deletesubmit_{{$mediafile->id}}" data-id="{{$mediafile->id}}">حذف</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach($mediafiles as $mediafile)
        <div class="modal fade" id="editModal{{$mediafile->id}}" tabindex="-1" aria-labelledby="editModalLabel{{$mediafile->id}}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{$mediafile->id}}">{{$thispage['edit']}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editform_{{ $mediafile->id }}" method="POST" action="{{ route(request()->segment(2).'.update', $mediafile->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="menu_id" id="menu_id_{{$mediafile->id}}" value="{{$mediafile->id}}" />
                            <div class="row mb-3 ">
                                <div class="col-md-4">
                                    <label class="form-label">انتخاب نوع فایل</label>
                                    <select name="subject_id" id="subject_id_{{$mediafile->id}}" class="form-control select-lg select2">
                                        <option value="">انتخاب نوع فایل</option>
                                        @foreach($subject_files as $subject_file)
                                            <option value="{{$subject_file->id}}" {{$mediafile->subject_id == $subject_file->id ? 'selected' : '' }}>{{$subject_file->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" id="editsubmit_{{$mediafile->id}}" class="btn btn-primary" >ذخیره اطلاعات</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">{{$thispage['add']}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('storemedia') }}" enctype="multipart/form-data" class="dropzone dz-clickable border rounded-3 shadow-sm bg-light p-4" id="fileUploadZone" style="min-height: 220px; border-style: dashed;">
                        @csrf

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

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">پیش نمایش فایل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body text-center" id="previewContent">
                    <!-- فایل پیش نمایش اینجا لود می‌شود -->
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{asset('assets/vendor/js/dataTables.min.js')}}"></script>
    <script src="{{'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js'}}"></script>
    <script src="{{'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js'}}"></script>

    <script type="text/javascript">
        $(function () {

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, 'desc']],
                ajax: "{{route(request()->segment(2).'.index')}}",
                columns: [
                    {data: 'file_path'      , name: 'file_path' },
                    {data: 'name'           , name: 'name'     },
                    {data: 'original_name'  , name: 'original_name'     },
                    {data: 'type'           , name: 'type'      },
                    {data: 'size'           , name: 'size'      },
                    {data: 'date'           , name: 'date'      },
                    {data: 'title'          , name: 'title'      },
                    {data: 'action'         , name: 'action', orderable: true, searchable: true},
                ],
                language: {
                    url: "{{asset('assets/vendor/js/fa.json')}}"
                }
            });

        });
    </script>
    <script>
        function previewFile(fileUrl, extension) {
            let previewContent = '';
            const fullUrl = `/${fileUrl}`;

            const imageExt = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
            const videoExt = ['mp4', 'webm', 'ogg'];
            const iframeDocs = ['pdf'];
            const officeDocs = ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'];

            if (imageExt.includes(extension)) {
                previewContent = `<img src="${fullUrl}" class="img-fluid" alt="Preview Image">`;
            } else if (videoExt.includes(extension)) {
                previewContent = `<video controls style="width: 100%; max-height: 500px;">
            <source src="${fullUrl}" type="video/${extension}">
            مرورگر شما از این فرمت ویدیو پشتیبانی نمی‌کند.
        </video>`;
            } else if (iframeDocs.includes(extension)) {
                previewContent = `<iframe src="${fullUrl}" style="width:100%; height:500px; border:none;"></iframe>`;
            } else if (officeDocs.includes(extension)) {
                previewContent = `<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=${window.location.origin}/${fileUrl}"
            style="width:100%; height:500px; border:none;"></iframe>`;
            } else {
                previewContent = `<p class="text-center">پیش‌نمایش برای این نوع فایل در دسترس نیست.</p>`;
            }

            document.getElementById('previewContent').innerHTML = previewContent;
            new bootstrap.Modal(document.getElementById('previewModal')).show();
        }

        document.addEventListener("DOMContentLoaded", function () {
            if (!Dropzone.instances.length) {
                let uploadedFilesCount = 0;
                let totalFilesToUpload = 0;

                const dz = new Dropzone("#fileUploadZone", {
                    url: "{{ route('storemedia') }}",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    maxFilesize: 20,
                    parallelUploads: 5,
                    uploadMultiple: false,
                    acceptedFiles: 'image/*,video/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    dictDefaultMessage: "فایل را اینجا رها کنید یا کلیک کنید برای انتخاب",
                    previewTemplate: `
<div class="dz-preview dz-file-preview d-flex flex-column align-items-center me-2 mb-3" style="width: 160px;">
  <div class="card border rounded shadow-sm text-center p-2 position-relative" style="width: 100%;">
    <div class="dz-image mb-2 rounded overflow-hidden" style="width: 100%; height: 100px; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
      <img data-dz-thumbnail style="max-height: 100%; max-width: 100%; object-fit: cover;" class="img-fluid"  alt="" src=""/>
    </div>
    <div class="dz-details w-100">
      <div class="text-truncate fw-bold small text-dark" data-dz-name></div>
      <div class="text-muted small mb-1" data-dz-size></div>
      <div class="progress w-100 mb-1" style="height: 4px;">
        <div class="progress-bar bg-primary" role="progressbar" data-dz-uploadprogress style="width: 0;"></div>
      </div>
      <div class="upload-percent badge bg-primary position-absolute top-0 end-0 m-1" style="font-size: 0.7rem;">0%</div>
    </div>
  </div>
</div>
`,

                    init: function () {
                        this.on("addedfile", function () {
                            totalFilesToUpload++;
                        });

                        this.on("uploadprogress", function (file, progress) {
                            const progressBar = file.previewElement.querySelector("[data-dz-uploadprogress]");
                            const percentBadge = file.previewElement.querySelector(".upload-percent");
                            if (progressBar && percentBadge) {
                                progressBar.style.width = progress + "%";
                                percentBadge.innerText = `${Math.round(progress)}%`;
                            }
                        });

                        this.on("success", function (file, response) {
                            uploadedFilesCount++;
                            const extension = file.name.split('.').pop().toLowerCase();
                            previewFile(response.file_path.replace(/^\/+/, ''), extension);

                            if (uploadedFilesCount === totalFilesToUpload) {
                                setTimeout(() => {
                                    bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();

                                    if (window.sample1Table) {
                                        window.sample1Table.ajax.reload(null, false);
                                    } else {
                                        location.reload();
                                    }

                                    showToast('✅ فایل‌ها با موفقیت آپلود شدند.');
                                }, 800);
                            }
                        });

                        this.on("error", function (file, response) {
                            console.error("Upload error:", response);
                            showToast("❌ خطا در آپلود فایل", "danger");
                        });
                    }
                });
            }

            // تابع Toast Bootstrap
            window.showToast = function (message, type = "success") {
                const toast = document.createElement("div");
                toast.className = `toast align-items-center text-bg-${type} border-0 show position-fixed bottom-0 end-0 m-4`;
                toast.setAttribute("role", "alert");
                toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="بستن"></button>
            </div>`;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 4000);
            };
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
        jQuery(document).ready(function(){
            jQuery('[id^=deletesubmit_]').click(function(e){
                e.preventDefault();

                var button = jQuery(this);
                var id = button.data('id');
                var originalButtonHtml = button.html(); // متن اصلی دکمه رو ذخیره کن
                // قفل کردن دکمه + گذاشتن اسپینر
                button.prop('disabled', true);
                button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال حذف...');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                jQuery.ajax({
                    url: "{{ route(request()->segment(2).'.destroy', 0) }}",
                    method: 'delete',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: id,
                    },
                    success: function (data) {
                        // مدال را ببند
                        var modalId = '#deleteModal' + id;
                        var modal = bootstrap.Modal.getInstance(document.querySelector(modalId));
                        modal.hide();

                        // جدول را رفرش کن
                        $('.yajra-datatable').DataTable().ajax.reload(null, false);
                    },
                    error: function () {
                        alert('مشکلی پیش آمد. لطفاً دوباره تلاش کنید.');
                    },
                    complete: function () {
                        // چه موفق باشه چه خطا بده، دکمه رو برگردون
                        button.prop('disabled', false);
                        button.html(originalButtonHtml);
                    }
                });
            });
        });
    </script>
@endsection
