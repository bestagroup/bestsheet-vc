<div class="tab-pane fade show active justify-content-center" id="navs-user-card" role="tabpanel">
    <div class="mb-12 col-md-12">
        <div class="card-body">
            <div class="user-avatar-section">
                <div class="d-flex align-items-center flex-column">
                    @if(Auth::user()->gender == 1)
                        <img src="{{ asset('assets/img/avatars/1.png') }}" class="img-fluid rounded mb-3 mt-4" height="120" width="120" alt="User avatar"/>
                    @elseif(Auth::user()->gender == 2)
                        <img src="{{ asset('assets/img/avatars/8.png') }}" class="img-fluid rounded mb-3 mt-4" height="120" width="120" alt="User avatar"/>
                    @else
                        <img src="{{ asset('assets/img/avatars/1.png') }}" class="img-fluid rounded mb-3 mt-4" height="120" width="120" alt="User avatar"/>
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
            <div class="info-container" style="text-align: right; max-width: 30%; margin: 0 auto;">
                <ul class="list-unstyled mb-4">
                    <li class="mb-3"><span class="fw-semibold me-2">نام کاربری:</span> {{ Auth::user()->username }}</li>
                    <li class="mb-3"><span class="fw-semibold me-2">ایمیل:</span> {{ Auth::user()->email }}</li>
                    <li class="mb-3"><span class="fw-semibold me-2">وضعیت:</span> <span class="badge bg-label-success">فعال</span></li>
                    <li class="mb-3"><span class="fw-semibold me-2">نقش:</span>
                        @if(Auth::user()->level == 'admin') مدیر سیستم
                        @elseif(Auth::user()->level == 'investor') مدیر سیستم
                        @endif
                    </li>
                    <li class="mb-3"><span class="fw-semibold me-2">تماس:</span> {{ Auth::user()->phone }}</li>
                </ul>
                <div class="d-flex justify-content-center">
                    <a href="javascript:" class="btn btn-primary me-3" data-bs-target="#editUser" data-bs-toggle="modal">ویرایش</a>
                    <a href="javascript:" class="btn btn-outline-danger suspend-user">تعلیق</a>
                </div>
            </div>
        </div>
    </div>

    {{-- مودال ویرایش اطلاعات کاربر --}}
    @include('modals.edit_user')
</div>
