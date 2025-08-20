
<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="layout-navbar-nav-left d-flex align-items-center">
    <!-- Burger menu (for mobile) -->
    <a class="nav-item nav-link px-0 me-xl-4 d-xl-none" href="javascript:void(0)">
      <i class="mdi mdi-menu mdi-24px"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <ul class="navbar-nav flex-row align-items-center ms-auto">

      <!-- Search -->
{{--      <li class="nav-item me-3">--}}
{{--        <a class="nav-link btn btn-icon rounded-pill" href="#">--}}
{{--          <i class="mdi mdi-magnify mdi-24px"></i>--}}
{{--        </a>--}}
{{--      </li>--}}

      <!-- Notifications -->
      <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3">
        <a class="nav-link btn btn-icon rounded-pill" href="#" data-bs-toggle="dropdown">
          <i class="mdi mdi-bell-outline mdi-24px"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li class="dropdown-menu-header">
            <div class="dropdown-header d-flex align-items-center justify-content-between">
              <h5 class="dropdown-title mb-0">اعلان‌ها</h5>
              <a href="javascript:void(0)" class="small">علامت‌گذاری به عنوان خوانده‌شده</a>
            </div>
          </li>
          <li class="dropdown-menu-footer border-top">
            <a href="javascript:void(0);" class="dropdown-item d-flex justify-content-center text-primary p-2">
              مشاهده همه اعلان‌ها
            </a>
          </li>
        </ul>
      </li>

      <!-- Theme Toggle -->
      <li class="nav-item me-3">
        <a href="{{ route('toggle-theme') }}" class="nav-link btn btn-icon rounded-pill" title="تغییر تم">
          @if(session('theme') === 'theme-default-dark')
            <i class="mdi mdi-weather-sunny mdi-24px"></i>
          @else
            <i class="mdi mdi-weather-night mdi-24px"></i>
          @endif
        </a>
      </li>

      <!-- User Dropdown -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
              @if(Auth::user()->gender == 1)
                  <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
              @elseif(Auth::user()->gender == 2)
                  <img src="{{ asset('assets/img/avatars/8.png') }}" alt class="w-px-40 h-auto rounded-circle" />
              @else
                  <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
              @endif
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="#">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                      @if(Auth::user()->gender == 1)
                          <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                      @elseif(Auth::user()->gender == 2)
                          <img src="{{ asset('assets/img/avatars/8.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                      @else
                          <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                      @endif
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-medium d-block" style="font-size: 0.7rem">{{Auth::user()->name}}</span>
                  <small class="text-muted">مدیر</small>
                </div>
              </div>
            </a>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <a class="dropdown-item" href="{{url('panel/account')}}">
              <i class="mdi mdi-account-outline me-2"></i>
              <span class="align-middle">پروفایل من</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <i class="mdi mdi-cog me-2"></i>
              <span class="align-middle">تنظیمات</span>
            </a>
          </li>
          <li>
              <a href="" class="dropdown-item" onclick="submitLogout(event)">
                  <i class="mdi mdi-logout me-2"></i>
                  <span class="align-middle">خروج</span>
              </a>
          </li>
            <script>
                function submitLogout(event) {
                    event.preventDefault();

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('logout') }}';

                    const csrfToken = '{{ csrf_token() }}';

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;

                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            </script>

        </ul>
      </li>
    </ul>
  </div>
</nav>
