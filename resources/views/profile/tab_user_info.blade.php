@php
    $user = Auth::user();
    use Illuminate\Support\Facades\DB;
    $roleName = DB::table('roles')->where('id', $user->role_id)->value('title_fa');
    $genderAvatar = $user->gender == 2 ? '8.png' : '1.png';
@endphp

<div class="tab-pane fade show active justify-content-center" id="navs-user-card" role="tabpanel">
    {{-- کارت پروفایل کاربر --}}
    <div id="userProfileCard">
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
                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="toggleEditMode('user')" style="font-size:.98rem">
                        <i class="mdi mdi-pencil-outline"></i>
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

    {{-- فرم ویرایش پروفایل --}}
    <div id="userEditForm" class="d-none">
        @include('profile.user_profile_form')
    </div>
</div>
