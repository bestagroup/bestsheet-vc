@extends('layouts.base')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css"/>
@endsection
@section('content')

    <div class="row gy-4 mb-4">
    <div class="alert alert-info"> {{Auth::user()->name}} خوش آمدید به داشبورد مدیریت 👋</div>

    </div>
    <div class="row gy-4 mb-4">

        <div class="row gy-4 mb-4">
            <!-- تبریک میگمcard -->
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-8 col-12">
                <div class="card h-100">
                    <div class="card-body text-nowrap">
                        <h4 class="card-title mb-1 d-flex gap-2 flex-wrap">تبریک میگم <strong>!</strong> 🎉</h4>
                        <p class="pb-0"> بیشترین سرمایه گذاری انجام شده در سال</p>
                        <h4 class="text-primary mb-1">42.8k</h4>
                        <p class="mb-2 pb-1">78% درصد هدف 🚀</p>
                        <a href="javascript:;" class="btn btn-sm btn-primary">مشاهده سرمایه گذاری</a>
                    </div>
                    <img src="{{asset('assets/img/illustrations/trophy.png')}}" class="position-absolute bottom-0 end-0 me-3" height="140" alt="view sales">
                </div>
            </div>
            <!--/ تبریک میگمcard -->

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
                            <p class="text-muted"> کل طرح ثبت شده</p>
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
                            <p class="text-muted">طرح های جاری</p>
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
                            <p class="text-muted">طرح خاتمه یافته</p>
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
                            <p class="text-muted">طرح های رد شده</p>
                            <h5 class="mb-2">{{DB::table('projects')->where('flow_level', 'رد طرح')->count()}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gy-4">

            <!-- Project Timeline Chart-->
            <div class="col-lg-12 col-12">
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
            <!--/ Project Timeline Chart-->

            <!-- Weekly Overview Chart -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">بررسی هفتگی</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="weeklyOverviewDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="weeklyOverviewDropdown">
                                    <a class="dropdown-item" href="javascript:void(0);">28 روز گذشته</a>
                                    <a class="dropdown-item" href="javascript:void(0);">ماه گذشته</a>
                                    <a class="dropdown-item" href="javascript:void(0);">سال پیش</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="weeklyOverviewChart"></div>
                        <div class="mt-1">
                            <div class="d-flex align-items-center gap-3">
                                <h3 class="mb-0">62%</h3>
                                <p class="mb-0 text-muted">عملکرد سرمایه گذاری شما نسبت به ماه گذشته 35 درصد 😎 بهتر است</p>
                            </div>
                            <div class="d-grid mt-3">
                                <button class="btn btn-primary" type="button">جزئیات</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Weekly Overview Chart -->

            <!-- Social Network Visits -->
{{--            <div class="col-lg-4 col-md-6 col-12">--}}
{{--                <div class="card h-100">--}}
{{--                    <div class="card-header d-flex align-items-center justify-content-between">--}}
{{--                        <h5 class="card-title m-0 me-2">بازدید از شبکه های اجتماعی</h5>--}}
{{--                        <div class="dropdown">--}}
{{--                            <button class="btn p-0" type="button" id="socialNetworkList" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                                <i class="mdi mdi-dots-vertical mdi-24px"></i>--}}
{{--                            </button>--}}
{{--                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="socialNetworkList">--}}
{{--                                <a class="dropdown-item" href="javascript:void(0);">28 روز گذشته</a>--}}
{{--                                <a class="dropdown-item" href="javascript:void(0);">ماه گذشته</a>--}}
{{--                                <a class="dropdown-item" href="javascript:void(0);">سال پیش</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="mb-3">--}}
{{--                            <div class="d-flex align-items-center mb-1">--}}
{{--                                <h4 class="mb-0">28,468</h4>--}}
{{--                                <span class="text-success ms-2 fw-semibold">--}}
{{--              <i class="mdi mdi-menu-up"></i>--}}
{{--              <small>62%</small>--}}
{{--            </span>--}}
{{--                            </div>--}}
{{--                            <small class="text-muted">بازدید 1 سال اخیر</small>--}}
{{--                        </div>--}}
{{--                        <ul class="p-0 m-0">--}}
{{--                            <li class="d-flex pb-1 mb-3">--}}
{{--                                <div class="flex-shrink-0">--}}
{{--                                    <img src="{{asset('assets/img/icons/brands/facebook-rounded.png')}}" alt="facebook" class="me-3" height="34">--}}
{{--                                </div>--}}
{{--                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">--}}
{{--                                    <div class="me-2">--}}
{{--                                        <h6 class="mb-0">فیس بوک</h6>--}}
{{--                                        <small class="text-muted">رسانه های اجتماعی</small>--}}
{{--                                    </div>--}}
{{--                                    <div class="d-flex align-items-center">--}}
{{--                                        <span class="fw-semibold text-heading">12,348</span>--}}
{{--                                        <div class="ms-3 badge bg-label-success rounded-pill">+12%</div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li class="d-flex pb-1 mb-3">--}}
{{--                                <div class="flex-shrink-0">--}}
{{--                                    <img src="{{asset('assets/img/icons/brands/dribbble-rounded.png')}}" alt="dribbble" class="me-3" height="34">--}}
{{--                                </div>--}}
{{--                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">--}}
{{--                                    <div class="me-2">--}}
{{--                                        <h6 class="mb-0">دریبل</h6>--}}
{{--                                        <small class="text-muted">جامعه</small>--}}
{{--                                    </div>--}}
{{--                                    <div class="d-flex align-items-center">--}}
{{--                                        <span class="fw-semibold text-heading">8,450</span>--}}
{{--                                        <div class="ms-3 badge bg-label-success rounded-pill">+32%</div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li class="d-flex pb-1 mb-3">--}}
{{--                                <div class="flex-shrink-0">--}}
{{--                                    <img src="{{asset('assets/img/icons/brands/twitter-rounded.png')}}" alt="facebook" class="me-3" height="34">--}}
{{--                                </div>--}}
{{--                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">--}}
{{--                                    <div class="me-2">--}}
{{--                                        <h6 class="mb-0">توییتر</h6>--}}
{{--                                        <small class="text-muted">رسانه های اجتماعی</small>--}}
{{--                                    </div>--}}
{{--                                    <div class="d-flex align-items-center">--}}
{{--                                        <span class="fw-semibold text-heading">350</span>--}}
{{--                                        <div class="ms-3 badge bg-label-danger rounded-pill">-18%</div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li class="d-flex pb-1">--}}
{{--                                <div class="flex-shrink-0">--}}
{{--                                    <img src="{{asset('assets/img/icons/brands/instagram-rounded.png')}}" alt="instagram" class="me-3" height="34">--}}
{{--                                </div>--}}
{{--                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">--}}
{{--                                    <div class="me-2">--}}
{{--                                        <h6 class="mb-0">اینستاگرام</h6>--}}
{{--                                        <small class="text-muted">رسانه های اجتماعی</small>--}}
{{--                                    </div>--}}
{{--                                    <div class="d-flex align-items-center">--}}
{{--                                        <span class="fw-semibold text-heading">25,566</span>--}}
{{--                                        <div class="ms-3 badge bg-label-success rounded-pill">+42%</div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            <!--/ Social Network Visits -->

            <!-- ماهانه Budget Chart-->
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
            <!--/ ماهانه Budget Chart-->

            <!-- Meeting Schedule -->
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
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{asset('assets/img/avatars/4.png')}}" alt="avatar" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-semibold">جلسه با شرکت باینو</h6>
                                        <small class="text-muted">
                                            <i class="mdi mdi-calendar-blank-outline mdi-14px"></i>
                                            <span>22 فروردین | 08:20-10:30</span>
                                        </small>
                                    </div>
                                    <div class="badge bg-label-primary rounded-pill">داخلی</div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{asset('assets/img/avatars/5.png')}}" alt="avatar" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-semibold">برگذاری کنفرانس</h6>
                                        <small class="text-muted">
                                            <i class="mdi mdi-calendar-blank-outline mdi-14px"></i>
                                            <span>13 تیر | 08:20-10:30</span>
                                        </small>
                                    </div>
                                    <div class="badge bg-label-warning rounded-pill">خارجی</div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{asset('assets/img/avatars/3.png')}}" alt="avatar" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-semibold">ملاقات با مهدی</h6>
                                        <small class="text-muted">
                                            <i class="mdi mdi-calendar-blank-outline mdi-14px"></i>
                                            <span>6 فروردین | 08:20-10:30</span>
                                        </small>
                                    </div>
                                    <div class="badge bg-label-secondary rounded-pill">دورهمی</div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{asset('assets/img/avatars/8.png')}}" alt="avatar" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-semibold">جلسه در اوپال</h6>
                                        <small class="text-muted">
                                            <i class="mdi mdi-calendar-blank-outline mdi-14px"></i>
                                            <span>30 اردیبهشت | 08:20-10:30</span>
                                        </small>
                                    </div>
                                    <div class="badge bg-label-danger rounded-pill">شام</div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{asset('assets/img/avatars/8.png')}}" alt="avatar" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-semibold">تماس با مهندس</h6>
                                        <small class="text-muted">
                                            <i class="mdi mdi-calendar-blank-outline mdi-14px"></i>
                                            <span>22 شهریور | 08:20-10:30</span>
                                        </small>
                                    </div>
                                    <div class="badge bg-label-success rounded-pill">ورزش</div>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{asset('assets/img/avatars/8.png')}}" alt="avatar" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-semibold">تماس با دکتر</h6>
                                        <small class="text-muted">
                                            <i class="mdi mdi-calendar-blank-outline mdi-14px"></i>
                                            <span>11 فروردین | 08:20-10:30</span>
                                        </small>
                                    </div>
                                    <div class="badge bg-label-primary rounded-pill">تجاری</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Meeting Schedule -->


            <!-- Organic جلسات Chart-->
            <div class="col-lg-4 col-12">
                <div class="card">
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
            <!--/ Organic جلسات Chart-->

            <!-- تاریخچه پرداخت -->
            <div class="col-lg-4 col-md-6 col-12">
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
            <!--/ تاریخچه پرداخت -->

            <div class="col-lg-4 col-12">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">مجموع پورتفو سرمایه گذاری  ( میلیون ریال )</h5>
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
                                <th class="text-capitalize text-body fw-medium fs-6">مبلغ (میلیارد ریال)</th>
                                <th class="text-end text-capitalize text-body fw-medium fs-6">درصد از کل</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                            @foreach($projects as $project)
                            <tr>
                                <td class="pe-5"><span class="text-heading">{{$project->title}}</span></td>
                                <td class="ps-5 d-flex justify-content-end"><span class="text-heading fw-semibold">{{number_format($project->total_amount / 1000000, 0)}}  </span></td>
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
            <!--/ بیشترین سرمایه گذاری در کشورها -->

            <!-- Roles Datatables -->
            <div class="col-lg-12 col-12">
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
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('assets/js/dashboards-crm.js') }}"></script>
@endpush

