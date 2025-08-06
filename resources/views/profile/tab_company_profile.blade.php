@php
    // پروفایل تستی شرکت
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

    $projects = (object)[
        'title'           => 'ثبت ۱۲۳۴۵۶',
        'company_id'      => '14001234567',
        'company_tel'     => '021-22223333',
        'company_email'   => 'info@rahno.vc',
        'company_address' => 'تهران، بلوار کشاورز، پلاک ۲۲',
    ];
@endphp

<div class="tab-pane fade justify-content-center" id="navs-co-profile-card" role="tabpanel">
    {{-- کارت اطلاعات شرکت --}}
    <div id="companyProfileCard" class="{{ $hasProfile ? '' : 'd-none' }}">
        <div class="card border-0 shadow-sm mb-4" style="max-width:480px; margin:0 auto; border-radius: 1.25rem;">
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

                <dl class="row g-3 pt-3" style="font-size: 0.96rem;">
                    <div class="col-12 d-flex">
                        <dt class="col-5 text-start text-muted">شماره ثبت:</dt>
                        <dd class="col-7 text-dark mb-0">{{ $projects->title }}</dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">شناسه ملی:</dt>
                        <dd class="col-7 text-dark mb-0">{{ $projects->company_id }}</dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">تلفن:</dt>
                        <dd class="col-7 text-dark mb-0" dir="ltr" style="font-family: monospace">{{ $projects->company_tel }}</dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">ایمیل:</dt>
                        <dd class="col-7 text-dark mb-0" dir="ltr" style="font-family: monospace">{{ $projects->company_email }}</dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">آدرس:</dt>
                        <dd class="col-7 text-dark mb-0">{{ $projects->company_address }}</dd>
                    </div>
                </dl>

            </div>
        </div>
    </div>

    {{-- فرم ویرایش اطلاعات شرکت --}}
    <div id="companyEditForm" class="{{ $hasProfile ? 'd-none' : '' }}">
        @include('profile.company_profile_form', ['profile' => $profile])
    </div>
</div>
