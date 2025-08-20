@extends('layouts.base')

@section('title', 'پروفایل حساب کاربری')

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
                    <div class="tab-pane fade show active justify-content-center" id="navs-user-card" role="tabpanel">
                        <div class="mb-12 col-md-12">
                            <div class="card-body">
                                <div id="userProfileCard" class="">
                                    <div class="card border-0 shadow-sm mb-4" style="max-width:480px; margin:0 auto; border-radius: 1.25rem;">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="rounded-circle d-flex justify-content-center align-items-center shadow-sm" style="width:56px; height:56px; background:#f2f3f6;">
                                                        @if(Auth::user()->gender == 1)
                                                            <img src="{{ asset('assets/img/avatars/1.png') }}"
                                                                 class="img-fluid rounded mb-3 mt-4" height="100" width="100" alt="User avatar"/>
                                                        @elseif(Auth::user()->gender == 2)
                                                            <img src="{{ asset('assets/img/avatars/8.png') }}"
                                                                 class="img-fluid rounded mb-3 mt-4" height="100" width="100" alt="User avatar"/>
                                                        @else
                                                            <img src="{{ asset('assets/img/avatars/1.png') }}"
                                                                 class="img-fluid rounded mb-3 mt-4" height="100" width="100" alt="User avatar"/>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold mb-1" style="font-size:1.2rem;">{{ Auth::user()->name }}</div>
                                                    </div>
                                                </div>
                                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="toggleEditMode('user')" style="font-size:.98rem">
                                                    <i class="mdi mdi-pencil-outline"></i>
                                                    <span class="d-none d-md-inline">ویرایش</span>
                                                </button>
                                            </div>

                                            <dl class="row g-3 pt-3" style="font-size: 0.96rem;">
                                                <div class="col-12 d-flex">
                                                    <dt class="col-5 text-start text-muted">نام و نام خانوادگی</dt>
                                                    <dd id="company-registration-number" class="col-7 text-dark mb-0">{{ Auth::user()->name }}</dd>
                                                </div>
                                                <div class="col-12 d-flex border-top pt-3">
                                                    <dt class="col-5 text-start text-muted">ادرس ایمیل</dt>
                                                    <dd id="company-national-id" class="col-7 text-dark mb-0">{{ Auth::user()->email }}</dd>
                                                </div>
                                                <div class="col-12 d-flex border-top pt-3">
                                                    <dt class="col-5 text-start text-muted">شماره موبایل</dt>
                                                    <dd id="company-phone" class="col-7 text-dark mb-0" dir="ltr" style="font-family: monospace">{{ Auth::user()->phone }}</dd>
                                                </div>
                                                <div class="col-12 d-flex border-top pt-3">
                                                    <dt class="col-5 text-start text-muted">جنسیت</dt>
                                                    <dd id="company-email" class="col-7 text-dark mb-0" dir="ltr" style="font-family: monospace">{{ Auth::user()->gender == 2 ? 'زن' : (Auth::user()->gender == 1 ? 'مرد' : '') }}</dd>
                                                </div>
                                                <div class="col-12 d-flex border-top pt-3">
                                                    <dt class="col-5 text-start text-muted">آدرس:</dt>
                                                    <dd id="company-address" class="col-7 text-dark mb-0">{{ $company->address }}</dd>
                                                </div>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->level == 'applicant')
                        @include('profile.tab_user_profile')

                        @include('profile.tab_company_profile')

                    <!-- فرایند سرمایه گذاری -->
                        @include('profile.tab_investment_steps')

                    <!-- فایل ها و مستندات -->
                        @include('profile.tab_documents')

                    <!-- صورتجلسات -->
                        @include('profile.tab_minutes')

                    <!-- تعهدات و تضامین -->
                        @include('profile.tab_guarantees')

                    @elseif(Auth::user()->level == 'investor')
                        @include('profile.tab_investor_projects')

                    @endif
                </div>
            </div>
        </div>
        @endsection

        @push('scripts')
            <script>
                function toggleEditMode(type) {
                    document.getElementById(type + 'ProfileCard').classList.toggle('d-none');
                    document.getElementById(type + 'EditForm').classList.toggle('d-none');
                }
            </script>
            <script src="{{asset('assets/vendor/js/sweetalert2.js')}}"></script>
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
                                    toggleEditMode();
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

    @endpush

