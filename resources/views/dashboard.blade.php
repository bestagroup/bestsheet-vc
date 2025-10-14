@extends('layouts.base')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css"/>
    <style>
        /* اسکرول تمیز و با ارتفاع کنترل‌شده */
        .payment-scroll{
            max-height: 400px;
            overflow-y: auto;
            padding: .25rem 0;
            scrollbar-width: thin;
        }
        .payment-scroll::-webkit-scrollbar { width: 6px; }
        .payment-scroll::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,.12);
            border-radius: 8px;
        }

        /* آیتم‌ها */
        .payment-item{
            border: 0 !important;
            border-bottom: 1px solid rgba(0,0,0,.06) !important;
            transition: background .2s ease, transform .2s ease;
        }
        .payment-item:last-child{ border-bottom: 0 !important; }
        .payment-item:hover{
            background: #fafafa;
            transform: translateY(-1px);
        }

        /* لوگو */
        .payment-logo{
            width: 40px; height: 40px;
            background: #f3f4f6;
        }

        /* متن‌های طولانی را دو خطی کنید (اختیاری) */
        .text-truncate{
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* گوشه‌های کارت نرم‌تر */
        .card.rounded-4, .rounded-top-4{ border-radius: 1rem !important; }

        :root{
            --bg-card: #ffffff;
            --bg-soft: #f5f7fb;
            --text-muted: #6b7280;
            --ring: rgba(15, 23, 42, .06);

            /* accent palette */
            --primary-100:#eef2ff; --primary-400:#818cf8; --primary-500:#6366f1; --primary-600:#4f46e5;
            --info-100:#e0f2fe;    --info-400:#60a5fa;    --info-500:#3b82f6;    --info-600:#2563eb;
            --warn-100:#fff7ed;    --warn-400:#fb923c;    --warn-500:#f97316;    --warn-600:#ea580c;
        }

        .dark, [data-theme="dark"]{
            --bg-card: #0b1220;
            --bg-soft: #0f172a;
            --text-muted: #9aa3b2;
            --ring: rgba(148, 163, 184, .12);
        }

        .portfolio-card{
            background: var(--bg-card);
            transition: box-shadow .25s ease, transform .25s ease;
            box-shadow: 0 6px 18px var(--ring) !important;
        }
        .portfolio-card:hover{ transform: translateY(-2px); }

        .portfolio-scroll{
            max-height: 400px; overflow-y: auto; scrollbar-width: thin;
        }
        .portfolio-scroll::-webkit-scrollbar{ width: 6px; }
        .portfolio-scroll::-webkit-scrollbar-thumb{ background: rgba(0,0,0,.12); border-radius: 8px; }

        .portfolio-item{ background: transparent; transition: background .2s ease, transform .2s ease; }
        .portfolio-item:hover{ background: var(--bg-soft); transform: translateY(-1px); }
        .portfolio-item:last-child{ border-bottom: 0 !important; }

        .tone-dot{
            width:10px; height:10px; border-radius:50%;
            box-shadow: 0 0 0 4px rgba(0,0,0,.04) inset;
        }
        .tone-primary{ background: var(--primary-500); }
        .tone-info{    background: var(--info-500); }
        .tone-warning{ background: var(--warn-500); }

        .pill{
            display:inline-block; padding:.15rem .5rem; border-radius: 999px;
            font-weight: 600; line-height: 1; letter-spacing:.2px;
        }
        .pill-primary{ background: color-mix(in oklab, var(--primary-100) 70%, #fff 30%); color: var(--primary-600); }
        .pill-info{    background: color-mix(in oklab, var(--info-100) 70%, #fff 30%);    color: var(--info-600); }
        .pill-warning{ background: color-mix(in oklab, var(--warn-100) 70%, #fff 30%);    color: var(--warn-600); }

        .progress.sleek{
            height: 8px; border-radius: 14px; background: rgba(0,0,0,.06);
            overflow: hidden;
            box-shadow: inset 0 1px 3px rgba(0,0,0,.04);
        }
        .progress.sleek .progress-bar{ border-radius: 14px; transition: width .45s cubic-bezier(.22,.61,.36,1); }

        .progress .bar-primary{
            background-image: linear-gradient(90deg, var(--primary-400), var(--primary-600));
        }
        .progress .bar-info{
            background-image: linear-gradient(90deg, var(--info-400), var(--info-600));
        }
        .progress .bar-warning{
            background-image: linear-gradient(90deg, var(--warn-400), var(--warn-600));
        }

        /* گردی‌ها */
        .rounded-4, .rounded-top-4{ border-radius: 16px !important; }

    </style>
@endsection
@section('content')

    <div class="row gy-4 mb-4">
    <div class="alert alert-info"> {{Auth::user()->name}} خوش آمدید به داشبورد مدیریت 👋</div>

    </div>

    <!-- Radar Chart -->
{{--    <div class="col-md-12 col-12">--}}
{{--        <div class="row justify-content-between">--}}
{{--            <div class="card col-md-6 m-1">--}}
{{--                <div class="card-header d-flex justify-content-between align-items-center">--}}
{{--                    <h5 class="card-title mb-0">شاخص کلیدی عملکرد</h5>--}}
{{--                    <div class="dropdown primary-font">--}}
{{--                        <button class="btn px-0" type="button" id="heatChartDd1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                            <i class="bx bx-dots-vertical-rounded"></i>--}}
{{--                        </button>--}}
{{--                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="heatChartDd1">--}}
{{--                            <a class="dropdown-item" href="javascript:void(0);">28 روز اخیر</a>--}}
{{--                            <a class="dropdown-item" href="javascript:void(0);">ماه قبل</a>--}}
{{--                            <a class="dropdown-item" href="javascript:void(0);">سال قبل</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    <div id="radarChart"></div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="card col-md-6 m-1">--}}
{{--                <div class="card-header d-flex justify-content-between">--}}
{{--                    <div>--}}
{{--                        <h5 class="card-title mb-1">آخرین به‌روزرسانی‌ها</h5>--}}
{{--                        <small class="text-muted primary-font">شبکه‌های تجاری</small>--}}
{{--                    </div>--}}
{{--                    <div class="dropdown primary-font">--}}
{{--                        <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown" aria-expanded="false">--}}
{{--                            <i class="bx bx-calendar"></i>--}}
{{--                        </button>--}}
{{--                        <ul class="dropdown-menu dropdown-menu-end">--}}
{{--                            <li>--}}
{{--                                <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">امروز</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">دیروز</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">7 روز اخیر</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">30 روز اخیر</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <hr class="dropdown-divider">--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">این ماه</a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">ماه قبل</a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    <div id="lineAreaChart"></div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <!-- /Radar Chart -->

    <!-- Line Area Chart -->

    <!-- /Line Area Chart -->

    <div class="row gy-4 mb-4">

        <div class="row gy-4 mb-4">
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-chart-box mdi-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1" data-bs-toggle="modal" data-bs-target="#usersModal" style="cursor: pointer;">
                            <p class="text-muted">تعداد کاربران</p>
                            <h5 class="mb-2">{{ DB::table('users')->whereLevel('applicant')->count() }}</h5>
                        </div>
                        <div class="modal fade" id="usersModal" tabindex="-1" aria-labelledby="usersModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="usersModalLabel">لیست کاربران</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive rounded-3">
                                            <div style="max-height: 400px; overflow-y: auto;">
                                                <table class="table table-sm table-bordered" style="border-collapse: collapse;">
                                                    <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                                    <tr>
                                                        <th class="py-3">تصویر </th>
                                                        <th class="py-3">نام کاربری </th>
                                                        <th class="py-3">ایمیل</th>
                                                        <th class="py-3">نقش</th>
                                                        <th class="py-3">وضعیت</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($users as $user)
                                                        <tr>
                                                            <td>
                                                                @if($user->gender == 1)
                                                                    <img src="{{ asset('assets/img/avatars/1.png') }}" class="w-px-40 h-auto rounded-circle" />
                                                                @elseif($user->gender == 2)
                                                                    <img src="{{ asset('assets/img/avatars/8.png') }}" class="w-px-40 h-auto rounded-circle" />
                                                                @else
                                                                    <img src="{{ asset('assets/img/avatars/1.png') }}" class="w-px-40 h-auto rounded-circle" />
                                                                @endif
                                                            </td>
                                                            <td>{{ $user->name }}</td>
                                                            <td>{{ $user->email }}</td>
                                                            <td>{{ $user->level == 'admin' ? 'مدیر' : ($user->level == 'applicant' ? 'سرمایه‌پذیر' : 'نامشخص') }}</td>
                                                            <td>فعال</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-chart-box mdi-24px"></i>
                                </div>
                            </div>
                            {{--<div class="d-flex align-items-center">--}}
                            {{--    <p class="mb-0 text-success me-1"></p>--}}
                            {{--    <i class="mdi mdi-chevron-up text-success"></i>--}}
                            {{--</div>--}}
                        </div>
                        <div class="card-info mt-4 pt-1" data-bs-toggle="modal" data-bs-target="#totalprojectsModal" style="cursor: pointer;">
                            <p class="text-muted"> تعداد کل طرح </p>
                            <h5 class="mb-2">{{DB::table('projects')->count()}}</h5>
                            {{--<div class="badge bg-label-secondary rounded-pill">4 ماه پیش</div>--}}
                        </div>
                        <div class="modal fade" id="totalprojectsModal" tabindex="-1" aria-labelledby="totalprojectsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="totalprojectsModalLabel"> تعداد کل طرح</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive rounded-3">
                                            <div style="max-height: 400px; overflow-y: auto;">
                                                <table class="table table-sm table-bordered" style="border-collapse: collapse;">
                                                    <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                                    <tr>
                                                        <th class="py-3">نام طرح </th>
                                                        <th class="py-3">نام مدیرعامل </th>
                                                        <th class="py-3">درصد پیشرفت</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($projectis as $project)
                                                        <tr>
                                                            <td>
                                                               {{$project->title}}
                                                            </td>
                                                            <td>{{ $project->CEO }}</td>
                                                            <td>{{round(($project->invest_step * 100) / 20)}} %</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-chart-box mdi-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1" data-bs-toggle="modal" data-bs-target="#activeprojectsModal" style="cursor: pointer;">
                            <p class="text-muted">تعداد طرح جاری</p>
                            <h5 class="mb-2">{{DB::table('projects')->where('invest_step' , '>=' , 1)->where('invest_step', '<>', 20)->count()}}</h5>
                        </div>
                        <div class="modal fade" id="activeprojectsModal" tabindex="-1" aria-labelledby="activeprojectsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="activeprojectsModalLabel">تعداد طرح جاری</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive rounded-3">
                                            <div style="max-height: 400px; overflow-y: auto;">
                                                <table class="table table-sm table-bordered" style="border-collapse: collapse;">
                                                    <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                                    <tr>
                                                        <th class="py-3">نام طرح </th>
                                                        <th class="py-3">نام مدیرعامل </th>
                                                        <th class="py-3">درصد پیشرفت</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($projectis as $project)
                                                            @if($project->invest_step >= 1 && $project->invest_step <> 20)
                                                                <tr>
                                                                    <td>{{$project->title}}</td>
                                                                    <td>{{ $project->CEO }}</td>
                                                                    <td>{{round(($project->invest_step * 100) / 20)}} %</td>
                                                                </tr>
                                                            @endif
                                                      @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-chart-box mdi-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1" data-bs-toggle="modal" data-bs-target="#endprojectsModal" style="cursor: pointer;">
                            <p class="text-muted">تعداد طرح خاتمه یافته</p>
                            <h5 class="mb-2">{{DB::table('projects')->Where('invest_step' , '>=' , 20)->count()}}</h5>
                        </div>
                        <div class="modal fade" id="endprojectsModal" tabindex="-1" aria-labelledby="endprojectsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="endprojectsModalLabel">تعداد طرح خاتمه یافته</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive rounded-3">
                                            <div style="max-height: 400px; overflow-y: auto;">
                                                <table class="table table-sm table-bordered" style="border-collapse: collapse;">
                                                    <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                                    <tr>
                                                        <th class="py-3">نام طرح </th>
                                                        <th class="py-3">نام مدیرعامل </th>
                                                        <th class="py-3">درصد پیشرفت</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($projectis as $project)
                                                        @if($project->invest_step == 20)
                                                            <tr>
                                                                <td>{{$project->title}}</td>
                                                                <td>{{ $project->CEO }}</td>
                                                                <td>{{round(($project->invest_step * 100) / 20)}} %</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-chart-box mdi-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1" data-bs-toggle="modal" data-bs-target="#rejectprojectsModal" style="cursor: pointer;">
                            <p class="text-muted">تعداد طرح رد شده</p>
                            <h5 class="mb-2">{{DB::table('projects')->where('invest_step' , '==', 0)->count()}}</h5>
                        </div>
                        <div class="modal fade" id="rejectprojectsModal" tabindex="-1" aria-labelledby="rejectprojectsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="rejectprojectsModalLabel">تعداد طرح رد شده</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive rounded-3">
                                            <div style="max-height: 400px; overflow-y: auto;">
                                                <table class="table table-sm table-bordered" style="border-collapse: collapse;">
                                                    <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                                    <tr>
                                                        <th class="py-3">نام طرح </th>
                                                        <th class="py-3">نام مدیرعامل </th>
                                                        <th class="py-3">درصد پیشرفت</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($projectis as $project)
                                                        @if($project->invest_step == 0)
                                                            <tr>
                                                                <td>{{$project->title}}</td>
                                                                <td>{{ $project->CEO }}</td>
                                                                <td>{{round(($project->invest_step * 100) / 20)}} %</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-chart-box mdi-24px"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1">
                            <p class="text-muted">مجموع سرمایه گذاری (ریال)</p>
                            <h5 class="mb-2">{{number_format(DB::table('finances')->sum('amount'))}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gy-4">

            <div class="col-lg-12 col-md-12 col-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-8 col-12">
                            <div class="card-header">
                                <h5 class="mb-1">جدول زمانی پروژه ها</h5>
                                <small class="mb-0 text-body">مجموع 840 وظیفه تکمیل شده</small>
                            </div>
                            <div class="card-body px-2">
                                <div id="projectTimelineChart"></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 border-start">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <h5 class="mb-1">لیست پروژه ها</h5>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="projectTimeline" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="projectTimeline">
                                            <a class="dropdown-item" href="javascript:void(0);">نوسازی</a>
                                            <a class="dropdown-item" href="javascript:void(0);">اشتراک گذاری</a>
                                            <a class="dropdown-item" href="javascript:void(0);">بروزرسانی</a>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-body mb-0">{{DB::table('projects')->where('invest_step' , '>=' , 1)->count()}}  پروژه در حال اجرا </small>
                            </div>
                            <div class="card-body">
                                @foreach($projects->take(7) as $project)
                                <div class="d-flex align-items-center mb-3 pb-1">
                                    <div class="avatar">
                                        <div class="rounded bg-lighter d-flex align-items-center h-px-30">
                                            <img src="{{asset('storage/'.$project->logo)}}" alt="credit-card" width="30">
                                        </div>
                                    </div>
                                    <div class="ms-3 d-flex flex-column">
                                        <h6 class="mb-1 fw-semibold">{{$project->title}}</h6>
                                        <small class="text-muted"> درصد پیشرفت {{round(($project->total_amount / $totalPaid) * 100)}} % </small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-12">
                <div class="card h-100 border-0 shadow-sm rounded-4">
                    <div class="card-header d-flex align-items-center justify-content-between bg-white rounded-top-4">
                        <h6 class="card-title m-0 me-2 fw-bold">تاریخچه پرداخت</h6>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="paymentHistory" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical mdi-24px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="paymentHistory">
                                <a class="dropdown-item" href="javascript:void(0);">۲۸ روز گذشته</a>
                                <a class="dropdown-item" href="javascript:void(0);">ماه گذشته</a>
                                <a class="dropdown-item" href="javascript:void(0);">سال پیش</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body py-0">
                        <div class="payment-scroll">
                            <ul class="list-group list-group-flush">
                                @foreach($finances as $finance)
                                    <li class="list-group-item px-3 py-3 payment-item d-flex align-items-center gap-3">
                                        {{-- Logo --}}
                                        <div class="payment-logo rounded-3 d-flex align-items-center justify-content-center flex-shrink-0">
                                            <img src="{{ asset('storage/'.$finance->logo) }}" alt="logo" width="28" height="28" class="rounded-2">
                                        </div>

                                        {{-- Texts --}}
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-truncate fw-semibold">{{ $finance->title }}</div>
                                                <div class="small text-muted ms-2">{{ $finance->date }}</div>
                                            </div>
                                        </div>

                                        {{-- Amount --}}
                                        <div class="text-end">
                                            <div class="fw-semibold">{{ number_format($finance->amount) }} <span class="text-muted small">تومان</span></div>
                                            <div class="text-muted small">{{ number_format($finance->amount) }}</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-6 col-md-6 col-12">
                <div class="card h-100 border-0 shadow-sm rounded-4 portfolio-card">
                    <!-- Header -->
                    <div class="card-header d-flex align-items-center justify-content-between bg-white rounded-top-4">
                        <div>
                            <h6 class="card-title m-0 me-2 fw-bold">مجموع پورتفوی سرمایه‌گذاری (ریال)</h6>
                            <small class="text-muted">نمای کلی سهم هر پروژه از کل پورتفو</small>
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="portfolioMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical mdi-24px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="portfolioMenu">
                                <a class="dropdown-item" href="javascript:void(0);">۲۸ روز گذشته</a>
                                <a class="dropdown-item" href="javascript:void(0);">ماه گذشته</a>
                                <a class="dropdown-item" href="javascript:void(0);">سال پیش</a>
                            </div>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body py-2">
                        <div class="portfolio-scroll">
                            <ul class="list-group list-group-flush">
                                @foreach($projects as $project)
                                    @php
                                        $share = $totalPaid > 0 ? round(($project->total_amount / $totalPaid) * 100) : 0;
                                        // tone: primary (>=25), info (10-24), warning (<10)
                                        $tone = $share >= 25 ? 'primary' : ($share >= 10 ? 'info' : 'warning');
                                    @endphp

                                    <li class="list-group-item px-3 py-3 border-0 border-bottom d-flex flex-column portfolio-item">
                                        <!-- Top row -->
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div class="d-flex align-items-center gap-2 min-w-0">
                                                <span class="tone-dot tone-{{ $tone }}"></span>
                                                <span class="fw-semibold text-dark text-truncate" title="{{ $project->title }}">{{ $project->title }}</span>
                                            </div>
                                            <div class="text-end">
                                                <span class="fw-semibold">{{ number_format($project->total_amount) }}</span>
                                            </div>
                                        </div>

                                        <!-- Meta -->
                                        <div class="d-flex justify-content-between align-items-center mb-2 small">
                                            <span class="text-muted">سهم از پورتفو</span>
                                            <span class="pill pill-{{ $tone }}">{{ $share }}%</span>
                                        </div>

                                        <!-- Progress (gradient) -->
                                        <div class="progress sleek" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $share }}">
                                            <div class="progress-bar bar-{{ $tone }}" style="width: {{ $share }}%;"></div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-12">
                <div class="card" style="max-height: 509px">
                    <div class="table-responsive rounded-3" style="margin: 0 5px">
                        <div style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-sm table-bordered" style="border-collapse: collapse;">
                                <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th class="py-3">تصویر </th>
                                    <th class="py-3">نام کاربری </th>
                                    <th class="py-3">ایمیل</th>
                                    <th class="py-3">نقش</th>
                                    <th class="py-3">وضعیت</th>
                                    <th class="py-3">اخرین ورود</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            @if($user->gender == 1)
                                                <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                            @elseif($user->gender == 2)
                                                <img src="{{ asset('assets/img/avatars/8.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                            @else
                                                <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                            @endif
                                        </td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{ $user->level == 'admin' ? 'مدیر' : ($user->level == 'applicant' ? 'سرمایه‌پذیر' : 'نامشخص') }}</td>
                                        <td>فعال</td>
                                        <td>@if($user->lastLogin && $user->lastLogin->created_at)
                                                {{ jdate($user->lastLogin->created_at)->format('Y/m/d ساعت H:i') }}
                                            @else
                                                ورود ثبت نشده
                                            @endif
                                        </td>
                                    </tr>
                              @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card h-100">
                    <div class="card-header pb-1">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">سرمایه گذاری سالانه</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="monthlyBudgetDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="monthlyBudgetDropdown">
                                    <a class="dropdown-item" href="javascript:void(0);">نو سازی</a>
                                    <a class="dropdown-item" href="javascript:void(0);">بروزرسانی</a>
                                    <a class="dropdown-item" href="javascript:void(0);">اشتراک گذاری</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="monthlyBudgetChart"></div>
                        <div class="mt-3">
                            <p class="mb-0 text-muted">در سال گذشته شما 4.7 میلیارد تومان سرمایه گذاری موفق داشته اید</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 me-2">جدول زمانبندی جلسات</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="meetingSchedule" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical mdi-24px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="meetingSchedule">
                                <a class="dropdown-item" href="javascript:void(0);">28 روز گذشته</a>
                                <a class="dropdown-item" href="javascript:void(0);">ماه گذشته</a>
                                <a class="dropdown-item" href="javascript:void(0);">سال پیش</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <ul class="p-0 m-0">
                            @foreach($calendars as $calendar)
                                <li class="d-flex mb-4 pb-1">
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0 fw-semibold">{{$calendar->title}}</h6>
                                            <small class="text-muted">
                                                <i class="mdi mdi-calendar-blank-outline mdi-14px"></i>
                                                <span>{{$calendar->start}}</span> --
                                                <span>{{$calendar->location}}</span>
                                            </small>
                                        </div>
                                        <div class="badge bg-label-primary rounded-pill">
                                            @if($calendar->label === 'meeting')
                                                جلسه
                                            @elseif($calendar->label === 'session')
                                                نشست
                                            @elseif($calendar->label === 'event')
                                                رویداد
                                            @elseif($calendar->label === 'person')
                                                شخصی
                                            @elseif($calendar->label === 'other')
                                                سایر
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="card h-100">
                    <div class="card-header pb-1">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">میزان تحقق اهداف</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="organicSessionsDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="organicSessionsDropdown">
                                    <a class="dropdown-item" href="javascript:void(0);">28 روز گذشته</a>
                                    <a class="dropdown-item" href="javascript:void(0);">ماه گذشته</a>
                                    <a class="dropdown-item" href="javascript:void(0);">سال پیش</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="organicSessionsChart"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('assets/js/timeline-chart.js') }}"></script>
    <script src="{{ asset('assets/js/charts-apex.js') }}"></script>
@endpush

