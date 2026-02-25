<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use App\Models\Financial_statement;
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
        // دریافت فیلترها
        // ================================
        $companyId = $request->company_id;
        $fromDate  = $request->from_date;
        $toDate    = $request->to_date;

        // ================================
        // Persian Months
        // ================================
        $monthsFa = ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];

        // ================================
        // لیست شرکت‌ها برای dropdown
        // ================================
        $companies = Project::select('id','title')->where('invest_step' , '>', 13)->get();

        // ================================
        // Invest Steps
        // ================================
        $investSteps = Investstep::where('id','>=',1)->orderBy('id')->get();

        // ================================
        // 1️⃣ Deal Funnel
        // ================================
        $dealFunnel = $investSteps->map(function($step) use ($companyId) {

            $query = Project::where('is_rejected',1)
                ->where('reject_step',$step->id);

            if ($companyId) {
                $query->where('id',$companyId);
            }

            return [
                'title' => $step->title,
                'count' => $query->count()
            ];
        })->filter(fn($i) => $i['count'] > 0)->values();

        $dealFunnel = [
            'labels' => $dealFunnel->pluck('title'),
            'data'   => $dealFunnel->pluck('count'),
        ];

        // ================================
        // 2️⃣ Strategic Fit
        // ================================
        $strategicFit = [
            'labels' => $investSteps->pluck('title'),
            'data'   => $investSteps->map(function($step) use ($companyId) {

                $query = Project::where('invest_step',$step->id);

                if ($companyId) {
                    $query->where('id',$companyId);
                }

                return $query->count();
            })
        ];

        // ================================
        // 3️⃣ Sector Allocation (با scope)
        // ================================
        $payments = Finance::query()
            ->filter($request)
            ->from('finances as f')
            ->leftJoin('projects as p','f.project_id','=','p.id')
            ->where('f.amount','>',0)
            ->select(
                'p.id as project_id',
                'p.title',
                'p.logo',
                DB::raw('SUM(f.amount) as total_paid')
            )
            ->groupBy('p.id','p.title','p.logo')
            ->orderByDesc('total_paid')
            ->get();

        $total = $payments->sum('total_paid');

        $payments = $payments->map(function($item) use ($total){
            $item->percent_of_total = $total > 0
                ? round(($item->total_paid / $total) * 100,2)
                : 0;
            return $item;
        });

        $sectorAllocation = [
            'labels' => $payments->pluck('title'),
            'data'   => $payments->pluck('percent_of_total'),
        ];

        // ================================
        // 4️⃣ Financial Statements (با scope)
        // ================================
        $records = Financial_statement::query()
            ->filter($request)
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = $records->map(fn($r) =>
            $r->year.'/'.str_pad($r->month,2,'0',STR_PAD_LEFT)
        );

        $stageAllocation = [
            'labels' => $labels,
            'data'   => $records->map(fn($r) =>
            (int) str_replace(',','',$r->net_sales)
            ),
        ];

        // ================================
        // KPI Trend (static فعلاً)
        // ================================
        $portfolioKpi = [
            'months'  => $monthsFa,
            'mrr'     => [120,138,155,172,190,210,235,255,280,305,330,360],
            'burn'    => [92,95,98,101,105,110,112,114,116,118,120,122],
            'runway'  => [15,15,14,13,12,12,11,10,10,9,9,8],
        ];

        // ================================
        // سایر داده‌های استاتیک
        // ================================
        $portfolioHealth = [
            'labels' => ['پایدار','در حال رشد','ریسکی','بحرانی','آماده خروج'],
            'data'   => [14,9,6,2,3],
        ];

        $exitTimeline = [
            'labels' => ['۱۳۹۹','۱۴۰۰','۱۴۰۱','۱۴۰۲','۱۴۰۳'],
            'count'  => [1,2,4,5,3],
            'value'  => [8,14,26,33,21],
        ];

        $fundMetrics = [
            'labels' => ['۱۳۹۹','۱۴۰۰','۱۴۰۱','۱۴۰۲','۱۴۰۳'],
            'tvpi'   => [1.05,1.22,1.38,1.61,1.84],
            'dpi'    => [0.12,0.28,0.39,0.52,0.63],
            'rvpi'   => [0.93,0.94,0.99,1.09,1.21],
        ];

        $companyPerformance = [
            'labels' => $labels,
            'irr'    => $records->pluck('net_sales'),
            'mom'    => $records->pluck('gross_profit'),
        ];

        $runwayByCompany = [
            'labels' => $companies->pluck('title'),
            'data'   => [11,9,14,10,8,12,9,13],
        ];

        $cashAndEquivalents = $records->pluck('cash_and_equivalents')
            ->map(fn($v)=>(int)str_replace(',','',$v));

        $totalCurrentAssets = $records->pluck('total_current_assets')
            ->map(fn($v)=>(int)str_replace(',','',$v));

        $totalCurrentLiabilities = $records->pluck('total_current_liabilities')
            ->map(fn($v)=>(int)str_replace(',','',$v));

        return view('panel.report')->with(compact('thispage', 'companies','dealFunnel','strategicFit','labels','sectorAllocation','stageAllocation','portfolioKpi','portfolioHealth','exitTimeline','fundMetrics','companyPerformance','runwayByCompany','cashAndEquivalents','totalCurrentAssets','totalCurrentLiabilities'
        ));
    }

    public function show(Request $request)
    {
        // همان منطق index ولی بدون view

        $payments = Finance::query()
            ->filter($request)
            ->from('finances as f')
            ->leftJoin('projects as p','f.project_id','=','p.id')
            ->where('f.amount','>',0)
            ->select(
                'p.title',
                DB::raw('SUM(f.amount) as total_paid')
            )
            ->groupBy('p.title')
            ->orderByDesc('total_paid')
            ->get();

        $total = $payments->sum('total_paid');

        $sectorAllocation = [
            'labels' => $payments->pluck('title'),
            'data'   => $payments->map(fn($i) =>
            $total > 0 ? round(($i->total_paid/$total)*100,2) : 0
            ),
        ];

        $records = FinancialStatement::query()
            ->filter($request)
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = $records->map(fn($r)=>
            $r->year.'/'.str_pad($r->month,2,'0',STR_PAD_LEFT)
        );

        return response()->json([
            'sectorAllocation' => $sectorAllocation,
            'labels' => $labels,
            'cashAndEquivalents' =>
                $records->pluck('cash_and_equivalents')
                    ->map(fn($v)=>(int)str_replace(',','',$v)),
            'totalCurrentAssets' =>
                $records->pluck('total_current_assets')
                    ->map(fn($v)=>(int)str_replace(',','',$v)),
            'totalCurrentLiabilities' =>
                $records->pluck('total_current_liabilities')
                    ->map(fn($v)=>(int)str_replace(',','',$v)),
        ]);
    }
}
