<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use App\Models\MenuPanel;
use App\Models\Project;
use App\Models\SubmenuPanel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
    public function index(Request $request)
    {

        $submenupanels  = SubmenuPanel::select('id','priority','title','label','menu_id','slug','status','class','controller')->get();
        $menupanels     = Menupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller')->get();
        $projects       = Project::all();
        $finances       = Finance::all();

        $thispage       = [
            'title'   => 'مدیریت پروژه ها',
            'list'    => 'لیست پروژه ها',
            'add'     => 'افزودن پروژه ها',
            'create'  => 'ایجاد پروژه ها',
            'enter'   => 'ورود پروژه ها',
            'edit'    => 'ویرایش پروژه ها',
            'delete'  => 'حذف پروژه ها',
        ];

        if ($request->ajax()) {
            $financeSub = DB::table('finances')
                ->select(
                    'project_id',
                    DB::raw("MAX(CASE WHEN serial = 1 THEN amount END) as first_stage_payment"),
                    DB::raw("MAX(CASE WHEN serial = 2 THEN amount END) as second_stage_payment"),
                    DB::raw("MAX(CASE WHEN serial = 3 THEN amount END) as third_stage_payment"),
                    DB::raw("MAX(CASE WHEN serial = 4 THEN amount END) as fourth_stage_payment"),
                    DB::raw("MAX(CASE WHEN serial = 5 THEN amount END) as fifth_stage_payment")
                )
                ->groupBy('project_id');

            $data = DB::table('projects as p')
                ->leftJoinSub($financeSub, 'f', function ($join) {
                    $join->on('p.id', '=', 'f.project_id');
                })
                ->leftJoin('companies as c', 'p.company_id', '=', 'c.id')
                ->select(
                    'p.id',
                    'c.commercial_name',
                    'c.ceo_name as CEO',
                    'p.portfo_status',
                    'p.flow_level',
                    'p.progress_percentage',
                    'p.activity_status',
                    'p.start_date',
                    'p.amount_request_accept',
                    'p.amount_commitment_first_stage',
                    'p.amount_commitment_second_stage',
                    'p.amount_commitment_third_stage',
                    'p.amount_commitment_fourth_stage',
                    'p.amount_commitment_fifth_stage',
                    'f.first_stage_payment',
                    'f.second_stage_payment',
                    'f.third_stage_payment',
                    'f.fourth_stage_payment',
                    'f.fifth_stage_payment',
                    'p.invest_step'
                )
                ->get();
            return Datatables::of($data)
                ->addColumn('commercial_name', function ($data) {
                    return ($data->commercial_name);
                })
                ->addColumn('CEO', function ($data) {
                    return ($data->CEO);
                })
                ->addColumn('portfo_status', function ($data) {
                    return ($data->portfo_status);
                })
                ->addColumn('flow_level', function ($data) {
                    return ($data->flow_level);
                })
                ->addColumn('activity_status', function ($data) {
                    return ($data->activity_status);
                })
                ->addColumn('start_date', function ($data) {
                    return ($data->start_date);
                })
                ->addColumn('invest_step', function ($data) {
                    return (($data->invest_step * 100 ) / 20 . '%');
                })
                ->addColumn('amount_request_accept', function ($data) {
                    return (number_format($data->amount_request_accept));
                })
                ->addColumn('amount_deposited', function ($data) {
                    return (number_format($data->first_stage_payment + $data->second_stage_payment + $data->third_stage_payment + $data->fourth_stage_payment + $data->fifth_stage_payment));
                })
                ->addColumn('amount_commitment_first_stage', function ($data) {
                    return (number_format($data->amount_commitment_first_stage));
                })
                ->addColumn('first_stage_payment', function ($data) {
                    return (number_format($data->first_stage_payment));
                })
                ->addColumn('amount_commitment_second_stage', function ($data) {
                    return (number_format($data->amount_commitment_second_stage));
                })
                ->addColumn('second_stage_payment', function ($data) {
                    return (number_format($data->second_stage_payment));
                })
                ->addColumn('amount_commitment_third_stage', function ($data) {
                    return (number_format($data->amount_commitment_third_stage));
                })
                ->addColumn('third_stage_payment', function ($data) {
                    return (number_format($data->third_stage_payment));
                })
                ->addColumn('amount_commitment_fourth_stage', function ($data) {
                    return (number_format($data->amount_commitment_fourth_stage));
                })
                ->addColumn('fourth_stage_payment', function ($data) {
                    return (number_format($data->fourth_stage_payment));
                })
                ->addColumn('amount_commitment_fifth_stage', function ($data) {
                    return (number_format($data->amount_commitment_fifth_stage));
                })
                ->addColumn('fifth_stage_payment', function ($data) {
                    return (number_format($data->fifth_stage_payment));
                })
                ->addColumn('commitment_balance', function ($data) {
                    return (number_format($data->first_stage_payment + $data->second_stage_payment + $data->third_stage_payment + $data->fourth_stage_payment + $data->fifth_stage_payment - $data->amount_request_accept));
                })
                ->editColumn('action', function ($data) {
                    $actionBtn = '';
                    if (auth()->user()->can('can-access', ['project', 'edit'])) {
                        $actionBtn .= '<button type="button" data-bs-toggle="modal" data-bs-target="#editModal'.$data->id.'" class="btn btn-sm btn-icon btn-outline-primary mx-1"><i class="mdi mdi-pencil-outline"></i></button>';
                    }
                    if (auth()->user()->can('can-access', ['project', 'delete'])) {
                        $actionBtn .= '<button class="btn btn-sm btn-icon btn-outline-danger mx-1 delete-btn" data-id="'.$data->id.'"><i class="mdi mdi-delete-outline"></i></button>';
                    }
                        $actionBtn .= '<button class="btn btn-sm btn-icon btn-eye mx-1" data-bs-toggle="modal" data-bs-target="#showModal'.$data->id.'"><i class="mdi mdi-eye"></i></button>';

                        $actionBtn .= '<button class="btn btn-sm btn-icon btn-image mx-1 upload-btn" data-id="'.$data->id.'"><i class="mdi mdi-file-document-multiple-outline"></i></button>';

                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('panel.project')->with(compact(['thispage' , 'submenupanels' , 'menupanels' , 'projects' , 'finances']));
    }

    public function store(Request $request)
    {
        try {
            $project = new Project();
            $project->commercial_name               = $request->input('commercial_name');
            $project->company_name                  = $request->input('company_name');
            $project->CEO                           = $request->input('CEO');
            $project->portfo_status                 = $request->input('portfo_status');
            $project->flow_level                    = $request->input('flow_level');
            $project->progress_percentage           = $request->input('progress_percentage');
            $project->activity_status               = $request->input('activity_status');
            $project->start_date                    = $request->input('start_date');
            $project->amount_request_accept         = $request->input('amount_request_accept');
            $project->amount_deposited              = $request->input('amount_deposited');
            $project->amount_commitment_first_stage = $request->input('amount_commitment_first_stage');
            $project->amount_commitment_second_stage= $request->input('amount_commitment_second_stage');
            $project->amount_commitment_third_stage = $request->input('amount_commitment_third_stage');
            $project->amount_commitment_fourth_stage= $request->input('amount_commitment_fourth_stage');
            $project->amount_commitment_fifth_stage = $request->input('amount_commitment_fifth_stage');
            $project->commitment_balance            = $request->input('commitment_balance');
            $project->description                   = $request->input('description');

            $result = $project->save();

            if ($result == true) {
                $success = true;
                $flag    = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات زیرمنو با موفقیت ثبت شد';
            }
            elseif($result != true) {
                $success = false;
                $flag    = 'error';
                $subject = 'عملیات نا موفق';
                $message = 'اطلاعات زیرمنو ثبت نشد، لطفا مجددا تلاش نمایید';
            }

        } catch (Exception $e) {

            $success = false;
            $flag    = 'error';
            $subject = 'خطا در ارتباط با سرور';
            //$message = strchr($e);
            $message = 'اطلاعات زیرمنو ثبت نشد،لطفا بعدا مجدد تلاش نمایید ';
        }

        return response()->json(['success'=>$success , 'subject' => $subject, 'flag' => $flag, 'message' => $message]);

//        return redirect(route('menudashboards.index'));

    }

    public function update(Request $request , $id)
    {

        $project = Project::findOrfail($id);
        $project->title                         = $request->input('title');
        $project->company_name                  = $request->input('company_name');
        $project->CEO                           = $request->input('CEO');
        $project->portfo_status                 = $request->input('portfo_status');
        $project->flow_level                    = $request->input('flow_level');
        $project->progress_percentage           = $request->input('progress_percentage');
        $project->activity_status               = $request->input('activity_status');
        $project->start_date                    = $request->input('start_date');
        $project->amount_request_accept         = $request->filled('amount_request_accept')          ? str_replace(',', '', $request->input('amount_request_accept'))          : null;
        $project->amount_deposited              = $request->filled('amount_deposited')               ? str_replace(',', '', $request->input('amount_deposited'))               : null;
        $project->amount_commitment_first_stage = $request->filled('amount_commitment_first_stage')  ? str_replace(',', '', $request->input('amount_commitment_first_stage'))  : null;
        $project->amount_commitment_second_stage= $request->filled('amount_commitment_second_stage') ? str_replace(',', '', $request->input('amount_commitment_second_stage')) : null;
        $project->amount_commitment_third_stage = $request->filled('amount_commitment_third_stage')  ? str_replace(',', '', $request->input('amount_commitment_third_stage'))  : null;
        $project->amount_commitment_fourth_stage= $request->filled('amount_commitment_fourth_stage') ? str_replace(',', '', $request->input('amount_commitment_fourth_stage')) : null;
        $project->amount_commitment_fifth_stage = $request->filled('amount_commitment_fifth_stage')  ? str_replace(',', '', $request->input('amount_commitment_fifth_stage'))  : null;
        $project->commitment_balance            = $request->filled('commitment_balance')             ? str_replace(',', '', $request->input('commitment_balance'))             : null;
        $project->logo                          = $request->input('logo');
        $project->description                   = $request->input('description');

        $result = $project->update();
        try{
            if ($result == true) {
                $success = true;
                $flag    = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات با موفقیت ثبت شد';
            }
            else {
                $success = false;
                $flag    = 'error';
                $subject = 'عملیات نا موفق';
                $message = 'اطلاعات ثبت نشد، لطفا مجددا تلاش نمایید';
            }

        } catch (Exception $e) {

            $success = false;
            $flag    = 'error';
            $subject = 'خطا در ارتباط با سرور';
            //$message = strchr($e);
            $message = 'اطلاعات ثبت نشد،لطفا بعدا مجدد تلاش نمایید ';
        }

        return response()->json(['success'=>$success , 'subject' => $subject, 'flag' => $flag, 'message' => $message]);
    }

    public function destroy(Request $request)
    {
        try {
            $project = Project::findOrfail($request->input('id'));
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
