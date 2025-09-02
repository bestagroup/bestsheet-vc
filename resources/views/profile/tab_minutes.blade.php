@php
    $sessions = [
        [
            'title'   => 'جلسه هیئت مدیره، مرداد ۱۴۰۳',
            'date'    => '۱۴۰۳/۰۵/۱۲',
            'type'    => 'هیئت مدیره',
            'members' => ['دکتر احمدی', 'مهندس رضایی', 'خانم مقدم'],
            'desc'    => 'تخصیص بودجه و تصویب ارزش‌گذاری مرحله دوم.',
            'file'    => 'board-14030512.pdf'
        ],
        [
            'title'   => 'جلسه کمیته ارزش‌گذاری',
            'date'    => '۱۴۰۳/۰۴/۰۸',
            'type'    => 'کمیته ارزش‌گذاری',
            'members' => ['دکتر احمدی', 'دکتر اکبری'],
            'desc'    => 'بررسی مدارک و تایید سرمایه پیشنهادی.',
            'file'    => 'committee-14030408.pdf'
        ],
    ];
@endphp

<div class="tab-pane fade justify-content-center" id="navs-minutes-card" role="tabpanel">
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMinutesModal">
            <i class="mdi mdi-plus"></i>مدیریت صورتجلسات
        </button>
    </div>
    <div class="modal fade" id="addMinutesModal" tabindex="-1" aria-labelledby="addMinutesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="card-body">
                    <form id="addminuteform" method="POST" class="row g-4 mb-4" action="{{route('minute.store')}}">
                    @csrf
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="title" name="title" placeholder="عنوان">
                            <label for="title">عنوان</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="date" name="date" placeholder="تاریخ برگزاری">
                            <label for="date">تاریخ برگزاری</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select name="type" id="type" class="form-control">
                                <option value="" selected>انتخاب کنید</option>
                                <option value="صورتجلسه هیئت مدیره" >صورتجلسه هیئت مدیره</option>
                            </select>
                            <label for="type">نوع شرکت</label>
                        </div>
                    </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group">
                                <input type="text" name="logo" class="form-control"
                                       id="file_{{ $company->id }}" readonly
                                       placeholder="انتخاب فایل">
                                <button class="btn btn-outline-secondary file-selector" type="button"
                                        data-record-id="{{ $company->id }}"
                                        data-input-id="file_{{ $company->id }}">
                                    انتخاب فایل
                                </button>
                            </div>
                        </div>

                        <div class="col-12 text-center">
                        <button type="button" class="btn btn-primary" id="editsubmit_{{$company->id}}">ذخیره</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold mb-3">صورتجلسات</h6>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>عنوان جلسه</th>
                        <th>تاریخ</th>
                        <th>نوع</th>
                        <th>اعضای حاضر</th>
                        <th>شرح تصمیم</th>
                        <th class="text-center" style="width:100px">فایل</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($sessions as $s)
                        <tr>
                            <td>{{ $s['title'] }}</td>
                            <td>{{ $s['date'] }}</td>
                            <td>{{ $s['type'] }}</td>
                            <td>
                                @foreach($s['members'] as $m)
                                    <span class="badge rounded-pill bg-dark">{{ $m }}</span>
                                @endforeach
                            </td>
                            <td style="max-width:180px">{{ $s['desc'] }}</td>
                            <td class="text-center">
                                <a href="{{ asset('files/'.$s['file']) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="mdi mdi-download"></i> دریافت
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">هیچ صورتجلسه‌ای ثبت نشده است.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
