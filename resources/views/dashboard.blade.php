@extends('layouts.base')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css"/>
@endsection
@section('content')

    <div class="row gy-4 mb-4">
    <div class="alert alert-info"> {{Auth::user()->name}} خوش آمدید به داشبورد مدیریت 👋</div>

    </div>

    <!-- Radar Chart -->
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">شاخص کلیدی عملکرد</h5>
                <div class="dropdown primary-font">
                    <button class="btn px-0" type="button" id="heatChartDd1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="heatChartDd1">
                        <a class="dropdown-item" href="javascript:void(0);">28 روز اخیر</a>
                        <a class="dropdown-item" href="javascript:void(0);">ماه قبل</a>
                        <a class="dropdown-item" href="javascript:void(0);">سال قبل</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="radarChart"></div>
            </div>
        </div>
    </div>
    <!-- /Radar Chart -->

    <!-- Line Area Chart -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <h5 class="card-title mb-1">آخرین به‌روزرسانی‌ها</h5>
                    <small class="text-muted primary-font">شبکه‌های تجاری</small>
                </div>
                <div class="dropdown primary-font">
                    <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-calendar"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">امروز</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">دیروز</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">7 روز اخیر</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">30 روز اخیر</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">این ماه</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">ماه قبل</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div id="lineAreaChart"></div>
            </div>
        </div>
    </div>
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
                        <div class="card-info mt-4 pt-1">
                            <p class="text-muted">تعداد کاربران</p>
                            <h5 class="mb-2">{{DB::table('users')->whereLevel('applicant')->count()}}</h5>
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
                        <div class="card-info mt-4 pt-1">
                            <p class="text-muted"> تعداد کل طرح </p>
                            <h5 class="mb-2">{{DB::table('projects')->count()}}</h5>

                            {{--<div class="badge bg-label-secondary rounded-pill">4 ماه پیش</div>--}}
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
                            <p class="text-muted">تعداد طرح جاری</p>
                            <h5 class="mb-2">{{DB::table('projects')->whereFlow_level('درحال انجام تعهدات')->count()}}</h5>
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
                            <p class="text-muted">تعداد طرح خاتمه یافته</p>
                            <h5 class="mb-2">{{DB::table('projects')->where('flow_level', 'پایان قرارداد')->orWhere('flow_level', 'خروج کامل')->count()}}</h5>
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
                            <p class="text-muted">تعداد طرح رد شده</p>
                            <h5 class="mb-2">{{DB::table('projects')->where('flow_level', 'رد طرح')->count()}}</h5>
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
                                <small class="text-body mb-0">{{DB::table('projects')->whereFlow_level('درحال انجام تعهدات')->count()}}  پروژه در حال اجرا </small>
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
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">تاریخچه پرداخت</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="paymentHistory" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical mdi-24px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="paymentHistory">
                                <a class="dropdown-item" href="javascript:void(0);">28 روز گذشته</a>
                                <a class="dropdown-item" href="javascript:void(0);">ماه گذشته</a>
                                <a class="dropdown-item" href="javascript:void(0);">سال پیش</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive text-nowrap" style="max-height: 400px">
                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th class="text-capitalize text-body fw-medium fs-6">شرکت </th>
                                <th class="text-capitalize text-body fw-medium fs-6">تاریخ</th>
                                <th class="text-end text-capitalize text-body fw-medium fs-6">مبلغ (ریال)</th>
                            </tr>
                            </thead>
                            <tbody class="border-top">

                            @foreach($finances as $finance)
                                <tr>
                                    <td class="d-flex">
                                        <div class="rounded bg-lighter d-flex align-items-center h-px-30">
                                            <img src="{{asset('storage/'.$finance->logo)}}" alt="credit-card" width="30">
                                        </div>
                                        <div class="ms-2">
                                            {{--                                        <h6 class="mb-0 fw-semibold">*4399</h6>--}}
                                            <small class="text-muted">{{$finance->title}}</small>
                                        </div>
                                    </td>
                                    <td class="text-muted small">{{$finance->date}}</td>

                                    <td class="text-end">
                                        <div class="ms-2">
                                            <h6 class="mb-0 fw-semibold">{{number_format($finance->amount)}}</h6>
                                            <small class="text-muted">{{number_format($finance->amount)}}</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-12">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">مجموع پورتفو سرمایه گذاری  ( ریال )</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="mostSales" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical mdi-24px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="mostSales">
                                <a class="dropdown-item" href="javascript:void(0);">28 روز گذشته</a>
                                <a class="dropdown-item" href="javascript:void(0);">ماه گذشته</a>
                                <a class="dropdown-item" href="javascript:void(0);">سال پیش</a>
                            </div>
                        </div>
                    </div>
                    {{--                    <div class="card-body">--}}
                    {{--                        <div class="mt-1">--}}
                    {{--                            <div class="d-flex align-items-center">--}}
                    {{--                                <p> میلیون ریال</p>--}}
                    {{--                                <h6 class="mb-0 me-3 display-3 float-left">--}}
                    {{--                                    {{ number_format(--}}
                    {{--                                        DB::table('finances')--}}
                    {{--                                            ->join('projects', 'finances.project_id', '=', 'projects.id')--}}
                    {{--                                            ->where('projects.flow_level', 'درحال انجام تعهدات')--}}
                    {{--                                            ->sum('finances.amount') / 1000000, 0--}}
                    {{--                                    ) }}--}}
                    {{--                                </h6>--}}

                    {{--                            </div>--}}
                    {{--                            <small class="text-muted mt-1">مجموع سرمایه گذاری ها ( میلیون ریال )</small>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    <div class="table-responsive text-nowrap border-top" style="max-height: 400px">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="text-capitalize text-body fw-medium fs-6">شرکت </th>
                                <th class="text-capitalize text-body fw-medium fs-6">مبلغ ( ریال)</th>
                                <th class="text-end text-capitalize text-body fw-medium fs-6">درصد از کل</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                            @foreach($projects as $project)
                                <tr>
                                    <td class="pe-5"><span class="text-heading">{{$project->title}}</span></td>
                                    <td class="ps-5 d-flex justify-content-end"><span class="text-heading fw-semibold">{{number_format($project->total_amount)}}  </span></td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-end">
                                            <span class="text-heading fw-semibold me-2">{{round(($project->total_amount / $totalPaid) * 100)}}%</span>
                                            <i class="mdi  mdi-20px {{round(($project->total_amount / $totalPaid) * 100) >= 10 ? 'mdi-chevron-up text-success' : 'mdi-chevron-down text-danger'}} "></i>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-12">
                <div class="card" style="max-height: 509px">
                    <div class="table-responsive rounded-3">
                        <table class="datatables-crm table table-sm">
                            <thead class="table-light">
                            <tr>
                                <th class="py-3">تصویر </th>
                                <th class="py-3">نام کاربری </th>
                                <th class="py-3">ایمیل</th>
                                <th class="py-3">نقش</th>
                                <th class="py-3">وضعیت</th>
                                <th class="py-3">اخرین ورود</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
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
                                    <td>مدیر</td>
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

