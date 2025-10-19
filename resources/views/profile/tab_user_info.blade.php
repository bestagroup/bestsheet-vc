@php
    $user = Auth::user();
    use Illuminate\Support\Facades\DB;
    $roleName = DB::table('roles')->where('id', $user->role_id)->value('title_fa');
    $genderAvatar = $user->gender == 2 ? '8.png' : '1.png';
@endphp

    <div class="tab-pane fade show active justify-content-center" id="navs-user-card" role="tabpanel">

        <div class="modal fade" id="adduserModal" tabindex="-1" aria-labelledby="adduserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">اطلاعات مدیرعامل</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                    </div>
                    <div class="card-body">
                        <div class="modal-body">
                            <form data-type="update" data-id="{{ Auth::user()->id }}"  class="row g-4 mb-4" method="POST" action="{{ route('fullregister.update', Auth::user()->id) }}">
                                @csrf
                                @method('PATCH')
                                <div class="col-12 col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input required type="text" id="user_name" name="name" class="form-control"
                                               placeholder="نام و نام خانوادگی" value="{{ old('name', Auth::user()->name ?? '') }}">
                                        <label for="user_name">نام و نام خانوادگی</label>
                                        <div class="invalid-feedback">لطفاً نام و نام خانوادگی را حداقل ۳ حرف وارد کنید.</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input required inputmode="numeric" pattern="^\d{10}$" maxlength="10" minlength="10"
                                               type="text" id="user_national_id" name="national_id" class="form-control"
                                               placeholder="کد ملی" value="{{ old('national_id', Auth::user()->national_id ?? '') }}">
                                        <label for="user_national_id">کد ملی</label>
                                        <div class="invalid-feedback" id="nationalIdFeedback">کد ملی نامعتبر است. باید ۱۰ رقم و معتبر باشد.</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="user_father_name" name="father_name" class="form-control"
                                               placeholder="نام پدر" value="{{ old('father_name', Auth::user()->father_name ?? '') }}">
                                        <label for="user_father_name">نام پدر</label>
                                        <div class="invalid-feedback">در صورت وارد کردن، حداقل ۳ حرف لازم است.</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input required type="email" id="user_email" name="email" class="form-control"
                                               placeholder="ایمیل" value="{{ old('email', Auth::user()->email ?? '') }}">
                                        <label for="user_email">ایمیل</label>
                                        <div class="invalid-feedback">لطفاً یک ایمیل معتبر وارد کنید.</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input required type="tel" id="user_phone" name="phone" class="form-control"
                                               placeholder="شماره موبایل" value="{{ old('phone', Auth::user()->phone ?? '') }}"
                                               pattern="^(?:\+98|0)?9\d{9}$" inputmode="tel" maxlength="11">
                                        <label for="user_phone">شماره موبایل</label>
                                        <div class="invalid-feedback">شماره موبایل معتبر نیست. مثال: 0912xxxxxxx </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <select id="gender" name="gender" class="form-select">
                                            <option value="">انتخاب</option>
                                            <option value="1" {{ (old('gender', Auth::user()->gender) == 1) ? 'selected' : '' }}>مرد</option>
                                            <option value="2" {{ (old('gender', Auth::user()->gender) == 2) ? 'selected' : '' }}>زن</option>
                                        </select>
                                        <label for="gender">جنسیت</label>
                                        <div class="invalid-feedback">جنسیت نامعتبر است.</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="user_postalcode" name="postalcode" class="form-control"
                                               placeholder="کد پستی" value="{{ old('postalcode', Auth::user()->postalcode ?? '') }}"
                                               inputmode="numeric" pattern="^\d{10}$" maxlength="10">
                                        <label for="user_postalcode">کد پستی</label>
                                        <div class="invalid-feedback">کد پستی باید ۱۰ رقم باشد.</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <textarea id="user_address" name="address" class="form-control" rows="3" placeholder="آدرس">{{ old('address', Auth::user()->address ?? '') }}</textarea>
                                        <label for="user_address">آدرس ثبتی</label>
                                        <div class="invalid-feedback">آدرس بیش از حد طولانی است یا نامعتبر است.</div>
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary" id="editsubmit_{{Auth::user()->id}}">ذخیره</button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="toggleEditMode('user')">انصراف</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm mb-4" style="max-width:480px; margin:0 auto; border-radius:1.25rem;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex justify-content-center align-items-center shadow-sm" style="width:56px; height:56px; background:#f2f3f6;">
                            @if(Auth::user()->gender == 1)
                                <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                            @elseif(Auth::user()->gender == 2)
                                <img src="{{ asset('assets/img/avatars/8.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                            @else
                                <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                            @endif
                        </div>
                        <div>
                            <div class="fw-bold mb-1" style="font-size:1.2rem;">{{  $user->name }}</div>
                            <div class="small text-secondary" dir="ltr" style="font-size:0.95rem;">{{ $roleName }}</div>
                        </div>
                    </div>
    {{--                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="toggleEditMode('user')" style="font-size:.98rem">--}}
    {{--                    <i class="mdi mdi-pencil-outline"></i>--}}
    {{--                    <span class="d-none d-md-inline">ویرایش</span>--}}
    {{--                </button>--}}
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adduserModal">
                        <span class="d-none d-md-inline">ویرایش</span>
                    </button>
                </div>

                <dl class="row g-3" style="font-size:0.95rem;">
                    <div class="col-12 d-flex">
                        <dt class="col-5 text-start text-muted">کد ملی :</dt>
                        <dd class="col-7 text-dark mb-0" id="user_national_id">{{ $user->national_id }}</dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">موبایل:</dt>
                        <dd class="col-7 text-dark mb-0" id="user_phone">{{ $user->phone }}</dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">ایمیل:</dt>
                        <dd class="col-7 text-dark mb-0" id="user_email">{{ $user->email }}</dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">وضعیت:</dt>
                        <dd class="col-7 text-dark mb-0"><span class="badge bg-label-success">فعال</span></dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">نقش:</dt>
                        <dd class="col-7 text-dark mb-0">
                            @if($user->level == 'admin')
                                مدیر
                            @elseif($user->level == 'investor')
                                سرمایه‌گذار
                            @elseif($user->level == 'applicant')
                                سرمایه‌پذیر
                            @endif
                        </dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">آدرس:</dt>
                        <dd class="col-7 text-dark mb-0" id="user_address">{{ $user->address }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

{{--<div class="tab-pane fade show active justify-content-center" id="navs-user-card" role="tabpanel">--}}
{{--    <div id="userProfileCard">--}}
{{--        <div class="card border-0 shadow-sm mb-4" style="max-width:480px; margin:0 auto; border-radius:1.25rem;">--}}
{{--            <div class="card-body p-4">--}}
{{--                <div class="d-flex align-items-center justify-content-between mb-3">--}}
{{--                    <div class="d-flex align-items-center gap-3">--}}
{{--                        <div class="rounded-circle d-flex justify-content-center align-items-center shadow-sm" style="width:56px; height:56px; background:#f2f3f6;">--}}
{{--                            @if(Auth::user()->gender == 1)--}}
{{--                                <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />--}}
{{--                            @elseif(Auth::user()->gender == 2)--}}
{{--                                <img src="{{ asset('assets/img/avatars/8.png') }}" alt class="w-px-40 h-auto rounded-circle" />--}}
{{--                            @else--}}
{{--                                <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                        <div>--}}
{{--                            <div class="fw-bold mb-1" style="font-size:1.2rem;">{{  $user->name }}</div>--}}
{{--                            <div class="small text-secondary" dir="ltr" style="font-size:0.95rem;">{{ $roleName }}</div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="toggleEditMode('user')" style="font-size:.98rem">--}}
{{--                        <i class="mdi mdi-pencil-outline"></i>--}}
{{--                        <span class="d-none d-md-inline">ویرایش</span>--}}
{{--                    </button>--}}
{{--                </div>--}}

{{--                <dl class="row g-3" style="font-size:0.95rem;">--}}
{{--                    <div class="col-12 d-flex">--}}
{{--                        <dt class="col-5 text-start text-muted">کد ملی :</dt>--}}
{{--                        <dd class="col-7 text-dark mb-0" id="user_national_id">{{ $user->national_id }}</dd>--}}
{{--                    </div>--}}
{{--                    <div class="col-12 d-flex border-top pt-3">--}}
{{--                        <dt class="col-5 text-start text-muted">موبایل:</dt>--}}
{{--                        <dd class="col-7 text-dark mb-0" id="user_phone">{{ $user->phone }}</dd>--}}
{{--                    </div>--}}
{{--                    <div class="col-12 d-flex border-top pt-3">--}}
{{--                        <dt class="col-5 text-start text-muted">ایمیل:</dt>--}}
{{--                        <dd class="col-7 text-dark mb-0" id="user_email">{{ $user->email }}</dd>--}}
{{--                    </div>--}}
{{--                    <div class="col-12 d-flex border-top pt-3">--}}
{{--                        <dt class="col-5 text-start text-muted">وضعیت:</dt>--}}
{{--                        <dd class="col-7 text-dark mb-0"><span class="badge bg-label-success">فعال</span></dd>--}}
{{--                    </div>--}}
{{--                    <div class="col-12 d-flex border-top pt-3">--}}
{{--                        <dt class="col-5 text-start text-muted">نقش:</dt>--}}
{{--                        <dd class="col-7 text-dark mb-0">--}}
{{--                            @if($user->level == 'admin')--}}
{{--                                مدیر--}}
{{--                            @elseif($user->level == 'investor')--}}
{{--                                سرمایه‌گذار--}}
{{--                            @elseif($user->level == 'applicant')--}}
{{--                                سرمایه‌پذیر--}}
{{--                            @endif--}}
{{--                        </dd>--}}
{{--                    </div>--}}
{{--                    <div class="col-12 d-flex border-top pt-3">--}}
{{--                        <dt class="col-5 text-start text-muted">آدرس:</dt>--}}
{{--                        <dd class="col-7 text-dark mb-0" id="user_address">{{ $user->address }}</dd>--}}
{{--                    </div>--}}
{{--                </dl>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    --}}{{-- فرم ویرایش پروفایل --}}
{{--    <div id="userEditForm" class="d-none">--}}
{{--        @include('profile.user_profile_form')--}}
{{--    </div>--}}
{{--</div>--}}
