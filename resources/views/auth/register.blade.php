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
                    <h4 class="mb-2 fw-semibold">ایجاد حساب و ثبت طرح</h4>
                    <p class="mb-4">لطفاً اطلاعات زیر را با دقت وارد کنید</p>


                    @include('partials.alerts')


                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>خطا در ارسال فرم:</strong>
                            <ul class="mb-0 mt-2 pe-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('fullregister') }}" novalidate>
                        @csrf

                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title"
                                   placeholder="نام شرکت / نام طرح"
                                   value="{{ old('title') }}" required>
                            <label for="title">نام شرکت / نام طرح</label>
                            @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text"
                                   class="form-control @error('CEO') is-invalid @enderror"
                                   id="CEO" name="CEO"
                                   placeholder="نام رابط / نام مدیرعامل"
                                   value="{{ old('CEO') }}" required>
                            <label for="CEO">نام رابط / نام مدیرعامل</label>
                            @error('CEO')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating form-floating-outline mb-3">
                            <input type="text"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone"
                                   placeholder="شماره همراه"
                                   value="{{ old('phone') }}" required>
                            <label for="phone">شماره همراه</label>
                            @error('phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating form-floating-outline mb-3">
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email"
                                   placeholder="آدرس ایمیل"
                                   value="{{ old('email') }}" required>
                            <label for="email">آدرس ایمیل</label>
                            @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               id="password" name="password"
                                               placeholder="رمز عبور" required>
                                        <label for="password">رمز عبور</label>
                                        @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password"
                                               class="form-control @error('password_confirmation') is-invalid @enderror"
                                               id="password_confirmation" name="password_confirmation"
                                               placeholder="تأیید رمز عبور" required>
                                        <label for="password_confirmation">تکرار رمز عبور</label>
                                        @error('password_confirmation')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 d-flex justify-content-between">
                            <div class="form-check">
                                <input class="form-check-input @error('terms_accepted') is-invalid @enderror"
                                       type="checkbox" id="terms-accepted" name="terms_accepted"
                                       {{ old('terms_accepted') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="terms-accepted">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">شرایط و قوانین</a> را با دقت مطالعه نموده‌ام.
                                </label>
                                @error('terms_accepted')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
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
        toastr.success(@json(session('success')));
        @endif
        @if (session('error'))
        toastr.error(@json(session('error')));
        @endif
        @if ($errors->any())
        @foreach ($errors->all() as $error)
        toastr.error(@json($error));
        @endforeach
        @endif
    </script>
@endsection
