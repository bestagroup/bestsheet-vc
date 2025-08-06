<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use App\Models\MenuPanel;
use App\Models\SubmenuPanel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Morilog\Jalali\Jalalian;
use function Laravel\Prompts\select;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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

        $totalPaid = DB::table('finances')->sum('amount');

        $projects = DB::table('finances as f')
            ->leftjoin('projects as p', 'f.project_id', '=', 'p.id')
            ->select('p.title', DB::raw('SUM(f.amount) as total_amount') , 'p.logo')
            ->groupBy('p.title' , 'p.logo')
            ->having('total_amount', '>', 0)
            ->orderBy('total_amount', 'desc')
            ->get();
//        $menupanels     = Menupanel::select('id','priority','icon', 'title','label', 'slug', 'status' , 'class' , 'controller')->get();
//        $submenupanels  = Submenupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller' , 'menu_id')->get();

        $menupanels = MenuPanel::with(['submenus'])
            ->where('status', 4)
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


        return view('dashboard')->with(compact(['thispage' , 'projects' , 'totalPaid' ,'monthLabels', 'users','finances' , 'monthlyData']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
