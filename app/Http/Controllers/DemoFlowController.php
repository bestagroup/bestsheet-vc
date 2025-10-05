<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoFlowController extends Controller
{
    public function index(Request $request)
    {
        // مراحل الکی (سمت سرور)
        $investsteps = collect([
            (object)['id' => 1, 'title' => 'ثبت اطلاعات اولیه'],
            (object)['id' => 2, 'title' => 'بارگذاری مدارک'],
            (object)['id' => 3, 'title' => 'بررسی کارشناس'],
            (object)['id' => 4, 'title' => 'تائید نهایی'],
        ]);

        // پروژه الکی + مرحله فعلی از Session (اگر نبود، 1)
        $project = (object)[
            'id'          => 101,
            'title'       => 'پروژه‌ی آزمایشی',
            'invest_step' => intval($request->session()->get('demo_invest_step', 1)),
        ];

        return view('demo.flow', compact('investsteps', 'project'));
    }

    public function store(Request $request)
    {
        // ورودی‌های فرم
        $projectId  = (int) $request->input('project_id');
        $stepId     = (int) $request->input('step_id');
        $status     = (string) $request->input('status'); // approved / rejected
        $desc       = (string) $request->input('description', '');

        // منطق الکی: اگر approved بود، مرحله بعدی را در Session ذخیره کن
        if ($status === 'approved') {
            $next = $stepId + 1;
            $request->session()->put('demo_invest_step', $next);
        }

        // پاسخ JSON
        return response()->json([
            'success'       => true,
            'message'       => 'مرحله ثبت شد',
            'current_step'  => $stepId,
            'next_step'     => $stepId + 1,
            'status'        => $status,
            'project_id'    => $projectId,
            'description'   => $desc,
        ]);
    }
}
