@extends('layouts.base')

@section('title')
    <title>{{ $thispage['title'] }}</title>
@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/jalalidatepicker/jalalidatepicker.min.css')}}" />
@endsection
@section('content')
    <style>
        .report-wrap { direction: rtl; }
        .report-title { margin-bottom: 6px; font-weight: 700; }
        .report-subtitle { margin-top: 0; opacity: .75; }

        .kpi-row {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 18px;
        }
        .kpi-col { flex: 1 1 220px; }

        .kpi-card {
            border-radius: 14px;
            box-shadow: 0 8px 22px rgba(17,24,39,.08) !important;
        }
        .kpi-card .card-content { padding: 16px 16px; }
        .kpi-value { font-size: 22px; font-weight: 800; margin: 0; }
        .kpi-label { margin: 6px 0 0; opacity: .9; }

        .report-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }
        .report-col { flex: 1 1 calc(33.333% - 14px); min-width: 340px; }

        .report-card {
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(17,24,39,.08) !important;
            overflow: hidden;
        }
        .report-card .card-content { padding: 16px 16px 10px; }
        .card-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; }
        .card-head h6 { margin:0; font-weight:800; font-size: 14px; color: #1f2937; }
        .card-hint { font-size: 12px; opacity: .65; }

        /* fixed height for chart areas */
        .chart-box { position: relative; height: 240px; }
        .chart-box.tall { height: 280px; }
        .chart-box.full { height: 320px; }

        /* make canvas fill */
        .chart-box canvas { width: 100% !important; height: 100% !important; }

        @media (max-width: 1100px) { .report-col { flex: 1 1 calc(50% - 14px); } }
        @media (max-width: 700px)  { .report-col { flex: 1 1 100%; min-width: unset; } }
    </style>

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-email.css') }}" />

    <div class="report-wrap">

        <div class="row" style="margin-bottom:10px;">
            <div class="col s12">
                <h4 class="report-title">{{ $thispage['title'] }}</h4>
                <p class="report-subtitle">نمای کلی از قیف پذیرش، وضعیت پورتفو، عملکرد و بازدهی سرمایه‌گذاری.</p>
            </div>
        </div>

        {{-- KPI --}}
        <div class="kpi-row">
            <div class="kpi-col">
                <div class="card kpi-card" style="background:#0ea5e9;color:#fff;">
                    <div class="card-content center">
                        <p class="kpi-value">{{number_format(DB::table('projects')->count())}}</p>
                        <p class="kpi-label">کل طرح های ثبت شده</p>
                    </div>
                </div>
            </div>

            <div class="kpi-col">
                <div class="card kpi-card" style="background:#10b981;color:#fff;">
                    <div class="card-content center">
                        <p class="kpi-value">{{DB::table('projects')->where('is_rejected' , 1)->count()}}</p>
                        <p class="kpi-label"> کل طرح های رد شده</p>
                    </div>
                </div>
            </div>

            <div class="kpi-col">
                <div class="card kpi-card" style="background:#6366f1;color:#fff;">
                    <div class="card-content center">
                        <p class="kpi-value">{{DB::table('projects')->where('is_rejected' , 0)->count()}}</p>
                        <p class="kpi-label">کل طرح های جاری</p>
                    </div>
                </div>
            </div>

            <div class="kpi-col">
                <div class="card kpi-card" style="background:#f97316;color:#fff;">
                    <div class="card-content center">
                        <p class="kpi-value">{{DB::table('projects')->where('invest_step' , 20)->count()}}</p>
                        <p class="kpi-label">کل طرح های خاتمه یافته</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" style="margin:15px;padding: 40px;">
            <div class="card-content">
                <form method="GET" action="{{ route('report.index') }}">
                    <div class="row">

                        {{-- شرکت --}}
                        {{-- شرکت --}}
                        <div class="input-field col s12 m4">
                            <select name="company_id" class="form-control">
                                <option value="">همه شرکت‌ها</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- از تاریخ --}}
                        <div class="input-field col s12 m3">
                            <input type="text" data-jdp class="form-control" autocomplete="off"
                                   id="from_date" name="from_date" placeholder="از تاریخ"
                                   value="{{ request('from_date') }}">
                        </div>

                        {{-- تا تاریخ --}}
                        <div class="input-field col s12 m3">
                            <input type="text" data-jdp class="form-control" autocomplete="off"
                                   id="to_date" name="to_date" placeholder="تا تاریخ"
                                   value="{{ request('to_date') }}">
                        </div>

                        <div class="input-field col s12 m2">
                            <button class="btn" type="submit">اعمال فیلتر</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- Charts --}}
        <div class="report-grid">

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head"><h6>روند فروش خالص</h6></div>
                        <div class="chart-box">
                            <canvas id="netSalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head"><h6>نسبت بهای تمام‌شده به فروش</h6></div>
                        <div class="chart-box">
                            <canvas id="cogsRatioChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head"><h6>حاشیه سود ناخالص</h6></div>
                        <div class="chart-box">
                            <canvas id="grossMarginChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head"><h6>نسبت هزینه اداری و فروش</h6></div>
                        <div class="chart-box">
                            <canvas id="sgaRatioChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head"><h6>ترکیب دارایی‌های جاری</h6></div>
                        <div class="chart-box">
                            <canvas id="currentAssetRatioChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head"><h6>نقدینگی (Current Ratio)</h6></div>
                        <div class="chart-box">
                            <canvas id="currentRatioChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head"><h6>ریسک مالی (بدهی به سرمایه)</h6></div>
                        <div class="chart-box">
                            <canvas id="debtToEquityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head"><h6>بازده دارایی (ROA)</h6></div>
                        <div class="chart-box">
                            <canvas id="roaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head"><h6>کیفیت سود</h6></div>
                        <div class="chart-box">
                            <canvas id="profitQualityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head"><h6>کنترل ترازنامه</h6></div>
                        <div class="chart-box">
                            <canvas id="balanceCheckChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/vendor/libs/chartjs/chartjs.js') }}"></script>
    <script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/jalalidatepicker/jalalidatepicker.min.js')}}"></script>
    <script src="{{ asset('assets/vendor/js/formhandler.js') }}"></script>
    {{-- ================================ --}}
    {{-- نکته اصلی: همه نمودارها از یک labels مشترک استفاده می‌کنند --}}
    {{-- labels = بازه زمانی (سال/ماه) --}}
    {{-- ================================ --}}

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (!window.Chart) return;

            Chart.defaults.font.family = 'Vazirmatn, IRANSans, system-ui';
            Chart.defaults.color = '#374151';

            const gridColor   = 'rgba(17,24,39,.06)';
            const borderColor = 'rgba(17,24,39,.12)';

            const baseOptions = {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    x: { grid: { display: false }, border: { display: false } },
                    y: { grid: { color: gridColor }, border: { color: borderColor } }
                }
            };

            const labels = @json($netSales['labels']);

            const lineChart = (id, data, color, unit) =>
                new Chart(document.getElementById(id), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            borderColor: color,
                            backgroundColor: color.replace('1)', '.12)'),
                            fill: true,
                            tension: .35,
                            pointRadius: 2
                        }]
                    },
                    options: {
                        ...baseOptions,
                        scales: {
                            ...baseOptions.scales,
                            y: {
                                ...baseOptions.scales.y,
                                beginAtZero: false, // مهم برای نمایش منفی‌ها
                                title: { display: true, text: unit }
                            }
                        }
                    },
                    plugins: {
                        ...baseOptions.plugins
                    },
                    plugins: [{
                        id: 'force-ltr',
                        beforeInit: chart => {
                            chart.ctx.canvas.style.direction = 'ltr';
                        }
                    }]
                });

            const barChart = (id, data, color, unit) =>
                new Chart(document.getElementById(id), {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: color,
                            borderRadius: 10
                        }]
                    },
                    options: {
                        ...baseOptions,
                        scales: {
                            ...baseOptions.scales,
                            y: {
                                ...baseOptions.scales.y,
                                beginAtZero: false, // مقادیر منفی را به درستی نمایش دهد
                                title: { display: true, text: unit }
                            }
                        }
                    },
                    plugins: {
                        ...baseOptions.plugins
                    },
                    plugins: [{
                        id: 'force-ltr',
                        beforeInit: chart => {
                            chart.ctx.canvas.style.direction = 'ltr';
                        }
                    }]
                });

            // ================================
            // KPI Charts با واحد اندازه‌گیری
            // ================================
            lineChart('netSalesChart',          @json($netSales['data']),          'rgba(14,165,233,1)', 'ریال');
            lineChart('cogsRatioChart',         @json($cogsRatio['data']),         'rgba(244,63,94,1)', 'ریال');
            lineChart('grossMarginChart',       @json($grossMargin['data']),       'rgba(16,185,129,1)', 'ریال');
            barChart ('sgaRatioChart',          @json($sgaRatio['data']),          'rgba(99,102,241,.85)', 'ریال');
            lineChart('currentAssetRatioChart', @json($currentAssetRatio['data']), 'rgba(14,165,233,1)');
            lineChart('currentRatioChart',      @json($currentRatio['data']),      'rgba(16,185,129,1)');
            barChart ('debtToEquityChart',      @json($debtToEquity['data']),      'rgba(249,115,22,.85)', 'ریال');
            lineChart('roaChart',               @json($roa['data']),               'rgba(99,102,241,1)');
            lineChart('profitQualityChart',     @json($profitQuality['data']),     'rgba(14,165,233,1)');
            barChart ('balanceCheckChart',      @json($balanceCheck['data']),      'rgba(244,63,94,.75)', 'ریال');
        });
    </script>
@endpush
