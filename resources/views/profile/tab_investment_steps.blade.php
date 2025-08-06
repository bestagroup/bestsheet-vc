@php
    $steps = [
        ['title' => 'بررسی اولیه', 'desc' => 'مدارک اولیه طرح را بارگذاری نمایید.', 'content' => '<form><input type="file" class="form-control mb-2"><button class="btn btn-primary">ارسال</button></form>'],
        ['title' => 'غربالگری', 'desc' => 'اطلاعات تکمیلی طرح بررسی می‌شود.', 'content' => '<form><input type="text" class="form-control mb-2" placeholder="سابقه فعالیت"><button class="btn btn-primary">ذخیره</button></form>'],
        ['title' => 'ارزیابی اولیه', 'desc' => 'طرح از نظر امکان‌پذیری بررسی می‌شود.', 'content' => '<form><input type="number" class="form-control mb-2" placeholder="امتیاز"><button class="btn btn-primary">ثبت</button></form>'],
        ['title' => 'ارزیابی موشکافانه', 'desc' => 'مدارک مالی و فنی بررسی می‌شود.', 'content' => '<form><input type="file" class="form-control mb-2"><button class="btn btn-primary">ارسال مدارک</button></form>'],
        ['title' => 'تائیدیه سینا وی سی', 'desc' => 'منتظر تایید مدیرعامل سینا وی‌سی.', 'content' => '<div class="alert alert-info">در حال بررسی...</div>'],
        ['title' => 'تائیدیه دانشمند', 'desc' => 'تاییدیه مدیرعامل دانشمند لازم است.', 'content' => '<div class="alert alert-info">در حال بررسی...</div>'],
        ['title' => 'تصویب هیئت مدیره', 'desc' => 'طرح در هیئت مدیره بررسی می‌شود.', 'content' => '<div class="alert alert-info">منتظر جلسه...</div>'],
        ['title' => 'ارزش‌گذاری', 'desc' => 'مبلغ سرمایه و درصد سهام تعیین می‌شود.', 'content' => '<form><input type="number" class="form-control mb-2" placeholder="مبلغ"><button class="btn btn-primary">ثبت</button></form>'],
        ['title' => 'ارائه در کمیته ارزش‌گذاری', 'desc' => 'خروجی به کمیته ارسال می‌شود.', 'content' => '<div class="alert alert-info">در حال ارائه...</div>'],
        ['title' => 'توافق قراردادی', 'desc' => 'بررسی مفاد قرارداد انجام می‌شود.', 'content' => '<form><input type="file" class="form-control mb-2"><button class="btn btn-primary">بارگذاری</button></form>'],
        ['title' => 'تصویب قرارداد', 'desc' => 'قرارداد نهایی تصویب می‌شود.', 'content' => '<div class="alert alert-info">در انتظار تایید...</div>'],
        ['title' => 'عقد قرارداد', 'desc' => 'قرارداد امضا و مبادله می‌شود.', 'content' => '<div class="alert alert-success">با موفقیت انجام شد.</div>'],
        ['title' => 'پایان دوره ارزش‌آفرینی', 'desc' => 'گزارش عملکرد نهایی ارسال شود.', 'content' => '<form><input type="file" class="form-control mb-2"><button class="btn btn-primary">ارسال گزارش</button></form>'],
        ['title' => 'خروج از طرح', 'desc' => 'فرآیند سرمایه‌گذاری پایان یافته است.', 'content' => '<div class="alert alert-success">خروج موفق.</div>']
    ];
    $currentStep = 7;
@endphp

<div class="tab-pane fade justify-content-center" id="navs-invest-card" role="tabpanel">
    {{-- نوار پیشرفت کلی --}}
    <div class="mb-4">
        <label class="form-label fw-bold">درصد پیشرفت فرآیند:</label>
        <div class="progress" style="height: 20px;">
            <div class="progress-bar bg-success" role="progressbar"
                 style="width: {{ round(($currentStep + 1) / count($steps) * 100, 0) }}%;"
                 aria-valuenow="{{ $currentStep + 1 }}"
                 aria-valuemin="0"
                 aria-valuemax="{{ count($steps) }}">
                {{ round(($currentStep + 1) / count($steps) * 100, 0) }}%
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- ستون سمت راست: لیست مراحل --}}
        <div class="col-md-4">
            <div class="list-group shadow-sm rounded" style="overflow-y:auto; max-height:620px;">
                @foreach($steps as $idx => $step)
                    <div class="list-group-item d-flex align-items-center py-2 {{ $idx === $currentStep ? 'active' : '' }}"
                         style="cursor: default; border-right: 5px solid {{ $idx < $currentStep ? '#4caf50' : ($idx === $currentStep ? '#7367f0' : '#ddd') }};">
                        <span class="me-2 d-inline-flex justify-content-center align-items-center rounded-circle"
                              style="width: 28px; height: 28px; background: {{ $idx < $currentStep ? '#c8e6c9' : ($idx === $currentStep ? '#ede7f6' : '#f1f1f1') }};
                                     color: {{ $idx < $currentStep ? '#2e7d32' : ($idx === $currentStep ? '#5e35b1' : '#aaa') }};
                                     font-weight: bold;">
                            {{ $idx + 1 }}
                        </span>
                        <div class="flex-grow-1">
                            <div class="fw-bold {{ $idx === $currentStep ? 'text-dark' : 'text-muted' }}">{{ $step['title'] }}</div>
                            <small class="text-muted">{{ $step['desc'] }}</small>
                        </div>
                        @if($idx === $currentStep)
                            <span class="badge bg-primary ms-auto">اکنون</span>
                        @elseif($idx < $currentStep)
                            <i class="mdi mdi-check-circle-outline text-success ms-auto"></i>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ستون سمت چپ: جزئیات مرحله جاری --}}
        <div class="col-md-8">
            <div class="card border shadow-sm">
                <div class="card-header bg-light d-flex align-items-center">
                    <span class="badge bg-primary me-2" style="width:26px;">{{ $currentStep + 1 }}</span>
                    <h6 class="mb-0 fw-bold">{{ $steps[$currentStep]['title'] }}</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">{{ $steps[$currentStep]['desc'] }}</p>
                    {!! $steps[$currentStep]['content'] !!}
                </div>
            </div>
        </div>
    </div>
</div>
