@php
    $hasProfile = true;
@endphp

<div class="tab-pane fade justify-content-center" id="navs-project-profile-card" role="tabpanel">

    <div id="companyProfileCard" class="{{ $hasProfile ? '' : 'd-none' }}">
        <div class="card border-0 shadow-sm mb-4" style="max-width:480px; margin:0 auto; border-radius: 1.25rem;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex justify-content-center align-items-center shadow-sm" style="width:56px; height:56px; background:#f2f3f6;">
                            <i class="mdi mdi-domain" style="font-size:2rem; color:#696cff"></i>
                        </div>
                        <div>
                            <div class="fw-bold mb-1" style="font-size:1.2rem;">{{ $projects->title }}</div>
                        </div>
                    </div>
                </div>

                <dl class="row g-3 pt-3" style="font-size: 0.96rem;">
                    <div class="col-12 d-flex">
                        <dt class="col-5 text-start text-muted">وضعیت طرح:</dt>
                        <dd id="company-registration-number" class="col-7 text-dark mb-0">{{ $projects->flow_level }}</dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">وضعیت همکاری:</dt>
                        <dd id="company-national-id" class="col-7 text-dark mb-0">{{ $projects->activity_status }}</dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">تاریخ شروع قرارداد:</dt>
                        <dd id="company-phone" class="col-7 text-dark mb-0" dir="ltr" style="font-family: monospace">{{ $projects->start_date }}</dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">مرحله سرمایه گذاری:</dt>
                        <dd id="company-email" class="col-7 text-dark mb-0" dir="ltr" style="font-family: monospace">@foreach($investsteps as $step) {{ $projects->invest_step == $step->id ? $step->title : '' }} @endforeach</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <div id="companyEditForm" class="{{ $hasProfile ? 'd-none' : '' }}">
        @include('profile.company_profile_form', ['profile' => $company->id])
    </div>
</div>
