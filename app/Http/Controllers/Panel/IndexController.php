<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\City;
use App\Models\Finance;
use App\Models\Investstep;
use App\Models\MenuPanel;
use App\Models\Project;
use App\Models\SubmenuPanel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;
use function Laravel\Prompts\select;

class IndexController extends Controller
{
    public function index()
    {
        if(Auth::user()->level == 'applicant'){
            return Redirect::route('profile');
        }
        $thispage       = [
            'list'    => 'داشبورد مدیریتی',
        ];
        $finances = DB::table('finances as f')
            ->leftJoin('projects as p', 'f.project_id', '=', 'p.id')
            ->select('f.amount' , 'f.date' , 'p.title' , 'p.logo')
            ->where('f.amount' , '>', 0 )
            ->orderBy('f.date' , 'DESC')
            ->get();

        $users = User::with('lastLogin')
            ->select('id', 'name', 'email', 'gender')
            ->get();
        $nowGregorian = Carbon::now();
        $nowJalali = Jalalian::fromCarbon($nowGregorian)->format('Y-m-d H:i:s');
        $calendars = Calendar::whereJsonContains('guests',(string)Auth::id())->where('start', '>=', $nowJalali)->orderBy('start' , 'ASC')->get();

        $totalPaid = DB::table('finances')->sum('amount');

        $projectis = Project::leftjoin('investsteps' ,'projects.invest_step' , '=' , 'investsteps.id')
            ->select('projects.CEO' ,'projects.company_name' , 'projects.title' , 'investsteps.title as flow_evel' , 'projects.invest_step')
            ->orderBy('projects.invest_step' , 'DESC')->get();

        $projects = DB::table('finances as f')
            ->leftjoin('projects as p', 'f.project_id', '=', 'p.id')
            ->select('p.CEO','p.title', DB::raw('SUM(f.amount) as total_amount') , 'p.logo')
            ->groupBy('p.title','p.logo','p.CEO')
            ->having('total_amount', '>', 0)
            ->orderBy('total_amount', 'desc')
            ->get();

// فرض: سال شمسی انتخابی کاربر
        $selectedYear = 1403;

// شروع و پایان سال شمسی به فرمت شمسی عددی
        $startDate = Jalalian::fromFormat('Ymd', $selectedYear . '0101')->toCarbon()->startOfDay();
        $endDate = Jalalian::fromFormat('Ymd', $selectedYear . '1231')->toCarbon()->endOfDay();

// گرفتن داده‌ها از جدول finances
        $data = DB::table('finances')
            ->whereBetween('date', [
                (int) Jalalian::fromCarbon($startDate)->format('Ymd'),
                (int) Jalalian::fromCarbon($endDate)->format('Ymd'),
            ])
            ->where('amount', '>', 0)
            ->get();

// آرایه ماه‌ها (1 تا 12) با مقدار صفر پیش‌فرض
        $monthlyData = array_fill(1, 12, 0);

        foreach ($data as $item) {
            $jalaliDate = Jalalian::fromFormat('Ymd', $item->date);
            $month = $jalaliDate->getMonth();

            if (isset($monthlyData[$month])) {
                $monthlyData[$month] += $item->amount;
            }
        }


// برچسب‌های ماه به صورت فارسی (می‌تونی دلخواه تغییر بدی)
        $monthLabels = [
            1 => 'فروردین',
            2 => 'اردیبهشت',
            3 => 'خرداد',
            4 => 'تیر',
            5 => 'مرداد',
            6 => 'شهریور',
            7 => 'مهر',
            8 => 'آبان',
            9 => 'آذر',
            10 => 'دی',
            11 => 'بهمن',
            12 => 'اسفند',
        ];

// آماده‌سازی داده‌های نمودار
        $monthLabels = array_values($monthLabels);
        $monthlyData = array_values($monthlyData);

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

        return view('dashboard')->with(compact(['thispage' , 'projects' , 'totalPaid' ,'monthLabels', 'users','finances' , 'monthlyData' , 'calendars' , 'projectis','dealFunnel', 'strategicFit', 'sectorAllocation',]));
    }
    public function getcities($stateId)
    {
        $cities = City::where('state_id', $stateId)->select('id', 'title')->orderBy('title')->get();

        return response()->json($cities);

    }

}
