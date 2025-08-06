<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use App\Models\MenuPanel;
use App\Models\SubmenuPanel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReceiveController extends Controller
{
    public function index(Request $request)
    {

        $submenupanels  = SubmenuPanel::select('id','priority','title','label','menu_id','slug','status','class','controller')->get();
        $menupanels     = Menupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller')->get();
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
            $data = Finance::all();
            return Datatables::of($data)
                ->addColumn('serial', function ($data) {
                    return ($data->serial);
                })
                ->addColumn('company_name', function ($data) {
                    return ($data->company_name);
                })
                ->addColumn('reason', function ($data) {
                    return ($data->reason);
                })
                ->addColumn('amount', function ($data) {
                    return ($data->amount);
                })
                ->editColumn('action', function ($data) {
                    $actionBtn = '<button type="button" data-bs-toggle="modal" data-bs-target="#editModal'.$data->id.'" class="btn btn-sm btn-icon btn-outline-primary" ><i class="mdi mdi-pencil-outline"></i></button>
                    <button class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal'.$data->id.'"><i class="mdi mdi-delete-outline"></i></button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('panel.receivemanage')->with(compact(['thispage' , 'submenupanels' , 'menupanels' , 'finances']));
    }

}
