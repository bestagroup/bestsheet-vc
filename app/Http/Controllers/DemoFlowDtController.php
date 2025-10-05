<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoFlowDtController extends Controller
{
    // صفحه‌ی اصلی
    public function index(Request $request)
    {
        // برای سادگی: اگر دیتا در سِشن نبود، بساز
        if (!$request->session()->has('demo_projects')) {
            $projects = [
                ['id'=>101,'company_name'=>'Alpha Co','title'=>'پروژه A','CEO'=>'John','invest_step'=>1],
                ['id'=>102,'company_name'=>'Beta Ltd','title'=>'پروژه B','CEO'=>'Sara','invest_step'=>2],
                ['id'=>103,'company_name'=>'Gamma Inc','title'=>'پروژه C','CEO'=>'Mehdi','invest_step'=>3],
            ];
            $request->session()->put('demo_projects', $projects);
        }
        return view('demo.flow_dt'); // resources/views/demo/flow_dt.blade.php
    }

    // دیتا برای DataTable (Client-side)
    public function data(Request $request)
    {
        $projects = $request->session()->get('demo_projects', []);
        return response()->json(['data' => $projects]);
    }

    // دریافت جزئیات پروژه + Steps
    public function show(Request $request, $id)
    {
        $id = (int) $id;
        $projects = $request->session()->get('demo_projects', []);
        $project = collect($projects)->firstWhere('id', $id);
        if (!$project) {
            return response()->json(['success'=>false,'message'=>'پروژه یافت نشد'], 404);
        }

        $investsteps = [
            ['id'=>1,'title'=>'ثبت اطلاعات اولیه'],
            ['id'=>2,'title'=>'بارگذاری مدارک'],
            ['id'=>3,'title'=>'بررسی کارشناس'],
            ['id'=>4,'title'=>'تأیید نهایی'],
        ];

        return response()->json([
            'success' => true,
            'project' => $project,
            'steps'   => $investsteps,
        ]);
    }

    // تأیید یا رد مرحله
    public function store(Request $request)
    {
        $id     = (int) $request->input('project_id');
        $stepId = (int) $request->input('step_id');
        $status = (string) $request->input('status'); // approved/rejected

        $projects = $request->session()->get('demo_projects', []);
        $idx = collect($projects)->search(fn($p) => $p['id'] === $id);
        if ($idx === false) {
            return response()->json(['success'=>false,'message'=>'پروژه یافت نشد'], 404);
        }

        // منطق ساده: اگر approved بود، invest_step میره به بعدی
        if ($status === 'approved') {
            $projects[$idx]['invest_step'] = $stepId + 1;
        }
        // اگر rejected بود، همینجا فقط ثبت می‌کنیم (برای دمو تغییری در step نمی‌دهیم)
        $request->session()->put('demo_projects', $projects);

        return response()->json([
            'success'      => true,
            'message'      => 'ثبت شد',
            'project_id'   => $id,
            'current_step' => $stepId,
            'next_step'    => $stepId + 1,
            'status'       => $status,
            'invest_step'  => $projects[$idx]['invest_step'],
        ]);
    }
}
