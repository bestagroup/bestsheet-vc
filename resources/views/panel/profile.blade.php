@extends('layouts.base')
@section('title', ' حساب کاربری')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dropzone.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
    <link rel="stylesheet" href="{{'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'}}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style> table {
            margin: 0 auto;
            width: 100% !important;
            clear: both;
            border-collapse: collapse;
            table-layout: auto !important;
            word-wrap: break-word;
            white-space: nowrap;
        }

        .dt-layout-start {
            margin-right: 0 !important;
        }

        .dt-layout-end {
            margin-left: 0 !important;
        }</style>
    <style>.nav-tabs .nav-link.active {
            border-bottom: 3px solid #7367f0 !important;
        }</style>
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

                        @include('profile.tab_sales')

                        @include('profile.tab_contracts')

                        @include('profile.tab_payments')

                    @elseif(Auth::user()->level == 'investor')
                        @include('profile.tab_investor_projects')
                    @endif
                </div>
            </div>
        </div>
        @endsection

        @push('scripts')
            <script src="{{asset('assets/vendor/js/sweetalert2.js')}}"></script>
            <script src="{{asset('assets/vendor/js/dataTables.min.js')}}"></script>
            <script src="{{asset('assets/vendor/js/formhandler.js')}}"></script>

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

            @if(Auth::user()->level == 'applicant')

                <script>
                    document.addEventListener("DOMContentLoaded", function() {

                        let hash = window.location.hash;
                        if (hash) {
                            let triggerEl = document.querySelector(`button[data-bs-target="${hash}"]`);
                            if (triggerEl) {
                                let tab = new bootstrap.Tab(triggerEl);
                                tab.show();
                            }
                        }

                        document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(function(tabButton) {
                            tabButton.addEventListener('shown.bs.tab', function (event) {
                                let target = event.target.getAttribute('data-bs-target');
                                history.replaceState(null, null, target);
                            });
                        });
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
                        $('#state_{{$project->id}}').on('change', function () {
                            let stateId = $(this).val();
                            let $citySelect = $('#city_{{$project->id}}');
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
            @endif

            <script>
                Dropzone.autoDiscover = false;

                document.addEventListener("DOMContentLoaded", function () {
                    const fileFormSelector = "#fileUploadZone";
                    let currentRecordId = null;

                    const dz = new Dropzone(fileFormSelector, {
                        url: "{{ route('storemedia') }}",
                        headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
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
                        let recordId = this.getAttribute('data-id');
                        let subjectId = this.getAttribute('data-subject');
                        let title = this.getAttribute('data-title');

                        document.getElementById('recordIdInput').value = recordId;
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
                document.querySelectorAll('.price-input').forEach(input => {
                    input.addEventListener('input', function (e) {
                        let value = e.target.value.replace(/,/g, '');
                        value = value.replace(/\D/g, '');
                        if (value) {
                            e.target.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        } else {
                            e.target.value = '';
                        }
                    });
                });
            </script>



            <script>
                (function () {

                    $(document).on('click', '.approve-btn', function(){
                        const $form = $(this).closest('form');
                        $form.find('.status-input').val('approved');
                        $form.find('.real-submit').trigger('click');
                    });

                    $(document).on('click', '.reject-btn', function(){
                        const $form = $(this).closest('form');
                        $form.find('.status-input').val('rejected');
                        $form.find('.real-submit').trigger('click');
                    });

                    $(document).on('submit', '.flow-form', function(e) {
                        e.preventDefault();
                        handleCreate(this);
                    });

                    $(document).ajaxSuccess(function (event, xhr, settings, data) {
                        if (!data || !data.success) return;

                        const $form = $('form[data-flow="true"]').filter(function () {
                            return $(this).attr('action') === settings.url;
                        }).last();

                        if (!$form.length) return;

                        const status = $form.find('[name="status"]').val();
                        const current = parseInt($form.data('current-step'), 10);
                        const next = parseInt($form.data('next-step'), 10);

                        if (status === 'rejected') {
                            const $item = $('#step-item-' + current);
                            $item.removeClass('active');
                            if ($item.find('.text-danger').length === 0) {
                                $item.append('<span class="ms-auto text-danger">✖</span>');
                            }
                            showToast('مرحله رد شد', 'warning');
                            return;
                        }

                        const $curItem = $('#step-item-' + current);
                        const $nextItem = $('#step-item-' + next);

                        $curItem.removeClass('active');
                        $curItem.find('.now-badge').remove();
                        if ($curItem.find('.text-success').length === 0) {
                            $curItem.append('<span class="ms-auto text-success">✔</span>');
                        }

                        if ($nextItem.length) {
                            $nextItem.addClass('active');
                            $nextItem.find('.now-badge').remove();
                            $nextItem.append('<span class="badge text-bg-primary now-badge">اکنون</span>');
                            $('#step-panel-' + current).hide();
                            $('#step-panel-' + next).show();
                            showToast('مرحله با موفقیت تائید شد', 'success');
                        } else {
                            showToast('🎉 همه مراحل تکمیل شد', 'success');
                        }
                    });

                    $('#resetFlow').on('click', function () {
                        $.post("{{ route('demo.flow.store') }}", {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            project_id: {{ $project->id }},
                            step_id: 0,
                            step_title: 'reset',
                            status: 'approved',
                            description: 'reset to 1'
                        }).always(function(){
                            window.location.reload();
                        });
                    });

                })();
            </script>



    @endpush
