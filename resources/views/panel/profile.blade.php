@extends('layouts.base')

@section('title', ' حساب کاربری')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dropzone.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
    <link rel="stylesheet" href="{{'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'}}" />
    <style> table{margin: 0 auto;width: 100% !important;clear: both;border-collapse: collapse;table-layout: auto !important;word-wrap:break-word;white-space: nowrap;} .dt-layout-start{margin-right: 0 !important;} .dt-layout-end{margin-left: 0 !important;}</style>
    <style>.nav-tabs .nav-link.active {border-bottom: 3px solid #7367f0 !important;}</style>
@endsection

<style>
    .nav-tabs .nav-link.active {
        border-bottom: 3px solid #7367f0 !important;
    }
</style>

@section('content')
    <div class="container mt-4">
        <div class="card text-center mb-3">
            <div class="card-header">
                <div class="nav-align-top">
                    @include('profile.nav_tabs')
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content pb-0">

                    @include('profile.tab_user_info')

                    @if(Auth::user()->level == 'applicant')
                        @include('profile.tab_company_profile')

                        @include('profile.tab_project_profile')

                        @include('profile.tab_investment_steps')

                        @include('profile.tab_documents')

                        @include('profile.tab_minutes')

                        @include('profile.tab_guarantees')

                    @elseif(Auth::user()->level == 'investor')
                        @include('profile.tab_investor_projects')

                    @endif
                </div>
            </div>
        </div>
        @endsection

        @push('scripts')
            <script src="{{'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js'}}"></script>
            <script>
                function toggleEditMode(section) {
                    if (section === 'user') {
                        document.getElementById('userProfileCard').classList.toggle('d-none');
                        document.getElementById('userEditForm').classList.toggle('d-none');
                    }
                    if (section === 'company') {
                        document.getElementById('companyProfileCard').classList.toggle('d-none');
                        document.getElementById('companyEditForm').classList.toggle('d-none');
                    }
                }

            </script>
            <script src="{{asset('assets/vendor/js/sweetalert2.js')}}"></script>
            <script src="{{asset('assets/vendor/js/dataTables.min.js')}}"></script>
            <script type="text/javascript">
                var tableInitialized = false;
                document.querySelector('button[data-bs-target="#navs-minutes-card"]')
                    .addEventListener('shown.bs.tab', function () {
                        if (!tableInitialized) {
                            $('.yajra-datatable').DataTable({
                                processing: true,
                                serverSide: true,
                                order: [[0, 'desc']],
                                scrollX: true,
                                scrollCollapse: true,
                                ajax: {
                                    url: "{{ route('minute.index') }}",
                                    data: function (d) {
                                        d.id = "{{ $company->id }}";
                                    }
                                },
                                columns: [
                                    {data: 'title'  , name: 'title'},
                                    {data: 'date'   , name: 'date'},
                                    {data: 'type'   , name: 'type'},
                                    {data: 'file'   , name: 'file', orderable: true, searchable: true},
                                ],
                                language: {
                                    url: "{{ asset('assets/vendor/js/fa.json') }}"
                                }
                            });
                            tableInitialized = true;
                        }
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
                    $('#submitaddminut').on('click', function(e){
                        e.preventDefault();
                        var $btn  = $(this);
                        var $form = $('#addminuteform');
                        var originalHtml = $btn.html();

                        $btn.prop('disabled', true)
                            .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...');

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: "{{ route('minute.store') }}",
                            method: 'POST',
                            data: $form.serialize(),
                            success: function (data) {
                                if (data.success) {
                                    const modalEl = document.getElementById('addMinutesModal');
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
                                    const company = data.data;
                                    $('#company-registration-number').text(company.registration_number || '');
                                    $('#company-national-id').text(company.national_id || '');
                                    $('#company-phone').text(company.phone || '');
                                    $('#company-email').text(company.email || '');
                                    $('#company-address').text(company.address || '');
                                    toggleEditMode('company');
                                    showToast('آیتم با موفقیت ویرایش شد!', 'success');
                                }

                                else {
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

                    $(document).on('click', '[id^=editusersubmit_]', function(e){
                        e.preventDefault();
                        const $btn = $(this);
                        const id = this.id.split('_')[1];
                        const $form = $('#edituserform_' + id);

                        if (!$form.length) {
                            console.error('فرم edituserform_' + id + ' پیدا نشد!');
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
                                    const user = data.data;
                                    $('#user_national_id').text(user.user_national_id || '');
                                    $('#user_phone').text(user.user_phone || '');
                                    $('#user_email').text(user.user_email || '');
                                    $('#user_address').text(user.user_address || '');
                                    toggleEditMode('user');
                                    showToast('آیتم با موفقیت ویرایش شد!', 'success');
                                }

                                else {
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
                document.querySelectorAll('.upload-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        let recordId    = this.getAttribute('data-id');
                        let subjectId   = this.getAttribute('data-subject');
                        let title       = this.getAttribute('data-title');

                        document.getElementById('recordIdInput').value  = recordId;
                        document.getElementById('subjectIdInput').value = subjectId;
                        document.getElementById('fileTitleInput').value = title;

                        document.getElementById('uploadModalLabel').innerText = "بارگذاری فایل " + title;

                        let modal = new bootstrap.Modal(document.getElementById('uploadModal'));
                        modal.show();
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
            <script>
                $(document).ready(function() {
                    // فعال‌سازی سرچ روی همه select ها
                    $('.select2').select2({
                        width: '100%',
                        placeholder: 'انتخاب کنید',
                        allowClear: true,
                        language: {
                            noResults: function () {
                                return "موردی یافت نشد";
                            }
                        }
                    });
                    $('#state_{{$company->id}}').on('change', function () {
                        let stateId = $(this).val();
                        let $citySelect = $('#city_{{$company->id}}');
                        $citySelect.html('<option value="">در حال بارگذاری...</option>').trigger('change');

                        if (stateId) {
                            $.get(`panel/getcities/${stateId}`, function (data) {
                                $citySelect.empty().append('<option value="">انتخاب کنید</option>');
                                data.forEach(function (city) {
                                    $citySelect.append(new Option(city.title, city.id));
                                });
                                $citySelect.trigger('change');
                            });
                        } else {
                            $citySelect.html('<option value="">انتخاب کنید</option>').trigger('change');
                        }
                    });
                });
            </script>
    @endpush

