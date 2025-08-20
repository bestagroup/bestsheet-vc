@php
    $hasProfile = true;
@endphp

<div class="tab-pane fade justify-content-center" id="navs-co-profile-card" role="tabpanel">
    {{-- کارت اطلاعات شرکت --}}
    <div id="userProfileCard" class="{{ $hasProfile ? '' : 'd-none' }}">
        <div class="card border-0 shadow-sm mb-4" style="max-width:480px; margin:0 auto; border-radius: 1.25rem;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex justify-content-center align-items-center shadow-sm" style="width:56px; height:56px; background:#f2f3f6;">
                            <i class="mdi mdi-domain" style="font-size:2rem; color:#696cff"></i>
                        </div>
                        <div>
                            <div class="fw-bold mb-1" style="font-size:1.2rem;">{{ $company->company_name }}</div>
                            <div class="small text-secondary" dir="ltr" style="font-size:0.95rem;">{{ $company->website }}</div>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="toggleEdituserMode()" style="font-size:.98rem">
                        <i class="mdi mdi-pencil-outline"></i>
                        <span class="d-none d-md-inline">ویرایش</span>
                    </button>
                </div>

                <dl class="row g-3 pt-3" style="font-size: 0.96rem;">
                    <div class="col-12 d-flex">
                        <dt class="col-5 text-start text-muted">شماره ثبت:</dt>
                        <dd id="company-registration-number" class="col-7 text-dark mb-0">{{ Auth::user()->name }}</dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">شناسه ملی:</dt>
                        <dd id="company-national-id" class="col-7 text-dark mb-0">{{ Auth::user()->email }}</dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">تلفن:</dt>
                        <dd id="company-phone" class="col-7 text-dark mb-0" dir="ltr" style="font-family: monospace">{{ Auth::user()->phone }}</dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">ایمیل:</dt>
                        <dd id="company-email" class="col-7 text-dark mb-0" dir="ltr" style="font-family: monospace">{{Auth::user()->gender == 1 ? 'مرد' : 'زن' }}</dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">آدرس:</dt>
                        <dd id="company-address" class="col-7 text-dark mb-0">{{ $company->address }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    {{-- فرم ویرایش اطلاعات شرکت --}}
    <div id="userEditForm" class="{{ $hasProfile ? 'd-none' : '' }}">
        @include('profile.user_profile_form', ['profile' => Auth::user()->id])
    </div>
</div>
