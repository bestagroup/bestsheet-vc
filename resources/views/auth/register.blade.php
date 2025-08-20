@extends('layouts.auth')
@section('title', 'ایجاد حساب و ثبت طرح')
@section('content')
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
            <!-- Register Card -->
            <div class="card p-2">
                <div class="app-brand justify-content-center mt-5">
                    <a href="{{ url('/') }}" class="app-brand-link gap-2">
          <span class="app-brand-logo demo">
            <img src="{{ asset('assets/img/sinavclogo.png') }}" alt="توسعه دانش بنیان سینا" width="40">
          </span>
                        <span class="app-brand-text demo text-heading fw-bold">توسعه دانش بنیان سینا</span>
                    </a>
                </div>

                <div class="card-body mt-2">
                    <h4 class="mb-2 fw-semibold"> ایجاد حساب و ثبت طرح </h4>
                    <p class="mb-4">لطفا اطلاعات زیر را با دقت وارد کنید</p>

                    <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('fullregister') }}">
                        @csrf
                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text" class="form-control" id="title" name="title" placeholder="نام شرکت / نام طرح" required>
                            <label for="name">نام شرکت / نام طرح</label>
                        </div>

                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text" class="form-control" id="CEO" name="CEO" placeholder="نام رابط / نام مدیرعامل" required>
                            <label for="name">نام رابط / نام مدیرعامل</label>
                        </div>

                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="شماره همراه" required>
                            <label for="name">شماره همراه</label>
                        </div>

                        <div class="form-floating form-floating-outline mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="آدرس ایمیل" required>
                            <label for="email">آدرس ایمیل</label>
                        </div>

                        <div class="mb-3">
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" id="password" class="form-control" name="password" placeholder="رمز عبور" required>
                                        <label for="password">رمز عبور</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="تایید رمز عبور" required>
                                        <label for="password_confirmation">تکرار رمزعبور</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 d-flex justify-content-between">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms-accepted" name="terms_accepted" required>
                                <label class="form-check-label" for="terms-accepted">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">شرایط و قوانین</a>  را با دقت مطالعه نموده ام.
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">{{ __('ثبت اطلاعات') }}</button>
                        </div>
                    </form>

                    <p class="text-center">
                        <span>حساب کاربری دارید؟</span>
                        <a href="{{ route('login') }}">
                            <span>ورود به حساب</span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">شرایط و قوانین شرکت توسعه دانش بنیان سینا</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <style>
                    li {
                        display: block;
                        padding-left: 1.1em;
                        padding-bottom: 30px;
                    }
                </style>
                <div class="modal-body">
                    <p>نکات بسیار مهم جهت بررسی و تایید طرح ها : </p>
                    <ul>
                        <li>1. فایل ارسالی شما جهت آشنایی با تیم و کلیات طرح شماست و تکمیل آن به معنی تضمین برگزاری جلسه نیست. کارشناسان ما طرح شما را به صورت کامل بررسی می کنند و در صورت پذیرش با شما تماس خواهند گرفت.</li>
                        <li>2. کسب و کار سینا وی سی، سرمایه‌گذاری است و علاقه‌ای به اجرای ایده‌ها ندارد. در نتیجه در بیان جزئیات کار خود صادق و دقیق بوده و اطمینان داشته باشید ایده‌ی شما دزدیده نخواهد شد! همچنین فراموش نکنید که معیار تصمیم‌گیری سینا وی سی برای برگزاری جلسه با شما، چیزی است که در فایل ارسالی شما مکتوب شده است.</li>
                        <li>3. درخواست های بدون تیم بررسی نخواهند شد. پس از برگزاری جلسات اولیه، ممکن است سینا وی سی درخواست کند تمام تیم شما در جلساتی حضور داشته باشند؛ پس آن دسته از هم‌تیمی‌های خود را که واقعاً با شما همراهند در اینجا معرفی کنید.</li>
                        <li>4. ایده‌های اجرا نشده بررسی نخواهند شد. ایده‌ها باید حداقل دارای دمو یا نمونه‌ اولیه یا کمینه محصول پذیرفتنی (MVP) باشند. همچنین طرح‌های اجرا شده‌ بدون کاربر، به سختی ممکن است حائز شرایط بررسی توسط سینا وی سی شوند.</li>
                        <li>5. طرح شما حتما باید در حوزه زنجیره ارزش بنیاد مستضعفان باشد. لطفا پیش از پر کردن فرم، به صفحه اصلی سایت سر بزنید و کنترل کنید که آیا طرح شما در حوزه زنجیره ارزش بنیاد قرار دارد یا نه.</li>
                        <li>6. رقبای‌تان را شناسایی کنید. معمولاً در همه زمینه‌ها کسانی هستند که قبل یا همزمان با شما شروع به کار کرده‌اند. در بیان مزیت‌های رقیب نسبت به شما و شما نسبت به رقیب، صادق باشید. عدم معرفی رقیب، یک امتیاز منفی بزرگ برای شما.</li>
                        <li>7. سرمایه درخواستی برای طرح ارسالی باید کمتر از 200 میلیارد ریال باشد.</li>
                        <li>8. شرکت توسعه دانش بنیان سینا طی مذاکرات آتی تا سقف 49 درصد از شرکت سرمایه پذیر سهام دریافت می کند.</li>
                        <li>9. حداکثر زمان خروج سینا وی سی از شرکت سرمایه پذیر 5 سال می باشد.</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const togglePasswordElements = document.querySelectorAll('.form-password-toggle');

            togglePasswordElements.forEach(function (wrapper) {
                const toggleButton = wrapper.querySelector('.input-group-text');
                const inputField = wrapper.querySelector('input');
                const icon = toggleButton.querySelector('i');

                toggleButton.addEventListener('click', function () {
                    const type = inputField.type === 'password' ? 'text' : 'password';
                    inputField.type = type;
                    icon.classList.toggle('mdi-eye-outline');
                    icon.classList.toggle('mdi-eye-off-outline');
                });
            });
        });
    </script>
    <script>
        @if (session('success'))
        toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
        toastr.error("{{ session('error') }}");
        @endif
    </script>
@endsection
