@extends('layouts.base')

@section('title', ' تقویم')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-calendar.css')}}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .flatpickr-calendar {
            background-color: #fff !important;
            border: 1px solid #ddd;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 2000 !important;
        }
        html[data-theme="dark"] .flatpickr-calendar {
            background-color: #2c2c2c !important;
            border-color: #444;
            color: #eee;
        }

        .fc .fc-view-harness .fc-event {
            color: var(--bs-primary-bg-subtle);
            background-color: var(--bs-primary);
            border-radius: 4px;
        }

        .fc .fc-toolbar .fc-button.fc-prev-button,
        .fc .fc-toolbar .fc-button.fc-next-button {
            color: var(--bs-primary) !important;
            background: transparent !important;
            border: none !important;
        }

        .fc .fc-toolbar .fc-button.fc-prev-button:hover,
        .fc .fc-toolbar .fc-button.fc-next-button:hover {
            color: #fff !important;
            background-color: var(--bs-primary) !important;
        }

        .fc .fc-toolbar-title {
            margin-inline: 1rem;
            padding-right: 8px;
            padding-left: 8px;
            font-size: 1.05rem;
        }
        /* استایل کلی انتخاب مهمانان */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid var(--bs-border-color, #dee2e6);
            border-radius: 0.375rem; /* مثل فرم‌های بوت‌استرپ */
            min-height: 42px;
            padding: 4px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        /* استایل هر تگ (pill) */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: var(--bs-primary);
            border: none;
            color: #fff;
            font-size: 0.85rem;
            font-weight: 500;
            border-radius: 20px;
            padding: 4px 10px;
            margin: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* آیکون × داخل تگ */
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
            margin-right: 4px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: crimson; /* تغییر رنگ موقع hover */
        }

        /* متن انتخاب (placeholder) در حالت multiple */
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            display: flex;
            align-items: center;
            justify-content: flex-start; /* متن سمت راست در RTL */
            padding-right: 6px;          /* کمی فاصله از دیواره راست */
            gap: 6px;                    /* فاصله بین آیتم‌ها وقتی انتخاب می‌شن */
            min-height: 34px;
            font-size: 0.9rem;
            color: #6c757d; /* رنگ placeholder مثل فرم‌های Bootstrap */
        }

        [dir=rtl] .select2-container--default .select2-selection--multiple .select2-selection__choice {
            padding-left: 1.3rem;
        }

        /* نمایش صحیح inline داخل سایدبار */
        .app-calendar-sidebar .inline-calendar .flatpickr-calendar {
            box-shadow: none;
            border: 0;
            width: 100%;
            background: transparent;
        }
        .app-calendar-sidebar .inline-calendar { display:block; }

    </style>
@endsection
@section('content')
    <div class="card app-calendar-wrapper">
        <div class="row g-0">

            <!-- Calendar Sidebar -->
            <div class="col app-calendar-sidebar" id="app-calendar-sidebar">
                <div class="border-bottom p-4 my-sm-0 mb-3">
                    <div class="d-grid">
                        <button class="btn btn-primary btn-toggle-sidebar" data-bs-toggle="offcanvas" data-bs-target="#addEventSidebar" aria-controls="addEventSidebar">
                            <i class="bx bx-plus"></i>
                            <span class="align-middle">افزودن رویداد</span>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <!-- inline calendar -->
                    <div class="ms-n2">
                        <div class="inline-calendar"></div>  <!-- همین کلاس لازم است -->
                    </div>



                    <hr class="container-m-nx my-4">

                    <!-- Filter -->
                    <div class="mb-4">
                        <small class="text-small text-muted text-uppercase align-middle">فیلتر</small>
                    </div>

                    <div class="form-check mb-2 pb-1">
                        <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all" checked>
                        <label class="form-check-label" for="selectAll">مشاهده همه</label>
                    </div>

                    <div class="app-calendar-events-filter">
                        <div class="form-check form-check-danger mb-2 pb-1">
                            <input class="form-check-input input-filter" type="checkbox" id="select-meeting" data-value="meeting" checked>
                            <label class="form-check-label" for="select-meeting">جلسه</label>
                        </div>
                        <div class="form-check mb-2 pb-1">
                            <input class="form-check-input input-filter" type="checkbox" id="select-session" data-value="session" checked>
                            <label class="form-check-label" for="select-session">نشست</label>
                        </div>
                        <div class="form-check form-check-warning mb-2 pb-1">
                            <input class="form-check-input input-filter" type="checkbox" id="select-event" data-value="event" checked>
                            <label class="form-check-label" for="select-event">رویداد</label>
                        </div>
                        <div class="form-check form-check-success mb-2 pb-1">
                            <input class="form-check-input input-filter" type="checkbox" id="select-person" data-value="person" checked>
                            <label class="form-check-label" for="select-person">شخصی</label>
                        </div>
                        <div class="form-check form-check-info">
                            <input class="form-check-input input-filter" type="checkbox" id="select-other" data-value="other" checked>
                            <label class="form-check-label" for="select-other">سایر</label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Calendar Sidebar -->

            <!-- Calendar & Modal -->
            <div class="col app-calendar-content">
                <div class="card shadow-none border-0">
                    <div class="card-body pb-0">
                        <div id="calendar"></div>
                    </div>
                </div>
                <div class="app-overlay"></div>

                <!-- Offcanvas for Add/Edit -->
                <div class="offcanvas offcanvas-end event-sidebar" tabindex="-1" id="addEventSidebar" aria-labelledby="addEventSidebarLabel">
                    <div class="offcanvas-header border-bottom">
                        <h6 class="offcanvas-title" id="addEventSidebarLabel">افزودن رویداد</h6>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <form class="event-form pt-0" id="eventForm" onsubmit="return false">
                            <div class="mb-3">
                                <label class="form-label" for="eventTitle">عنوان</label>
                                <input type="text" class="form-control" id="eventTitle" name="eventTitle" placeholder="عنوان رویداد">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="eventLabel">برچسب</label>
                                <select class="select2 select-event-label form-select" id="eventLabel" name="eventLabel">
                                    <option data-label="primary" value="meeting" selected>جلسه</option>
                                    <option data-label="danger" value="session">نشست</option>
                                    <option data-label="warning" value="event">رویداد</option>
                                    <option data-label="success" value="person">شخصی</option>
                                    <option data-label="info" value="other">سایر</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="eventStartDate">تاریخ شروع</label>
                                <div class="input-group w-100">
                                    <input type="text" class="form-control flatpickr-input" id="eventStartDate" name="eventStartDate" placeholder="انتخاب تاریخ شروع">
                                    <span class="mdi mdi-calendar-month-outline"></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="eventEndDate">تاریخ پایان</label>
                                <div class="input-group w-100">
                                    <input type="text" class="form-control flatpickr-input" id="eventEndDate" name="eventEndDate" placeholder="انتخاب تاریخ پایان">
                                    <span class="mdi mdi-calendar-month-outline"></span>
                                </div>
                            </div>



                            <div class="mb-3">
                                <label class="switch">
                                    <input type="checkbox" class="switch-input allDay-switch">
                                    <span class="switch-toggle-slider">
                        <span class="switch-on"></span>
                        <span class="switch-off"></span>
                    </span>
                                    <span class="switch-label">تمام روز</span>
                                </label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="eventURL">آدرس URL رویداد</label>
                                <input type="url" class="form-control" id="eventURL" name="eventURL" placeholder="https://www.google.com/">
                            </div>

                            <div class="mb-3 select2-primary">
                                <label class="form-label" for="eventGuests">افزودن مهمانان</label>
                                <select class="select2 select-event-guests form-select" id="eventGuests" name="eventGuests[]" multiple>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" data-avatar="{{ $user->gender == 1 ? asset('assets/img/avatars/1.png') : ($user->gender == 2 ? asset('assets/img/avatars/2.png') : asset('assets/img/avatars/1.png')) }}">
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="eventLocation">مکان</label>
                                <input type="text" class="form-control" id="eventLocation" name="eventLocation" placeholder="مکان رویداد">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="eventDescription">شرح</label>
                                <textarea class="form-control" name="eventDescription" id="eventDescription"></textarea>
                            </div>

                            <div class="mb-3 d-flex justify-content-sm-between justify-content-start my-4">
                                <div>
                                    <button type="submit" class="btn btn-primary btn-add-event me-sm-3 me-1">افزودن</button>
                                    <button type="submit" class="btn btn-primary btn-update-event d-none me-sm-3 me-1">به‌روزرسانی</button>
                                    <button type="reset" class="btn btn-label-secondary btn-cancel me-sm-0 me-1" data-bs-dismiss="offcanvas">انصراف</button>
                                </div>
                                <div>
                                    <button class="btn btn-label-danger btn-delete-event d-none">حذف</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('assets/js/app-calendar-events.js') }}"></script>
    <script src="{{ asset('assets/js/app-calendar.js') }}"></script>
@endpush

