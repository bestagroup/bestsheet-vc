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
                <div class="d-flex align-items-center flex-column mb-4">
                    <img src="{{ asset('assets/img/avatars/'.$genderAvatar) }}"
                         class="img-fluid rounded mb-3 mt-2 shadow-sm" height="120" width="120" alt="User avatar"/>

                    <div class="text-center">
                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <span class="badge bg-label-danger">{{ $roleName }}</span>
                    </div>
                </div>

                <dl class="row g-3" style="font-size:0.95rem;">
                    <div class="col-12 d-flex">
                        <dt class="col-5 text-start text-muted">نام کاربری:</dt>
                        <dd class="col-7 text-dark mb-0">{{ $user->username }}</dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">ایمیل:</dt>
                        <dd class="col-7 text-dark mb-0">{{ $user->email }}</dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">وضعیت:</dt>
                        <dd class="col-7 text-dark mb-0"><span class="badge bg-label-success">فعال</span></dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">نقش:</dt>
                        <dd class="col-7 text-dark mb-0">
                            @if($user->level == 'admin')
                                مدیر سیستم
                            @elseif($user->level == 'investor')
                                سرمایه‌گذار سیستم
                            @elseif($user->level == 'applicant')
                                سرمایه‌پذیر سیستم
                            @endif
                        </dd>
                    </div>
                    <div class="col-12 d-flex border-top pt-3">
                        <dt class="col-5 text-start text-muted">موبایل:</dt>
                        <dd class="col-7 text-dark mb-0">{{ $user->phone }}</dd>
                    </div>
                </dl>

                <div class="d-flex justify-content-center mt-4">
                    <button type="button" class="btn btn-primary me-3" onclick="toggleEditMode('user')">
                        <i class="mdi mdi-pencil-outline"></i> ویرایش
                    </button>
                    <a href="javascript:" class="btn btn-outline-danger suspend-user">تعلیق</a>
                </div>
            </div>
        </div>
    </div>

    {{-- فرم ویرایش پروفایل --}}
    <div id="userEditForm" class="d-none">
        @include('profile.user_profile_form')
    </div>
</div>
