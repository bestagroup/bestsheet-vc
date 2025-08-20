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
                                <div class="user-avatar-section">
                                    <div class="d-flex align-items-center flex-column">
                                        @if(Auth::user()->gender == 1)
                                            <img src="{{ asset('assets/img/avatars/1.png') }}"
                                                 class="img-fluid rounded mb-3 mt-4" height="120" width="120" alt="User avatar"/>
                                        @elseif(Auth::user()->gender == 2)
                                            <img src="{{ asset('assets/img/avatars/8.png') }}"
                                                 class="img-fluid rounded mb-3 mt-4" height="120" width="120" alt="User avatar"/>
                                        @else
                                            <img src="{{ asset('assets/img/avatars/1.png') }}"
                                                 class="img-fluid rounded mb-3 mt-4" height="120" width="120" alt="User avatar"/>
                                        @endif

                                        <div class="user-info text-center">
                                            <h4>{{ Auth::user()->name }}</h4>
                                            <span class="badge bg-label-danger">
                            @php
                                use Illuminate\Support\Facades\DB;
                                $roleName = DB::table('roles')->where('id', Auth::user()->role_id)->value('title_fa');
                            @endphp
                                                {{ $roleName }}
                        </span>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="pb-3 border-bottom mb-3 mt-4">مشخصات فردی</h5>

                                {{-- فرم اینلاین ویرایش --}}
                                <form id="editUserForm" class="row g-4" action="#" method="POST" style="max-width:720px;margin:0 auto;text-align:right;">
                                    @csrf
                                    <div class="col-12 col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" id="name" name="name" class="form-control"
                                                   placeholder="نام و نام خانوادگی" value="{{ Auth::user()->name }}">
                                            <label for="name">نام و نام خانوادگی</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" id="username" name="username" class="form-control"
                                                   placeholder="نام کاربری" value="{{ Auth::user()->username }}">
                                            <label for="username">نام کاربری</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" id="national_id" name="national_id" class="form-control"
                                                   placeholder="کد ملی" value="{{ Auth::user()->national_id }}">
                                            <label for="national_id">کد ملی</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input type="email" id="email" name="email" class="form-control"
                                                   placeholder="ایمیل" value="{{ Auth::user()->email }}">
                                            <label for="email">ایمیل</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input type="number" id="age" name="age" class="form-control"
                                                   placeholder="سن" value="{{ Auth::user()->age ?? '' }}">
                                            <label for="age">سن</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <select id="gender" name="gender" class="form-select">
                                                <option value="">انتخاب</option>
                                                <option value="1" {{ Auth::user()->gender == 1 ? 'selected' : '' }}>مرد</option>
                                                <option value="2" {{ Auth::user()->gender == 2 ? 'selected' : '' }}>زن</option>
                                            </select>
                                            <label for="gender">جنسیت</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" id="phone" name="phone" class="form-control"
                                                   placeholder="شماره موبایل" value="{{ Auth::user()->phone }}">
                                            <label for="phone">شماره موبایل</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" id="telephone" name="telephone" class="form-control"
                                                   placeholder="شماره تماس" value="{{ Auth::user()->telephone ?? '' }}">
                                            <label for="telephone">شماره تماس</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating form-floating-outline">
                        <textarea id="address" name="address" class="form-control" rows="3"
                                  placeholder="آدرس">{{ Auth::user()->address ?? '' }}</textarea>
                                            <label for="address">آدرس ثبتی</label>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary me-sm-3 me-1">ذخیره</button>
                                        <button type="reset" class="btn btn-outline-secondary">انصراف</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

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
                function toggleEditMode() {
                    document.getElementById('companyProfileCard').classList.toggle('d-none');
                    document.getElementById('companyEditForm').classList.toggle('d-none');
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

