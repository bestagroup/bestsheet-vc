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
