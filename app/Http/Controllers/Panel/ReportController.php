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
            'title' => 'گزارشات جامع سرمایه‌گذاری',
            'list'  => 'داشبورد گزارشات مدیریتی',
        ];

        // لیست شرکت‌ها
        $companies = Project::select('id','title')
            ->where('invest_step','>',13)
            ->get();

        // ================================
        // Base Query با استفاده از scopeFilter
        // ================================
        $fsQuery = Financial_statement::query()
            ->filter($request)   // scopeFilter
            ->join('projects','projects.id','=','financial_statements.project_id')
            ->orderBy('financial_statements.year');

        // ================================
        // Helper: تبدیل Collection به labels/data امن
        // ================================
        $toChart = function ($rows, $valueField) {
            $rows = $rows->map(fn($r) => [
                'label' => $r->year,
                'value' => (float)$r->$valueField
            ]);
            return [
                'labels' => $rows->pluck('label')->values(),
                'data'   => $rows->pluck('value')->values()
            ];
        };

        // ================================
        // 1️⃣ Net Sales رشد فروش
        // ================================
        $netSales          = $toChart((clone $fsQuery)->get(), 'net_sales');

        // ================================
        // 2️⃣ COGS Ratio نسبت بهای تمام‌شده به فروش
        // ================================

        $cogsRatio = $toChart(
            (clone $fsQuery)->get()->map(fn($r) => (object)[
                'year'=>$r->year,
                'cogs_ratio'=>($r->cogs_goods + $r->cogs_services)/($r->net_sales ?: 1)
            ]), 'cogs_ratio'
        );

        // ================================
        // 3️⃣ Gross Margin حاشیه سود ناخالص
        // ================================
        $grossMargin = $toChart(
            (clone $fsQuery)->get()->map(fn($r) => (object)[
                'year'=>$r->year,
                'gross_margin'=>($r->gross_profit)/($r->net_sales ?: 1)
            ]), 'gross_margin'
        );

        // ================================
        // 4️⃣ SG&A Ratio نسبت هزینه اداری و فروش
        // ================================
        $sgaRatio = $toChart(
            (clone $fsQuery)->get()->map(fn($r) => (object)[
                'year'=>$r->year,
                'sga_ratio'=>($r->selling_general_admin_expense)/($r->net_sales ?: 1)
            ]), 'sga_ratio'
        );

        // ================================
        // 5️⃣ Current Asset Ratio ترکیب دارایی‌ها
        // ================================
        $currentAssetRatio = $toChart(
            (clone $fsQuery)->get()->map(fn($r) => (object)[
                'year'=>$r->year,
                'current_asset_ratio'=>($r->total_current_assets)/($r->total_assets ?: 1)
            ]), 'current_asset_ratio'
        );

        // ================================
        // 6️⃣ Current Ratio نقدینگی
        // ================================
        $currentRatio = $toChart(
            (clone $fsQuery)->get()->map(fn($r) => (object)[
                'year'=>$r->year,
                'current_ratio'=>($r->total_current_assets)/($r->total_current_liabilities ?: 1)
            ]), 'current_ratio'
        );

        // ================================
        // 7️⃣ Debt to Equity ریسک مالی
        // ================================
        $debtToEquity = $toChart(
            (clone $fsQuery)->get()->map(fn($r) => (object)[
                'year'=>$r->year,
                'debt_to_equity'=>($r->total_liabilities)/($r->total_equity ?: 1)
            ]), 'debt_to_equity'
        );

        // ================================
        // 8️⃣ ROA بازده دارایی
        // ================================
        $roa = $toChart(
            (clone $fsQuery)->get()->map(fn($r) => (object)[
                'year'=>$r->year,
                'roa'=>($r->net_profit)/($r->total_assets ?: 1)
            ]), 'roa'
        );

        // ================================
        // 9️⃣ Profit Quality کیفیت سود
        // ================================
        $profitQuality = $toChart(
            (clone $fsQuery)->get()->map(fn($r) => (object)[
                'year'=>$r->year,
                'profit_quality'=>($r->net_profit - $r->non_operating_net)/($r->net_profit ?: 1)
            ]), 'profit_quality'
        );

        // ================================
        // 🔟 Balance Check کنترل ترازنامه
        // ================================
        $balanceCheck = $toChart(
            (clone $fsQuery)->get()->map(fn($r) => (object)[
                'year'=>$r->year,
                'balance_check'=>($r->total_assets - $r->total_equity_and_liabilities)
            ]), 'balance_check'
        );

        // ================================
        // ارسال به View
        // ================================
        return view('panel.report')->with(compact(
            'thispage','companies',
            'netSales','cogsRatio','grossMargin','sgaRatio','currentAssetRatio','currentRatio',
            'debtToEquity','roa','profitQuality','balanceCheck'
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
