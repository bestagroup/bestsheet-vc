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

                    @include('profile.tab_user_info')

                    @if(Auth::user()->level == 'applicant')
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

