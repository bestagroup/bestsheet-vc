<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Investstep;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $thispage = [
            'title'   => 'گزارشات جامع سرمایه‌گذاری',
            'list'    => 'داشبورد گزارشات مدیریتی',
            'add'     => '',
            'create'  => '',
            'enter'   => '',
            'edit'    => '',
            'delete'  => '',
        ];

        // ================================
        // Persian Months (Jalali)
        // ================================
        $monthsFa = ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];

        // ================================
        // Portfolio Companies (FA names)
        // ================================
        $companiesFa = [
            'فراهوش', 'تپسیار', 'پزشک‌یار', 'ابرپرداز', 'هوشمند‌راه', 'داده‌نگار', 'فینوتک', 'سروین‌کلاد'
        ];
        $investSteps = Investstep::where('id' , '>=' , 1)->orderBy('id')->get();
        // 1) Deal Flow Funnel (Counts)
        $dealFunnel = $investSteps->map(function($step) {
            $count = Project::where('is_rejected', 1)
                ->where('reject_step', $step->id)
                ->count();
            return [
                'title' => $step->title,
                'count' => $count
            ];
        })
            ->filter(fn($item) => $item['count'] > 0) // فقط مراحل با حداقل یک رد
            ->values();

        $dealFunnel = [
            'labels' => $dealFunnel->pluck('title'),
            'data'   => $dealFunnel->pluck('count'),
        ];
        // 2) Strategic Fit Distribution


// شمارش خروجی‌ها بر اساس مرحله
        $strategicFit = [
            'labels' => $investSteps->pluck('title'), // نام مراحل
            'data'   => $investSteps->map(function($step) {
                return Project::where('invest_step', $step->id)
                    ->count();
            })
        ];

        // 3) Sector Allocation (%)
        $payments = DB::table('finances as f')
            ->leftJoin('projects as p', 'f.project_id', '=', 'p.id')
            ->where('f.amount', '>', 0)
            ->select(
                'p.id as project_id',
                'p.title',
                'p.logo',
                DB::raw('SUM(f.amount) as total_paid')
            )
            ->groupBy('p.id', 'p.title', 'p.logo')
            ->orderBy('total_paid', 'DESC')
            ->get();

// محاسبه درصد کل
        $total = $payments->sum('total_paid');

        $payments = $payments->map(function($item) use ($total) {
            $item->percent_of_total = $total > 0 ? round(($item->total_paid / $total) * 100, 2) : 0;
            return $item;
        });

        $sectorAllocation = [
            'labels' => $payments->pluck('title'),
            'data'   => $payments->pluck('percent_of_total'),
        ];

        // 4) Stage Allocation (count)
        $stageAllocation = [
            'labels' => ['پیش‌بذری','بذری','سری A','سری B','بلوغ'],
            'data'   => [18, 26, 11, 6, 2],
        ];

        // 5) Portfolio KPI Trend (12 months)
        $portfolioKpi = [
            'months'  => $monthsFa,
            'mrr'     => [120, 138, 155, 172, 190, 210, 235, 255, 280, 305, 330, 360], // (میلیون تومان / یا هر واحد)
            'burn'    => [92, 95, 98, 101, 105, 110, 112, 114, 116, 118, 120, 122],
            'runway'  => [15, 15, 14, 13, 12, 12, 11, 10, 10, 9, 9, 8], // ماه
        ];

        // 6) Portfolio Health (count)
        $portfolioHealth = [
            'labels' => ['پایدار','در حال رشد','ریسکی','بحرانی','آماده خروج'],
            'data'   => [14, 9, 6, 2, 3],
        ];

        // 7) Exit Timeline (counts + value)
        $exitTimeline = [
            'labels' => ['۱۳۹۹','۱۴۰۰','۱۴۰۱','۱۴۰۲','۱۴۰۳'],
            'count'  => [1, 2, 4, 5, 3],
            'value'  => [8, 14, 26, 33, 21], // مثلا میلیارد تومان / یا میلیون دلار
        ];

        // 8) Fund Metrics (TVPI, DPI, RVPI)
        $fundMetrics = [
            'labels' => ['۱۳۹۹','۱۴۰۰','۱۴۰۱','۱۴۰۲','۱۴۰۳'],
            'tvpi'   => [1.05, 1.22, 1.38, 1.61, 1.84],
            'dpi'    => [0.12, 0.28, 0.39, 0.52, 0.63],
            'rvpi'   => [0.93, 0.94, 0.99, 1.09, 1.21],
        ];

        // 9) Company Performance (Top companies)
        $companyPerformance = [
            'labels' => $companiesFa,
            'irr'    => [31, 24, 18, 27, 14, 22, 19, 16], // %
            'mom'    => [12, 9, 6, 10, 4, 8, 7, 5],       // % MoM growth
        ];

        // 10) Runway by Company (months)
        $runwayByCompany = [
            'labels' => $companiesFa,
            'data'   => [11, 9, 14, 10, 8, 12, 9, 13],
        ];

        return view('panel.report')->with(compact(
            'thispage',
            'dealFunnel',
            'strategicFit',
            'sectorAllocation',
            'stageAllocation',
            'portfolioKpi',
            'portfolioHealth',
            'exitTimeline',
            'fundMetrics',
            'companyPerformance',
            'runwayByCompany'
        ));
    }
}
