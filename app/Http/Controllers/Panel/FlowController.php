<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Commitment;
use App\Models\Company;
use App\Models\Finance;
use App\Models\Investstep;
use App\Models\MediaFile;
use App\Models\MenuPanel;
use App\Models\Project;
use App\Models\Project_step;
use App\Models\SubmenuPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FlowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $submenupanels  = SubmenuPanel::select('id','priority','title','label','menu_id','slug','status','class','controller')->get();
        $menupanels     = Menupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller')->get();
        $projects       = Project::all();
        $finances       = Finance::all();
        $companies      = Company::all();
        $investsteps    = Investstep::all();
        $files          = MediaFile::where('status' ,'!=' , 5)->get();

        $commitments    = Commitment::whereStatus(4)->get();

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
            $data = DB::table('projects as p')
                ->leftJoin('companies as c', 'p.company_id', '=', 'c.id')
                ->select(
                    'p.id',
                    'p.title',
                    'c.ceo_name as CEO',
                    'p.portfo_status',
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
                ->addColumn('portfo_status', function ($data) {
                    return ($data->portfo_status);
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
                    if (auth()->user()->can('can-access', ['project', 'edit'])) {
                        $actionBtn .= '<button type="button" data-bs-toggle="modal" data-bs-target="#editModal'.$data->id.'" class="btn btn-sm btn-icon btn-outline-primary mx-1"><i class="mdi mdi-pencil-outline"></i></button>';
                    }
                    if (auth()->user()->can('can-access', ['project', 'delete'])) {
                        $actionBtn .= '<button class="btn btn-sm btn-icon btn-outline-danger mx-1 delete-btn" data-id="'.$data->id.'"><i class="mdi mdi-delete-outline"></i></button>';
                    }
                    $actionBtn .= '<button class="btn btn-sm btn-icon btn-eye mx-1" data-bs-toggle="modal" data-bs-target="#showModal'.$data->id.'"><i class="mdi mdi-eye"></i></button>';

                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('panel.flow')->with(compact(['thispage' , 'submenupanels' , 'menupanels' , 'projects' , 'finances' , 'companies' , 'investsteps' , 'files' , 'commitments']));
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
        try {
            $step = new Project_step();
            $step->project_id   = $request->input('project_id');
            $step->title        = $request->input('step_title');
            $step->step_number  = $request->input('step_id');
            $step->description  = $request->input('description');
            $step->status       = $request->input('status');
            $step->user_id      = Auth::user()->id;

            $result = $step->save();

            $project = Project::whereId($request->input('project_id'))->first();
            $project->invest_step = intval($request->input('step_id')) + 1;
            $project->save();

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
