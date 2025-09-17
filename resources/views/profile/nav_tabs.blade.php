<ul class="nav nav-tabs nav-fill" role="tablist">
    <li class="nav-item">
        <button type="button" class="nav-link d-flex flex-column gap-1 active" role="tab"
                data-bs-toggle="tab" data-bs-target="#navs-user-card" aria-controls="navs-user-card"
                aria-selected="true">
            <i class="tf-icons mdi mdi-account-outline mdi-20px me-1"></i>
            اطلاعات کاربر
        </button>
    </li>
    @if(Auth::user()->level == 'applicant')
        <li class="nav-item">
            <button type="button" class="nav-link d-flex flex-column gap-1" role="tab"
                    data-bs-toggle="tab" data-bs-target="#navs-co-profile-card"
                    aria-controls="navs-co-profile-card" aria-selected="false">
                <i class="tf-icons mdi mdi-domain mdi-20px me-1"></i>
                اطلاعات شرکت
            </button>
        </li>
{{--        <li class="nav-item">--}}
{{--            <button type="button" class="nav-link d-flex flex-column gap-1" role="tab"--}}
{{--                    data-bs-toggle="tab" data-bs-target="#navs-project-profile-card"--}}
{{--                    aria-controls="navs-project-profile-card" aria-selected="false">--}}
{{--                <i class="tf-icons mdi mdi-rocket mdi-20px me-1"></i>--}}
{{--                اطلاعات طرح--}}
{{--            </button>--}}
{{--        </li>--}}
        <li class="nav-item">
            <button type="button" class="nav-link d-flex flex-column gap-1" role="tab"
                    data-bs-toggle="tab" data-bs-target="#navs-invest-card"
                    aria-controls="navs-invest-card" aria-selected="false">
                <i class="tf-icons mdi mdi-clipboard-flow mdi-20px me-1"></i>
                فرایند سرمایه‌گذاری
            </button>
        </li>
        <li class="nav-item">
            <button type="button" {{$projects->invest_step > 1 ? '' : 'disabled'}}  class="nav-link d-flex flex-column gap-1" role="tab"
                    data-bs-toggle="tab" data-bs-target="#navs-file-and-doc-card"
                    aria-controls="navs-file-and-doc-card" aria-selected="false">
                <i class="tf-icons mdi mdi-folder-file mdi-20px me-1"></i>
                فایل‌ها و مستندات
            </button>
        </li>
        <li class="nav-item">
            <button type="button" {{$projects->invest_step > 1 ? '' : 'disabled'}} class="nav-link d-flex flex-column gap-1" role="tab"
                    data-bs-toggle="tab" data-bs-target="#navs-minutes-card"
                    aria-controls="navs-minutes-card" aria-selected="false">
                <i class="tf-icons mdi mdi-message-text-outline mdi-20px me-1"></i>
                صورتجلسات
            </button>
        </li>
        <li class="nav-item">
            <button type="button" {{$projects->invest_step > 1 ? '' : 'disabled'}}  class="nav-link d-flex flex-column gap-1" role="tab"
                    data-bs-toggle="tab" data-bs-target="#navs-guarantee-card"
                    aria-controls="navs-guarantee-card" aria-selected="false">
                <i class="tf-icons mdi mdi-comment-text-multiple mdi-20px me-1"></i>
                تعهدات
            </button>
        </li>
    @elseif(Auth::user()->level == 'investor')
        <li class="nav-item">
            <button type="button" class="nav-link d-flex flex-column gap-1" role="tab"
                    data-bs-toggle="tab" data-bs-target="#navs-investor-projects-card"
                    aria-controls="navs-investor-projects-card" aria-selected="false">
                <i class="tf-icons mdi mdi-lightbulb-outline mdi-20px me-1"></i>
                پروژه‌های سرمایه‌گذاری شده
            </button>
        </li>
    @endif
</ul>
