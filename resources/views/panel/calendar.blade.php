@extends('layouts.base')

@section('title', ' تقویم')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-calendar.css')}}" />
    <link rel="stylesheet" href="{{'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'}}" />
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
                        <div class="inline-calendar"></div>
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
                        <form class="event-form pt-0" id="addform" onsubmit="return false">
                            @csrf
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
                                <input type="text" class="form-control" id="eventStartDate" name="eventStartDate" placeholder="تاریخ شروع">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="eventEndDate">تاریخ پایان</label>
                                <input type="text" class="form-control" id="eventEndDate" name="eventEndDate" placeholder="تاریخ پایان">
                            </div>
                            <div class="mb-3">
                                <label class="switch">
                                    <input type="checkbox" class="switch-input allDay-switch">
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on"></span>
                                        <span class="switch-off"></span>
                                    </span>
                                    <span class="switch-label">هر روز</span>
                                </label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="eventURL">آدرس URL رویداد</label>
                                <input type="url" style="direction: ltr" class="form-control" id="eventURL" name="eventURL" placeholder="https://site.ir">
                            </div>
                            <div class="mb-3 select2-primary">
                                <label class="form-label" for="eventGuests">افزودن مهمانان</label>
                                <select class="select2 select-event-guests form-select" id="eventGuests" name="eventGuests" multiple>
                                    @foreach($users as $user)
                                        <option @if($user->gender == 1) data-avatar="{{ asset('assets/img/avatars/1.png') }}" @elseif($user->gender == 2)  data-avatar="{{ asset('assets/img/avatars/8.png') }}" @else  data-avatar="{{ asset('assets/img/avatars/1.png') }}" @endif value="{{$user->name}}">{{$user->name}}</option>
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
                                    <button type="reset" class="btn btn-label-secondary btn-cancel me-sm-0 me-1" data-bs-dismiss="offcanvas">انصراف</button>
                                </div>
                                <div><button class="btn btn-label-danger btn-delete-event d-none">حذف</button></div>
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

    <script>
        jQuery(function($){
            function showToast(message, type = 'success') {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 3000,
                    rtl: true
                };
                if (toastr[type]) {
                    toastr[type](message);
                } else {
                    toastr.success(message);
                }
            }

            $('.btn-add-event').on('click', function(e){
                e.preventDefault();
                var $btn  = $(this);
                var $form = $('#addform');
                var originalHtml = $btn.html();

                $btn.prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('calendar.store') }}",
                    method: 'POST',
                    data: $form.serialize(),
                    success: function (data) {
                        if (data.success) {
                            // بستن offcanvas + ریست فرم با دکمه انصراف
                            $('.btn-cancel[data-bs-dismiss="offcanvas"]').trigger('click');

                            // رفرش جدول
                            $('.yajra-datatable').DataTable().ajax.reload(null, false);

                            // پیام موفقیت
                            showToast('آیتم با موفقیت افزوده شد!', 'success');
                        } else {
                            swal(data.subject, data.message, data.flag);
                        }
                    },
                    error: function () {
                        swal('خطا', 'مشکلی پیش آمد. لطفاً دوباره تلاش کنید.', 'error');
                    },
                    complete: function () {
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });
        });
    </script>


@endpush

