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
            <i class="mdi mdi-plus"></i>افزودن صورتجلسات
        </button>
    </div>
    <div class="modal fade" id="addMinutesModal" tabindex="-1" aria-labelledby="addMinutesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">افزودن صورتجلسات</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6"><select class="form-select"><option value="">نوع</option><option>چک</option><option>سفته</option><option>وثیقه ملکی</option><option>تعهد اجرایی</option><option>سایر</option></select></div>
                            <div class="col-md-6"><input type="text" class="form-control" placeholder="عنوان یا شماره"></div>
                            <div class="col-md-6"><input type="text" class="form-control" placeholder="ارائه‌دهنده"></div>
                            <div class="col-md-6"><input type="text" class="form-control" placeholder="مبلغ (تومان)"></div>
                            <div class="col-md-6"><input type="text" class="form-control" placeholder="تاریخ صدور"></div>
                            <div class="col-md-6"><input type="text" class="form-control" placeholder="تاریخ سررسید"></div>
                            <div class="col-12"><input type="file" class="form-control"></div>
                            <div class="col-12"><textarea class="form-control" rows="2" placeholder="توضیحات تکمیلی"></textarea></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-primary">ثبت تعهد</button>
                    </div>
                </form>
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
