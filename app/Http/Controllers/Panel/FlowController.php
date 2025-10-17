<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Commitment;
use App\Models\Finance;
use App\Models\Investstep;
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
                    'p.amount_request_accept',
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
                    return ($data->flow_level);
                })
                ->addColumn('invest_step', function ($data) {
                    return (($data->invest_step * 100 ) / 20 . '%');
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
                ->editColumn('action', function ($data) {
                    $actionBtn = '';
                    if (auth()->user()->can('can-access', ['flow', 'edit'])) {
                        $actionBtn .= '<button type="button" class="btn btn-sm btn-outline-primary edit-btn" data-id="'.$data->id.'" data-url="'.route('flow.edit', $data->id).'"><i class="mdi mdi-pencil-outline"></i></button>';

                    }
                    if (auth()->user()->can('can-access', ['flow', 'delete'])) {
                        $actionBtn .= '<button type="button" class="btn btn-sm btn-icon btn-outline-danger mx-1 delete-btn" data-id="'.$data->id.'"><i class="mdi mdi-delete-outline"></i></button>';
                    }
                    $actionBtn .= '<button type="button" class="btn btn-sm btn-outline-primary show-btn" data-id="'.$data->id.'" data-url="'.route('flow.show', $data->id).'"><i class="mdi mdi-eye"></i></button>';

                    $actionBtn .= '<button class="btn btn-sm btn-icon btn-image mx-1 upload-btn" data-id="'.$data->id.'"><i class="mdi mdi-file-document-multiple-outline"></i></button>';

                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('panel.flow')->with(compact(['thispage' , 'submenupanels' , 'menupanels' , 'states' ,'cities']));
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
        $project = Project::findOrFail($id);
        $finances       = Finance::all();
        $states         = State::all();
        $cities         = City::all();
        $investsteps    = Investstep::whereStatus(4)->get();
        $files          = MediaFile::where('status' ,'!=' , 5)->get();
        $commitments    = Commitment::whereStatus(4)->get();

        return view('panel.partials.show-profile', compact('project', 'states', 'cities' , 'investsteps' , 'files' , 'commitments' , 'finances'));
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
