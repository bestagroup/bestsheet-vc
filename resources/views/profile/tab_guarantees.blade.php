@php
    $commitments = [
        [
            'type' => 'سفته', 'title' => 'سفته شماره 123456', 'date' => '۱۴۰۳/۰۵/۰۱', 'expire' => '۱۴۰۴/۰۵/۰۱',
            'amount' => '2,000,000,000', 'by' => 'شرکت آلفا', 'status' => 'دریافت‌شده',
            'file' => 'safte-123456.jpg', 'desc' => 'سفته تضمین حسن انجام تعهدات'
        ],
        [
            'type' => 'چک', 'title' => 'چک شماره 874512', 'date' => '۱۴۰۳/۰۶/۲۰', 'expire' => '۱۴۰۳/۱۰/۲۰',
            'amount' => '500,000,000', 'by' => 'آقای ملکی', 'status' => 'در جریان',
            'file' => null, 'desc' => 'چک ضمانت بازگشت سرمایه'
        ],
        [
            'type' => 'وثیقه ملکی', 'title' => 'ملک واقع در خیابان ولیعصر', 'date' => '۱۴۰۳/۰۷/۰۵', 'expire' => null,
            'amount' => 'بدون مبلغ', 'by' => 'شرکت بتا', 'status' => 'دریافت‌شده',
            'file' => 'melk-vli.jpg', 'desc' => 'سند رسمی وثیقه'
        ],
    ];
@endphp

<div class="tab-pane fade justify-content-center" id="navs-guarantee-card" role="tabpanel">
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCommitmentModal">
            <i class="mdi mdi-plus"></i> افزودن تعهد/تضمین جدید
        </button>
    </div>

    {{-- Modal افزودن تعهد --}}
    <div class="modal fade" id="addCommitmentModal" tabindex="-1" aria-labelledby="addCommitmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">افزودن تعهد یا تضمین جدید</h5>
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

    {{-- جدول تعهدات --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold mb-3">لیست تعهدات و تضامین</h6>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>نوع</th>
                        <th>عنوان/شماره</th>
                        <th>ارائه‌دهنده</th>
                        <th>مبلغ</th>
                        <th>تاریخ صدور</th>
                        <th>تاریخ سررسید</th>
                        <th>وضعیت</th>
                        <th class="text-center" style="width:90px">فایل</th>
                        <th>توضیحات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($commitments as $item)
                        <tr>
                            <td>{{ $item['type'] }}</td>
                            <td>{{ $item['title'] }}</td>
                            <td>{{ $item['by'] }}</td>
                            <td>{{ $item['amount'] }}</td>
                            <td>{{ $item['date'] }}</td>
                            <td>{{ $item['expire'] ?? '-' }}</td>
                            <td>
                                    <span class="badge {{ $item['status'] == 'دریافت‌شده' ? 'bg-success' : ($item['status'] == 'در جریان' ? 'bg-warning' : 'bg-secondary') }}">
                                        {{ $item['status'] }}
                                    </span>
                            </td>
                            <td class="text-center">
                                @if($item['file'])
                                    <a href="{{ asset('files/'.$item['file']) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="mdi mdi-eye"></i></a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td style="max-width:120px">{{ $item['desc'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">موردی ثبت نشده است.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
