<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Commitment;
use App\Models\Finance;
use App\Models\Investstep;
use App\Models\KPI;
use App\Models\MediaFile;
use App\Models\MenuPanel;
use App\Models\Project;
use App\Models\Project_step;
use App\Models\State;
use App\Models\SubmenuPanel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class FlowController extends Controller
{
    public function index(Request $request)
    {
        $submenupanels  = SubmenuPanel::select('id','priority','title','label','menu_id','slug','status','class','controller')->get();
        $menupanels     = Menupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller')->get();
        $states         = State::all();
        $cities         = City::all();

        $thispage       = [
            'title'   => 'مدیریت طرح / شرکت  ',
            'list'    => 'لیست طرح ها و شرکت ها  ',
            'add'     => 'افزودن طرح / شرکت  ',
            'create'  => 'ایجاد طرح / شرکت  ',
            'enter'   => 'ورود طرح / شرکت  ',
            'edit'    => 'ویرایش اطلاعات طرح / شرکت  ',
            'upload'  => 'بارگزاری فایل طرح / شرکت  ',
            'delete'  => 'حذف طرح / شرکت  ',
        ];

        if ($request->ajax()) {
            $data = DB::table('projects as p')
                ->select(
                    'p.id',
                    'p.title',
                    'p.company_name',
                    'p.CEO',
                    DB::raw('(SELECT i.title FROM investsteps i WHERE i.id = p.invest_step LIMIT 1) as flow_level'),
                    'p.start_date',
                    'p.invest_step',
                    'p.percentageshare',
                    'p.amount_request_accept',
                    'p.is_rejected',
                    'p.created_at',
                    DB::raw('(SELECT COALESCE(SUM(f.amount),0) FROM finances f WHERE f.project_id = p.id) as total_payment')
                )
                ->get();
            return Datatables::of($data)
                ->addColumn('title', function ($data) {
                    return ($data->title);
                })
                ->addColumn('CEO', function ($data) {
                    return ($data->CEO);
                })
                ->addColumn('company_name', function ($data) {
                    return ($data->company_name);
                })
                ->addColumn('flow_level', function ($data) {
                    $flow = $data->flow_level;

                    if ($data->is_rejected == 1) {
                        // می‌توانی از FontAwesome یا emoji استفاده کنی
                        $flow .= ' <span style="color:red;">&#9940;</span>'; // علامت عبور ممنوع ❌
                    }
                    return $flow;
                })
                ->addColumn('percentageshare', function ($data) {
                    return ($data->percentageshare . '%');
                })
                ->addColumn('invest_step', function ($data) {

                    $percent = ($data->invest_step * 100) / 20;

                    return '
                        <div class="d-flex align-items-center" style="min-width:120px;">
                            <div class="progress flex-grow-1" style="height: 8px; background:#e7e7e7; border-radius:4px;">
                                <div class="progress-bar bg-primary"
                                    role="progressbar"
                                    style="width: '.$percent.'%; border-radius:4px;">
                                </div>
                            </div>
                            <span class="ms-2" style="font-size: 0.85rem;">'.$percent.'%</span>
                        </div>
                    ';
                })
                ->addColumn('start_date', function ($data) {
                    return ($data->start_date);
                })
                ->addColumn('amount_request_accept', function ($data) {
                    return (number_format($data->amount_request_accept));
                })
                ->addColumn('amount_deposited', function ($data) {
                    return (number_format($data->total_payment));
                })
                ->addColumn('commitment_balance', function ($data) {
                    return (number_format($data->amount_request_accept - $data->total_payment));
                })
                ->addColumn('created_at', function ($data) {
                    return (jdate($data->created_at)->format('Y-m') ?? 0);
                })
                ->editColumn('action', function ($data) {
                    $base = 'btn btn-sm btn-icon rounded-pill waves-effect mx-1';

                    $actionBtn = '';
                    if (auth()->user()->can('can-access', ['flow', 'edit'])) {
                        $actionBtn .= '<button type="button" class="'.$base.' btn btn-sm btn-outline-primary edit-btn" data-id="'.$data->id.'" data-url="'.route('flow.edit', $data->id).'"><i class="mdi mdi-pencil-outline"></i></button>';

                    }
                    if (auth()->user()->can('can-access', ['flow', 'delete'])) {
                        $actionBtn .= '<button type="button" class="'.$base.' btn btn-sm btn-icon btn-outline-danger mx-1 delete-btn" data-id="'.$data->id.'"><i class="mdi mdi-delete-outline"></i></button>';
                    }
                    $actionBtn .= '<button type="button" class="'.$base.' btn btn-sm btn-outline-primary show-btn" data-id="'.$data->id.'" data-url="'.route('flow.show', $data->id).'"><i class="mdi mdi-eye"></i></button>';

                    $actionBtn .= '<button class="'.$base.' btn btn-sm btn-icon btn-image mx-1 upload-btn" data-id="'.$data->id.'"><i class="mdi mdi-file-document-multiple-outline"></i></button>';

                    return $actionBtn;
                })
                ->rawColumns(['action','invest_step','flow_level'])
                ->make(true);
        }
        return view('panel.flow')->with(compact(['thispage' , 'submenupanels' ,'menupanels' , 'states'  ,'cities']));
    }

    public function store(Request $request)
    {
        try {
            $project_steps = new Project_step();

            $project_steps->project_id  = $request->input('project_id');
            $project_steps->title       = $request->input('step_title');
            $project_steps->step_number = $request->input('step_id');
            $project_steps->status      = $request->input('status'); // approved / rejected
            $project_steps->description = $request->input('description', '');
            $project_steps->user_id     = Auth::user()->id;

            $result = $project_steps->save();

            $project = Project::findOrfail($request->input('project_id'));

            $currentStep = (int) $request->input('step_id');
            $maxStep     = Investstep::max('id') ?? $project->invest_step ?? 1;

            if ($project_steps->status === 'approved') {
                $project->invest_step = min($maxStep, $currentStep + 1);
                $project->is_rejected = 0;
                $project->reject_step = null;
            } elseif ($project_steps->status === 'rejected') {
                $project->is_rejected = 1;
                $project->reject_step = $currentStep;
                $project->invest_step = $currentStep;
            }

            $project->update();

            if ($result == true) {
                $success = true;
                $flag = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات زیرمنو با موفقیت ثبت شد';
            } elseif ($result != true) {
                $success = false;
                $flag = 'error';
                $subject = 'عملیات نا موفق';
                $message = 'اطلاعات زیرمنو ثبت نشد، لطفا مجددا تلاش نمایید';
            }

        } catch (Exception $e) {
            Log::error('Project store error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['success' => false, 'flag' => 'error', 'subject' => 'خطای سرور', 'message' => 'مشکلی در ثبت اطلاعات پیش آمد، لطفاً بعداً تلاش نمایید',
            ], 500);
        }
        return response()->json(['success'=>$success , 'subject' => $subject, 'flag' => $flag, 'message' => $message]);
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $states = State::all();
        $cities = City::all();

        return view('panel.partials.edit-form', compact('project', 'states', 'cities'));
    }

    public function show($id)
    {
        $project = Project::leftJoin('users', 'users.id', '=', 'projects.user_id')
            ->leftJoin('investsteps', 'investsteps.id', '=', 'projects.user_id')
            ->select('projects.*', 'users.name as ceo_name', 'users.phone')
            ->where('projects.id', $id)
            ->first();


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


        $finances = Finance::all();
        $states = State::all();
        $cities = City::all();
        $kpis = Kpi::orderBy('kpi_number', 'ASC')->get();
        $investsteps = Investstep::whereStatus(4)->get();
        $files = MediaFile::where('status', '!=', 5)->get();
        $commitments = Commitment::whereStatus(4)->get();
        $project_steps = Project_step::leftJoin('users', 'project_steps.user_id', '=', 'users.id')
            ->where('project_steps.project_id', $id)
            ->select('project_steps.*', 'users.name as username')
            ->get();

        return view('panel.partials.show-profile', compact('project', 'strategicFit','dealFunnel' , 'sectorAllocation' , 'states','cities','project_steps','kpis','investsteps','files','commitments','finances'
        ));
    }

    public function destroy($id)
    {
        try {
            $project = Project::findOrfail($id);
            $result = $project->delete();

            if ($result == true) {
                $success = true;
                $flag = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات با موفقیت پاک شد';
            }elseif($result != true) {
                $success = false;
                $flag    = 'error';
                $subject = 'عملیات نا موفق';
                $message = 'اطلاعات زیرمنو ثبت نشد، لطفا مجددا تلاش نمایید';
            }

        } catch (Exception $e) {

            $success = false;
            $flag    = 'error';
            $subject = 'خطا در ارتباط با سرور';
            $message = 'اطلاعات پاک نشد،لطفا بعدا مجدد تلاش نمایید ';
        }
        return response()->json(['success'=>$success , 'subject' => $subject, 'flag' => $flag, 'message' => $message]);
    }
}
