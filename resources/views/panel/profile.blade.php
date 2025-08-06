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
                    <ul class="nav nav-tabs nav-fill" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link d-flex flex-column gap-1 active" role="tab"
                                    data-bs-toggle="tab" data-bs-target="#navs-user-card" aria-controls="navs-user-card"
                                    aria-selected="true"><i class="tf-icons mdi mdi-account-outline mdi-20px me-1"></i>اطلاعات
                                کاربر
                            </button>
                        </li>
                        @if(Auth::user()->level == 'investor')
                        <li class="nav-item">
                            <button type="button" class="nav-link d-flex flex-column gap-1" role="tab"
                                    data-bs-toggle="tab" data-bs-target="#navs-co-profile-card"
                                    aria-controls="navs-co-profile-card" aria-selected="false"><i
                                    class="tf-icons mdi mdi-domain mdi-20px me-1"></i> اطلاعات شرکت
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link d-flex flex-column gap-1" role="tab"
                                    data-bs-toggle="tab" data-bs-target="#navs-invest-card"
                                    aria-controls="navs-invest-card" aria-selected="false"><i
                                    class="tf-icons mdi mdi-clipboard-flow mdi-20px me-1"></i>فرایند سرمایه گذاری
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link d-flex flex-column gap-1" role="tab"
                                    data-bs-toggle="tab" data-bs-target="#navs-file-and-doc-card"
                                    aria-controls="navs-file-and-doc-card" aria-selected="false"><i
                                    class="tf-icons mdi mdi-folder-file mdi-20px me-1"></i>فایل ها و مستندات
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link d-flex flex-column gap-1" role="tab"
                                    data-bs-toggle="tab" data-bs-target="#navs-minutes-card"
                                    aria-controls="navs-minutes-card" aria-selected="false"><i
                                    class="tf-icons mdi mdi-message-text-outline mdi-20px me-1"></i> صورتجلسات
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link d-flex flex-column gap-1" role="tab"
                                    data-bs-toggle="tab" data-bs-target="#navs-guarantee-card"
                                    aria-controls="navs-guarantee-card" aria-selected="false"><i
                                    class="tf-icons mdi mdi-comment-text-multiple mdi-20px me-1"></i>تعهدات و تضامین
                            </button>
                        </li>
                        @endif
                    </ul>
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
                                                 class="img-fluid rounded mb-3 mt-4" height="120" width="120"
                                                 alt="User avatar"/>
                                        @elseif(Auth::user()->gender == 2)
                                            <img src="{{ asset('assets/img/avatars/8.png') }}"
                                                 class="img-fluid rounded mb-3 mt-4" height="120" width="120"
                                                 alt="User avatar"/>
                                        @else
                                            <img src="{{ asset('assets/img/avatars/1.png') }}"
                                                 class="img-fluid rounded mb-3 mt-4" height="120" width="120"
                                                 alt="User avatar"/>
                                        @endif
                                        <div class="user-info text-center">
                                            <h4>{{Auth::user()->name}}</h4>
                                            <span class="badge bg-label-danger">
                                                @php
                                                    use Illuminate\Support\Facades\DB;
                                                    $roleName = DB::table('roles')
                                                        ->where('id', Auth::user()->role_id)
                                                        ->value('title_fa');
                                                @endphp
                                                {{ $roleName }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between flex-wrap my-2 py-1">

                                </div>
                                <h5 class="pb-3 border-bottom mb-3">مشخصات فردی</h5>
                                <div class="info-container" style="text-align: right;max-width: 30%;margin: 0 auto;">
                                    <ul class="list-unstyled mb-4">
                                        <li class="mb-3">
                                            <span class="fw-semibold text-heading me-2">نام کاربری:</span>
                                            <span>{{Auth::user()->username}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-semibold text-heading me-2">ایمیل:</span>
                                            <span>{{Auth::user()->email}}</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-semibold text-heading me-2">وضعیت:</span>
                                            <span class="badge bg-label-success">فعال</span>
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-semibold text-heading me-2">نقش:</span>
                                            @if(Auth::user()->level == 'admin')
                                                <span> مدیر سیستم </span>
                                            @elseif(Auth::user()->level == 'investor')
                                                <span> مدیر سیستم </span>
                                            @endif
                                        </li>
                                        <li class="mb-3">
                                            <span class="fw-semibold text-heading me-2">تماس:</span>
                                            <span>{{Auth::user()->phone}}</span>
                                        </li>
                                    </ul>
                                    <div class="d-flex justify-content-center">
                                        <a href="javascript:;" class="btn btn-primary me-3" data-bs-target="#editUser"
                                           data-bs-toggle="modal">ویرایش</a>
                                        <a href="javascript:;" class="btn btn-outline-danger suspend-user">تعلیق</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit User Modal -->
                        <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                                <div class="modal-content p-3 p-md-5">
                                    <div class="modal-body py-3 py-md-0">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        <div class="text-center mb-4">
                                            <h3 class="mb-2">ویرایش اطلاعات کاربر</h3>
                                            <p class="pt-1">
                                                به روز رسانی جزئیات کاربر یک حسابرسی حریم خصوصی دریافت
                                                خواهد کرد.
                                            </p>
                                        </div>
                                        <form id="editUserForm" class="row g-4" onsubmit="return false">
                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" id="modalEditName" name="modalEditName"
                                                           class="form-control" placeholder="{{Auth::user()->name}}"/>
                                                    <label for="modalEditName">نام و نام خانوادگی</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" id="modalEditUserName" name="modalEditUserName"
                                                           class="form-control"
                                                           placeholder="{{Auth::user()->username}}"/>
                                                    <label for="modalEditUserName">نام کاربری</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" id="modalEditNationalCode"
                                                           name="modalEditNationalCode" class="form-control"
                                                           placeholder="{{Auth::user()->national_id}}"/>
                                                    <label for="modalEditNationalCode">کد ملی</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" id="modalEditUserEmail" name="modalEditUserEmail"
                                                           class="form-control" placeholder="{{Auth::user()->email}}"/>
                                                    <label for="modalEditUserEmail">ایمیل</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="text" id="modalEditAge" name="modalEditAge"
                                                           class="form-control" placeholder="20"/>
                                                    <label for="modalEditAge">سن</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <select id="modalEditUserGender" name="modalEditUserGender"
                                                            class="select2 form-select" data-allow-clear="true">
                                                        <option value="">انتخاب</option>
                                                        <option
                                                            {{Auth::user()->gender == '1' ? 'selected' : ''}} value="1">
                                                            مرد
                                                        </option>
                                                        <option
                                                            {{Auth::user()->gender == '2' ? 'selected' : ''}} value="2">
                                                            زن
                                                        </option>
                                                    </select>
                                                    <label for="modalEditUserGender">جنسیت</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="input-group input-group-merge">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" id="modalEditUserPhone"
                                                               name="modalEditUserPhone"
                                                               class="form-control phone-number-mask"
                                                               placeholder="{{Auth::user()->phone}}"/>
                                                        <label for="modalEditUserPhone">شماره موبایل</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="input-group input-group-merge">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" id="modalEditUserTelephone"
                                                               name="modalEditUserTelephone"
                                                               class="form-control phone-number-mask"
                                                               placeholder="021 22206434"/>
                                                        <label for="modalEditUserTelephone">شماره تماس</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12">
                                                <div class="input-group input-group-merge">
                                                    <div class="form-floating form-floating-outline">
                                                        <textarea rows="20" cols="20" class="form-control"
                                                                  name="address"
                                                                  placeholder="آدرس پستی را وارد کنید"></textarea>
                                                        <label for="modalEditUserTelephone">آدرس ثبتی</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 text-center">
                                                <button type="submit" class="btn btn-primary me-sm-3 me-1">ارسال
                                                </button>
                                                <button type="reset" class="btn btn-outline-secondary"
                                                        data-bs-dismiss="modal" aria-label="Close">منصرف
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Edit User Modal -->

                        <div class="tab-pane fade" id="navs-company-card" role="tabpanel">
                            <h4 class="card-title">پروفایل</h4>
                            <p class="card-text">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با
                                استفاده</p>
                            <a href="javascript:void(0)" class="btn btn-secondary">گزینه نمایشی</a>
                        </div>
                        <div class="tab-pane fade" id="navs-invest-flow-card" role="tabpanel">
                            <h4 class="card-title">پیام ها</h4>
                            <p class="card-text">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با
                                استفاده</p>
                            <a href="javascript:void(0)" class="btn btn-secondary">گزینه نمایشی</a>
                        </div>
                    </div>
                    <!-- اطلاعات شرکت -->
                    {{-- profile.blade.php یا هر جای مناسب --}}
                    @php
                        // فرض بر این که اطلاعات شرکت از دیتابیس اومده یا دستی تعریف شده
                        $hasProfile = true;
                        $profile = [
                            'company_name' => 'شرکت دانش‌بنیان پارس',
                            'registration_number' => '123456',
                            'national_id' => '14001234567',
                            'company_phone' => '021-22220000',
                            'company_email' => 'info@parscompany.ir',
                            'company_address' => 'تهران، بلوار کشاورز، پلاک ۱۰۰',
                            'website' => 'parscompany.ir'
                        ];
                    @endphp
                    @if(Auth::user()->level == 'investor')
                    <div class="tab-pane fade justify-content-center" id="navs-co-profile-card" role="tabpanel">
                        {{-- نمایش کارت اطلاعات شرکت --}}
                        <div id="companyProfileCard" class="{{ $hasProfile ? '' : 'd-none' }}">
                            <div class="card border-0 shadow-sm mb-4" style="max-width:480px; margin:0 auto; border-radius: 1.25rem; background:rgba(255,255,255,0.94);">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle d-flex justify-content-center align-items-center shadow-sm" style="width:56px; height:56px; background:#f2f3f6;">
                                                <i class="mdi mdi-domain" style="font-size:2rem; color:#696cff"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold mb-1" style="font-size:1.2rem;">{{ $profile['company_name'] }}</div>
                                                <div class="small text-secondary" dir="ltr" style="font-size:0.95rem;">{{ $profile['website'] }}</div>
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="toggleEditMode()" style="font-size:.98rem">
                                            <i class="mdi mdi-pencil-outline"></i>
                                            <span class="d-none d-md-inline">ویرایش</span>
                                        </button>
                                    </div>

                                    <dl class="row mb-0" style="font-size:1.01rem;">
                                        <dt class="col-5 text-secondary fw-normal text-end pb-1">شماره ثبت:</dt>
                                        <dd class="col-7 mb-2 text-dark">{{ $projects->title }}</dd>

                                        <dt class="col-5 text-secondary fw-normal text-end pb-1">شناسه ملی:</dt>
                                        <dd class="col-7 mb-2 text-dark">{{ $projects->company_id }}</dd>

                                        <dt class="col-5 text-secondary fw-normal text-end pb-1">تلفن:</dt>
                                        <dd class="col-7 mb-2 text-dark" dir="ltr" style="font-family:monospace">{{ $projects->company_tel }}</dd>

                                        <dt class="col-5 text-secondary fw-normal text-end pb-1">ایمیل:</dt>
                                        <dd class="col-7 mb-2 text-dark" dir="ltr" style="font-family:monospace">{{ $projects->company_email }}</dd>

                                        <dt class="col-5 text-secondary fw-normal text-end pb-1">آدرس:</dt>
                                        <dd class="col-7 mb-1 text-dark">{{ $projects->company_address }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        {{-- فرم ویرایش اطلاعات شرکت (در حالت عادی مخفی) --}}
                        <div id="companyEditForm" class="{{ $hasProfile ? 'd-none' : '' }}">
                            @include('profile.company_profile_form', ['profile' => $profile])
                        </div>
                    </div>

                    <!-- فرایند سرمایه گذاری -->
                    <div class="tab-pane fade justify-content-center" id="navs-invest-card" role="tabpanel">
                        @php
                            $steps = [
                                [
                                    'title'   => 'بررسی اولیه',
                                    'desc'    => 'مدارک اولیه طرح را بارگذاری نمایید. اطلاعات اولیه باید کامل باشد.',
                                    'content' => '
                                        <form>
                                            <div class="mb-3">
                                                <label class="form-label">آپلود فایل پیچ دک</label>
                                                <input type="file" class="form-control">
                                            </div>
                                            <button class="btn btn-primary">ارسال</button>
                                        </form>
                                    '
                                ],
                                [
                                    'title'   => 'غربالگری',
                                    'desc'    => 'اطلاعات تکمیلی طرح و بررسی اولیه توسط کارشناسان انجام می‌شود.',
                                    'content' => '
                                        <form>
                                            <div class="mb-3">
                                                <label class="form-label">حوزه فعالیت</label>
                                                <input type="text" class="form-control" placeholder="مثال: فناوری اطلاعات">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">سابقه فعالیت</label>
                                                <input type="text" class="form-control" placeholder="مدت و توضیح سابقه">
                                            </div>
                                            <button class="btn btn-primary">ذخیره اطلاعات</button>
                                        </form>
                                    '
                                ],
                                [
                                    'title'   => 'ارزیابی اولیه',
                                    'desc'    => 'در این مرحله تیم ارزیاب، طرح را از نظر امکان‌پذیری و نوآوری بررسی می‌کند.',
                                    'content' => '
                                        <form>
                                            <div class="mb-3">
                                                <label class="form-label">امتیاز نوآوری (۰ تا ۱۰)</label>
                                                <input type="number" class="form-control" min="0" max="10">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">امتیاز تیم (۰ تا ۱۰)</label>
                                                <input type="number" class="form-control" min="0" max="10">
                                            </div>
                                            <button class="btn btn-primary">ثبت امتیاز</button>
                                        </form>
                                    '
                                ],
                                [
                                    'title'   => 'ارزیابی موشکافانه',
                                    'desc'    => 'مدارک مالی و فنی به صورت دقیق بررسی و بارگذاری می‌شود.',
                                    'content' => '
                                        <form>
                                            <div class="mb-3">
                                                <label class="form-label">آپلود صورت‌های مالی</label>
                                                <input type="file" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">آپلود گزارش فنی یا محصول</label>
                                                <input type="file" class="form-control">
                                            </div>
                                            <button class="btn btn-primary">ارسال مدارک</button>
                                        </form>
                                    '
                                ],
                                [
                                    'title'   => 'تائیدیه مدیرعامل و هیئت مدیره سینا وی سی',
                                    'desc'    => 'منتظر تائید مدیرعامل سینا وی‌سی جهت ادامه فرایند.',
                                    'content' => '
                                        <div class="alert alert-info">در حال بررسی توسط مدیرعامل سینا وی‌سی...</div>
                                    '
                                ],
                                [
                                    'title'   => 'تائیدیه مدیرعامل و هیئت مدیره دانشمند',
                                    'desc'    => 'تائیدیه مدیرعامل دانشمند برای عبور از این مرحله ضروری است.',
                                    'content' => '
                                        <div class="alert alert-info">در حال بررسی توسط مدیرعامل دانشمند...</div>
                                    '
                                ],
                                [
                                    'title'   => 'تصویب هیئت مدیره سینا وی‌سی',
                                    'desc'    => 'طرح در جلسه هیئت مدیره سینا وی‌سی مطرح و تصویب می‌شود.',
                                    'content' => '
                                        <div class="alert alert-info">منتظر برگزاری جلسه هیئت مدیره...</div>
                                    '
                                ],
                                [
                                    'title'   => 'ارزش‌گذاری',
                                    'desc'    => 'در این مرحله، ارزش‌گذاری شرکت و میزان سرمایه پیشنهادی مشخص می‌شود.',
                                    'content' => '
                                        <form>
                                            <div class="mb-3">
                                                <label class="form-label">مبلغ سرمایه پیشنهادی (تومان)</label>
                                                <input type="number" class="form-control" placeholder="مبلغ به تومان">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">درصد سهام پیشنهادی</label>
                                                <input type="number" class="form-control" placeholder="درصد">
                                            </div>
                                            <button class="btn btn-primary">ثبت ارزش‌گذاری</button>
                                        </form>
                                    '
                                ],
                                [
                                    'title'   => 'ارائه در کمیته ارزش‌ گذاری',
                                    'desc'    => 'خروجی ارزش‌گذاری به کمیته مربوطه ارائه می‌شود و منتظر تایید است.',
                                    'content' => '
                                        <div class="alert alert-info">ارائه در کمیته ارزش‌گذاری در حال انجام است...</div>
                                    '
                                ],
                                [
                                    'title'   => 'توافق قراردادی',
                                    'desc'    => 'بررسی مفاد قرارداد بین طرفین انجام می‌پذیرد.',
                                    'content' => '
                                        <form>
                                            <div class="mb-3">
                                                <label class="form-label">بارگذاری پیش‌نویس قرارداد</label>
                                                <input type="file" class="form-control">
                                            </div>
                                            <button class="btn btn-primary">بارگذاری</button>
                                        </form>
                                    '
                                ],
                                [
                                    'title'   => 'تصویب قرارداد در هیئت میره سینا وی سی',
                                    'desc'    => 'قرارداد نهایی جهت تصویب به کمیته ارسال می‌شود.',
                                    'content' => '
                                        <div class="alert alert-info">منتظر تایید کمیته قرارداد...</div>
                                    '
                                ],
                                [
                                    'title'   => 'عقد قرارداد',
                                    'desc'    => 'در این مرحله، قرارداد به صورت رسمی امضا و مبادله می‌شود.',
                                    'content' => '
                                        <div class="alert alert-success">قرارداد با موفقیت منعقد شد.</div>
                                    '
                                ],
                                [
                                    'title'   => 'پایان دوره ارزش‌ آفرینی',
                                    'desc'    => 'دوره ارزش‌آفرینی پایان یافته و گزارش عملکرد باید ارسال شود.',
                                    'content' => '
                                        <form>
                                            <div class="mb-3">
                                                <label class="form-label">آپلود گزارش نهایی عملکرد</label>
                                                <input type="file" class="form-control">
                                            </div>
                                            <button class="btn btn-primary">ارسال گزارش</button>
                                        </form>
                                    '
                                ],
                                [
                                    'title'   => 'خروج از طرح',
                                    'desc'    => 'فرآیند سرمایه‌گذاری به پایان رسیده است.',
                                    'content' => '
                                        <div class="alert alert-success">شما از طرح با موفقیت خارج شده‌اید. تبریک!</div>
                                    '
                                ],
                            ];
                        @endphp

                        @php
                            $currentStep = 7;
                        @endphp

                        <div class="row" style="direction:rtl;">
                            <div class="col-md-4">
                                <ul class="list-group list-group-flush p-0" style="border-radius:1.1rem; background:#fff;">
                                    @foreach($steps as $idx => $step)
                                        <li class="list-group-item py-3 d-flex align-items-center gap-2" style="background:transparent; border:none;">
                                            <span class="rounded-circle d-inline-flex justify-content-center align-items-center"
                                                  style="width:32px;height:32px;
                                                        background:{{ $idx < $currentStep ? '#e8f5e9' : ($idx === $currentStep ? '#e3e0fa' : '#f3f3f7') }};
                                                        color:{{ $idx < $currentStep ? '#4caf50' : ($idx === $currentStep ? '#7367f0' : '#bbb') }};
                                                        font-weight:bold;">
                                                {{ $idx + 1 }}
                                            </span>
                                            <span class="{{ $idx === $currentStep ? 'fw-bold text-dark' : 'text-muted' }}">
                                                {{ $step['title'] }}
                                            </span>
                                            @if($idx === $currentStep)
                                                <span class="badge bg-primary ms-auto">در جریان</span>
                                            @elseif($idx < $currentStep)
                                                <i class="mdi mdi-check-circle-outline text-success ms-auto"></i>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <!-- جزئیات مرحله فعال -->
                            <div class="col-md-8">
                                <div class="card shadow-sm" style="min-height:260px;">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3">{{ $steps[$currentStep]['title'] }}</h6>
                                        <div class="text-muted mb-3">{{ $steps[$currentStep]['desc'] }}</div>
                                        {!! $steps[$currentStep]['content'] !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- فایل ها و مستندات -->
                    <div class="tab-pane fade justify-content-center" id="navs-file-and-doc-card" role="tabpanel">

                        @php
                            $files = [
                                [
                                    'name' => 'صورت‌های مالی ۱۴۰۲.pdf',
                                    'type' => 'pdf',
                                    'category' => 'مالی',
                                    'size' => '1.3 MB',
                                    'uploaded_at' => '1403/04/10',
                                    'desc' => 'گزارش مالی سال ۱۴۰۲'
                                ],
                                [
                                    'name' => 'قرارداد نهایی.docx',
                                    'type' => 'docx',
                                    'category' => 'قرارداد',
                                    'size' => '420 KB',
                                    'uploaded_at' => '1403/03/29',
                                    'desc' => 'متن قرارداد مصوب'
                                ],
                                [
                                    'name' => 'لوگوی شرکت.png',
                                    'type' => 'png',
                                    'category' => 'سایر',
                                    'size' => '110 KB',
                                    'uploaded_at' => '1403/01/10',
                                    'desc' => 'لوگوی رسمی شرکت'
                                ],
                            ];
                        @endphp


                        <div class="card mb-4 shadow-sm" style="max-width:520px;margin:0 auto;">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">بارگذاری فایل جدید</h6>
                                <form>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <input type="file" class="form-control" id="fileInput">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="توضیح (اختیاری)">
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-select" id="categorySelect">
                                                <option value="">دسته‌بندی</option>
                                                <option>مالی</option>
                                                <option>قرارداد</option>
                                                <option>سایر</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 d-flex align-items-end">
                                            <button class="btn btn-primary w-100">آپلود فایل</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">لیست فایل‌ها و مستندات</h6>
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead class="table-light">
                                        <tr>
                                            <th style="width:40px"></th>
                                            <th>نام فایل</th>
                                            <th>دسته</th>
                                            <th>توضیح</th>
                                            <th>حجم</th>
                                            <th>تاریخ بارگذاری</th>
                                            <th class="text-center" style="width:120px">عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($files as $file)
                                            <tr>
                                                <td>
                                                    @if($file['type'] == 'pdf')
                                                        <i class="mdi mdi-file-pdf-box text-danger fs-4"></i>
                                                    @elseif($file['type'] == 'docx')
                                                        <i class="mdi mdi-file-word-box text-primary fs-4"></i>
                                                    @elseif($file['type'] == 'png' || $file['type'] == 'jpg')
                                                        <i class="mdi mdi-file-image-box text-warning fs-4"></i>
                                                    @else
                                                        <i class="mdi mdi-file-document-outline text-secondary fs-4"></i>
                                                    @endif
                                                </td>
                                                <td>{{ $file['name'] }}</td>
                                                <td>{{ $file['category'] }}</td>
                                                <td>{{ $file['desc'] }}</td>
                                                <td>{{ $file['size'] }}</td>
                                                <td>{{ $file['uploaded_at'] }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-sm btn-outline-primary" title="دانلود"><i
                                                            class="mdi mdi-download"></i></a>
                                                    <a href="#" class="btn btn-sm btn-outline-danger" title="حذف"><i
                                                            class="mdi mdi-trash-can-outline"></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">هیچ فایلی ثبت نشده
                                                    است.
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- صورتجلسات -->
                    <div class="tab-pane fade justify-content-center" id="navs-minutes-card" role="tabpanel">
                        @php
                            $sessions = [
                                [
                                    'title'   => 'جلسه هیئت مدیره، مرداد ۱۴۰۳',
                                    'date'    => '۱۴۰۳/۰۵/۱۲',
                                    'type'    => 'هیئت مدیره',
                                    'members' => ['دکتر احمدی', 'مهندس رضایی', 'خانم مقدم'],
                                    'desc'    => 'تخصیص بودجه و تصویب ارزش‌گذاری مرحله دوم.',
                                    'file'    => 'board-14030512.pdf'
                                ],
                                [
                                    'title'   => 'جلسه کمیته ارزش‌گذاری',
                                    'date'    => '۱۴۰۳/۰۴/۰۸',
                                    'type'    => 'کمیته ارزش‌گذاری',
                                    'members' => ['دکتر احمدی', 'دکتر اکبری'],
                                    'desc'    => 'بررسی مدارک و تایید سرمایه پیشنهادی.',
                                    'file'    => 'committee-14030408.pdf'
                                ],
                                // ... صورتجلسات دیگر
                            ];
                        @endphp
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">صورتجلسات</h6>
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead class="table-light">
                                        <tr>
                                            <th>عنوان جلسه</th>
                                            <th>تاریخ</th>
                                            <th>نوع</th>
                                            <th>اعضای حاضر</th>
                                            <th>شرح تصمیم</th>
                                            <th class="text-center" style="width:100px">فایل</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($sessions as $s)
                                            <tr>
                                                <td>{{ $s['title'] }}</td>
                                                <td>{{ $s['date'] }}</td>
                                                <td>{{ $s['type'] }}</td>
                                                <td>
                                                    @foreach($s['members'] as $m)
                                                        <span
                                                            class="badge bg-light text-dark border me-1">{{ $m }}</span>
                                                    @endforeach
                                                </td>
                                                <td style="max-width:180px">{{ $s['desc'] }}</td>
                                                <td class="text-center">
                                                    <a href="{{ asset('files/'.$s['file']) }}"
                                                       class="btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="mdi mdi-download"></i> دریافت
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">هیچ صورتجلسه‌ای ثبت
                                                    نشده است.
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تعهدات و تضامین -->
                    <div class="tab-pane fade justify-content-center" id="navs-guarantee-card" role="tabpanel">

                        @php
                            $commitments = [
                                [
                                    'type'    => 'سفته',
                                    'title'   => 'سفته شماره 123456',
                                    'date'    => '۱۴۰۳/۰۵/۰۱',
                                    'expire'  => '۱۴۰۴/۰۵/۰۱',
                                    'amount'  => '2,000,000,000',
                                    'by'      => 'شرکت آلفا',
                                    'status'  => 'دریافت‌شده',
                                    'file'    => 'safte-123456.jpg',
                                    'desc'    => 'سفته تضمین حسن انجام تعهدات',
                                ],
                                [
                                    'type'    => 'چک',
                                    'title'   => 'چک شماره 874512',
                                    'date'    => '۱۴۰۳/۰۶/۲۰',
                                    'expire'  => '۱۴۰۳/۱۰/۲۰',
                                    'amount'  => '500,000,000',
                                    'by'      => 'آقای ملکی',
                                    'status'  => 'در جریان',
                                    'file'    => null,
                                    'desc'    => 'چک ضمانت بازگشت سرمایه',
                                ],
                                [
                                    'type'    => 'وثیقه ملکی',
                                    'title'   => 'ملک واقع در خیابان ولیعصر',
                                    'date'    => '۱۴۰۳/۰۷/۰۵',
                                    'expire'  => null,
                                    'amount'  => 'بدون مبلغ',
                                    'by'      => 'شرکت بتا',
                                    'status'  => 'دریافت‌شده',
                                    'file'    => 'melk-vli.jpg',
                                    'desc'    => 'سند رسمی وثیقه',
                                ],
                            ];
                        @endphp

                        <div class="d-flex justify-content-end mb-3">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCommitmentModal">
                                <i class="mdi mdi-plus"></i> افزودن تعهد/تضمین جدید
                            </button>
                        </div>

                        <div class="modal fade" id="addCommitmentModal" tabindex="-1"
                             aria-labelledby="addCommitmentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form>
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold" id="addCommitmentModalLabel">افزودن تعهد یا
                                                تضمین جدید</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="بستن"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <select class="form-select" required>
                                                        <option value="">نوع</option>
                                                        <option>چک</option>
                                                        <option>سفته</option>
                                                        <option>وثیقه ملکی</option>
                                                        <option>تعهد اجرایی</option>
                                                        <option>سایر</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control"
                                                           placeholder="عنوان یا شماره">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="ارائه‌دهنده">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="مبلغ (تومان)">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="تاریخ صدور">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" placeholder="تاریخ سررسید">
                                                </div>
                                                <div class="col-12">
                                                    <input type="file" class="form-control" placeholder="بارگذاری سند">
                                                </div>
                                                <div class="col-12">
                                                    <textarea class="form-control" rows="2"
                                                              placeholder="توضیحات تکمیلی"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">انصراف
                                            </button>
                                            <button type="submit" class="btn btn-primary">ثبت تعهد</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">لیست تعهدات و تضامین</h6>
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead class="table-light">
                                        <tr>
                                            <th>نوع</th>
                                            <th>عنوان/شماره</th>
                                            <th>ارائه‌دهنده</th>
                                            <th>مبلغ</th>
                                            <th>تاریخ صدور</th>
                                            <th>تاریخ سررسید</th>
                                            <th>وضعیت</th>
                                            <th class="text-center" style="width:90px">فایل</th>
                                            <th>توضیحات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($commitments as $item)
                                            <tr>
                                                <td>{{ $item['type'] }}</td>
                                                <td>{{ $item['title'] }}</td>
                                                <td>{{ $item['by'] }}</td>
                                                <td>{{ $item['amount'] }}</td>
                                                <td>{{ $item['date'] }}</td>
                                                <td>{{ $item['expire'] ?: '-' }}</td>
                                                <td>
                            <span
                                class="badge {{ $item['status'] == 'دریافت‌شده' ? 'bg-success' : ($item['status'] == 'در جریان' ? 'bg-warning' : 'bg-secondary') }}">
                                {{ $item['status'] }}
                            </span>
                                                </td>
                                                <td class="text-center">
                                                    @if($item['file'])
                                                        <a href="{{ asset('files/'.$item['file']) }}" target="_blank"
                                                           class="btn btn-sm btn-outline-primary"><i
                                                                class="mdi mdi-eye"></i></a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td style="max-width:120px">{{ $item['desc'] }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-muted py-4">موردی ثبت نشده
                                                    است.
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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
    @endpush

