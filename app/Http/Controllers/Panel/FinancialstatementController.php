<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Financial_statement;
use App\Models\MenuPanel;
use App\Models\Project;
use App\Models\SubmenuPanel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FinancialstatementController extends Controller
{
    public function index(Request $request){
        $thispage       = [
            'title'   => 'مدیریت صورت وضعیت',
            'list'    => 'لیست صورت وضعیت',
            'add'     => 'افزودن صورت وضعیت',
            'create'  => 'ایجاد صورت وضعیت',
            'enter'   => 'ورود صورت وضعیت',
            'edit'    => 'ویرایش صورت وضعیت',
            'delete'  => 'حذف صورت وضعیت',
        ];
        $menupanels             = Menupanel::select('id','priority','icon', 'title','label', 'slug', 'status' , 'class' , 'controller')->get();
        $submenupanels          = Submenupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller' , 'menu_id')->get();
        $financialstatements    = Financial_statement::all();
        $projects               = Project::where('invest_step' , '<>', 0)->get();

        if ($request->ajax()) {
            $data = DB::table('financial_statements as f')
                ->leftJoin('projects as p', 'p.id', '=', 'f.project_id')
                ->select('f.id', 'f.project_id', 'p.company_name', 'p.title', 'f.net_sales', 'f.operating_revenue', 'f.cogs_goods', 'f.cogs_services', 'f.gross_profit', 'f.selling_general_admin_expense', 'f.operating_loss', 'f.financial_expense', 'f.other_income', 'f.non_operating_net', 'f.profit_before_tax', 'f.income_tax_expense', 'f.net_profit', 'f.tangible_fixed_assets', 'f.intangible_assets', 'f.other_assets', 'f.total_non_current_assets', 'f.prepayments', 'f.inventory', 'f.trade_receivables', 'f.other_receivables', 'f.cash_and_equivalents', 'f.total_current_assets', 'f.total_assets', 'f.capital', 'f.capital_in_progress', 'f.legal_reserve', 'f.retained_earnings', 'f.total_equity', 'f.long_term_rnd_payable', 'f.long_term_loans', 'f.employee_benefit_reserve', 'f.total_non_current_liabilities', 'f.trade_payables', 'f.tax_payable', 'f.short_term_loans', 'f.advances_received', 'f.total_current_liabilities', 'f.total_liabilities', 'f.total_equity_and_liabilities', 'f.created_at', 'f.updated_at')
                ->get();

            return Datatables::of($data)

                ->addColumn('company_name'      , fn ($data) => $data->company_name)
                ->addColumn('net_sales'         , fn ($data) => number_format($data->net_sales))
                ->addColumn('operating_revenue' , fn ($data) => number_format($data->operating_revenue))
                ->addColumn('cogs_goods'        , fn ($data) => number_format($data->cogs_goods))
                ->addColumn('cogs_services'     , fn ($data) => number_format($data->cogs_services))
                ->addColumn('gross_profit'      , fn ($data) => number_format($data->gross_profit))
                ->addColumn('selling_general_admin_expense', fn ($data) => number_format($data->selling_general_admin_expense))
                ->addColumn('operating_loss'    , fn ($data) => number_format($data->operating_loss))
                ->addColumn('financial_expense' , fn ($data) => number_format($data->financial_expense))
                ->addColumn('other_income'      , fn ($data) => number_format($data->other_income))
                ->addColumn('non_operating_net' , fn ($data) => number_format($data->non_operating_net))
                ->addColumn('profit_before_tax' , fn ($data) => number_format($data->profit_before_tax))
                ->addColumn('income_tax_expense', fn ($data) => number_format($data->income_tax_expense))
                ->addColumn('net_profit'        , fn ($data) => number_format($data->net_profit))

                ->addColumn('tangible_fixed_assets', fn ($data) => number_format($data->tangible_fixed_assets))
                ->addColumn('intangible_assets' , fn ($data) => number_format($data->intangible_assets))
                ->addColumn('other_assets'      , fn ($data) => number_format($data->other_assets))
                ->addColumn('total_non_current_assets', fn ($data) => number_format($data->total_non_current_assets))

                ->addColumn('prepayments'       , fn ($data) => number_format($data->prepayments))
                ->addColumn('inventory'         , fn ($data) => number_format($data->inventory))
                ->addColumn('trade_receivables' , fn ($data) => number_format($data->trade_receivables))
                ->addColumn('other_receivables' , fn ($data) => number_format($data->other_receivables))
                ->addColumn('cash_and_equivalents', fn ($data) => number_format($data->cash_and_equivalents))
                ->addColumn('total_current_assets', fn ($data) => number_format($data->total_current_assets))
                ->addColumn('total_assets'      , fn ($data) => number_format($data->total_assets))

                ->addColumn('capital'           , fn ($data) => number_format($data->capital))
                ->addColumn('capital_in_progress', fn ($data) => number_format($data->capital_in_progress))
                ->addColumn('legal_reserve'     , fn ($data) => number_format($data->legal_reserve))
                ->addColumn('retained_earnings' , fn ($data) => number_format($data->retained_earnings))
                ->addColumn('total_equity'      , fn ($data) => number_format($data->total_equity))

                ->addColumn('long_term_rnd_payable', fn ($data) => number_format($data->long_term_rnd_payable))
                ->addColumn('long_term_loans'   , fn ($data) => number_format($data->long_term_loans))
                ->addColumn('employee_benefit_reserve', fn ($data) => number_format($data->employee_benefit_reserve))
                ->addColumn('total_non_current_liabilities', fn ($data) => number_format($data->total_non_current_liabilities))

                ->addColumn('trade_payables'    , fn ($data) => number_format($data->trade_payables))
                ->addColumn('tax_payable'       , fn ($data) => number_format($data->tax_payable))
                ->addColumn('short_term_loans'  , fn ($data) => number_format($data->short_term_loans))
                ->addColumn('advances_received' , fn ($data) => number_format($data->advances_received))
                ->addColumn('total_current_liabilities', fn ($data) => number_format($data->total_current_liabilities))

                ->addColumn('total_liabilities' , fn ($data) => number_format($data->total_liabilities))
                ->addColumn('total_equity_and_liabilities', fn ($data) => number_format($data->total_equity_and_liabilities))

                ->editColumn('action', function ($data) {
                    $base = 'btn btn-sm btn-icon rounded-pill waves-effect mx-1';

                    $actionBtn = '';
                    if (auth()->user()->can('can-access', ['financialstatement', 'edit'])) {
                        $actionBtn .= '<button type="button" class="'.$base.' btn btn-sm btn-outline-primary edit-btn" data-id="'.$data->id.'" data-url="'.route('financialstatement.edit', $data->id).'"><i class="mdi mdi-pencil-outline"></i></button>';

                    }
                    if (auth()->user()->can('can-access', ['financialstatement', 'delete'])) {
                        $actionBtn .= '<button type="button" class="'.$base.' btn btn-sm btn-icon btn-outline-danger mx-1 delete-btn" data-id="'.$data->id.'"><i class="mdi mdi-delete-outline"></i></button>';
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('panel.financialstatement')->with(compact(['menupanels' , 'submenupanels' , 'financialstatements','thispage' , 'projects']));
    }

    public function store(Request $request)
    {
            try {
                $financestatement = new Financial_statement();
                $financestatement->project_id = $request->project_id;

                $financestatement->net_sales            = $request->filled('net_sales') ? str_replace(',', '', $request->net_sales) : 0;
                $financestatement->operating_revenue    = $request->filled('operating_revenue') ? str_replace(',', '', $request->operating_revenue) : 0;
                $financestatement->cogs_goods           = $request->filled('cogs_goods') ? str_replace(',', '', $request->cogs_goods) : 0;
                $financestatement->cogs_services        = $request->filled('cogs_services') ? str_replace(',', '', $request->cogs_services) : 0;
                $financestatement->gross_profit         = $request->filled('gross_profit') ? str_replace(',', '', $request->gross_profit) : 0;
                $financestatement->selling_general_admin_expense = $request->filled('selling_general_admin_expense') ? str_replace(',', '', $request->selling_general_admin_expense) : 0;
                $financestatement->operating_loss       = $request->filled('operating_loss') ? str_replace(',', '', $request->operating_loss) : 0;
                $financestatement->financial_expense    = $request->filled('financial_expense') ? str_replace(',', '', $request->financial_expense) : 0;
                $financestatement->other_income         = $request->filled('other_income') ? str_replace(',', '', $request->other_income) : 0;
                $financestatement->non_operating_net    = $request->filled('non_operating_net') ? str_replace(',', '', $request->non_operating_net) : 0;
                $financestatement->profit_before_tax    = $request->filled('profit_before_tax') ? str_replace(',', '', $request->profit_before_tax) : 0;
                $financestatement->income_tax_expense   = $request->filled('income_tax_expense') ? str_replace(',', '', $request->income_tax_expense) : 0;
                $financestatement->net_profit           = $request->filled('net_profit') ? str_replace(',', '', $request->net_profit) : 0;

                $financestatement->tangible_fixed_assets = $request->filled('tangible_fixed_assets') ? str_replace(',', '', $request->tangible_fixed_assets) : 0;
                $financestatement->intangible_assets    = $request->filled('intangible_assets') ? str_replace(',', '', $request->intangible_assets) : 0;
                $financestatement->other_assets         = $request->filled('other_assets') ? str_replace(',', '', $request->other_assets) : 0;
                $financestatement->total_non_current_assets = $request->filled('total_non_current_assets') ? str_replace(',', '', $request->total_non_current_assets) : 0;

                $financestatement->prepayments          = $request->filled('prepayments') ? str_replace(',', '', $request->prepayments) : 0;
                $financestatement->inventory            = $request->filled('inventory') ? str_replace(',', '', $request->inventory) : 0;
                $financestatement->trade_receivables    = $request->filled('trade_receivables') ? str_replace(',', '', $request->trade_receivables) : 0;
                $financestatement->other_receivables    = $request->filled('other_receivables') ? str_replace(',', '', $request->other_receivables) : 0;
                $financestatement->cash_and_equivalents = $request->filled('cash_and_equivalents') ? str_replace(',', '', $request->cash_and_equivalents) : 0;
                $financestatement->total_current_assets = $request->filled('total_current_assets') ? str_replace(',', '', $request->total_current_assets) : 0;
                $financestatement->total_assets         = $request->filled('total_assets') ? str_replace(',', '', $request->total_assets) : 0;

                $financestatement->capital              = $request->filled('capital') ? str_replace(',', '', $request->capital) : 0;
                $financestatement->capital_in_progress  = $request->filled('capital_in_progress') ? str_replace(',', '', $request->capital_in_progress) : 0;
                $financestatement->legal_reserve        = $request->filled('legal_reserve') ? str_replace(',', '', $request->legal_reserve) : 0;
                $financestatement->retained_earnings    = $request->filled('retained_earnings') ? str_replace(',', '', $request->retained_earnings) : 0;
                $financestatement->total_equity         = $request->filled('total_equity') ? str_replace(',', '', $request->total_equity) : 0;

                $financestatement->long_term_rnd_payable = $request->filled('long_term_rnd_payable') ? str_replace(',', '', $request->long_term_rnd_payable) : 0;
                $financestatement->long_term_loans      = $request->filled('long_term_loans') ? str_replace(',', '', $request->long_term_loans) : 0;
                $financestatement->employee_benefit_reserve = $request->filled('employee_benefit_reserve') ? str_replace(',', '', $request->employee_benefit_reserve) : 0;
                $financestatement->total_non_current_liabilities = $request->filled('total_non_current_liabilities') ? str_replace(',', '', $request->total_non_current_liabilities) : 0;

                $financestatement->trade_payables       = $request->filled('trade_payables') ? str_replace(',', '', $request->trade_payables) : 0;
                $financestatement->tax_payable          = $request->filled('tax_payable') ? str_replace(',', '', $request->tax_payable) : 0;
                $financestatement->short_term_loans     = $request->filled('short_term_loans') ? str_replace(',', '', $request->short_term_loans) : 0;
                $financestatement->advances_received    = $request->filled('advances_received') ? str_replace(',', '', $request->advances_received) : 0;
                $financestatement->total_current_liabilities = $request->filled('total_current_liabilities') ? str_replace(',', '', $request->total_current_liabilities) : 0;

                $financestatement->total_liabilities    = $request->filled('total_liabilities') ? str_replace(',', '', $request->total_liabilities) : 0;
                $financestatement->total_equity_and_liabilities = $request->filled('total_equity_and_liabilities') ? str_replace(',', '', $request->total_equity_and_liabilities) : 0;

                $result = $financestatement->save();


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

                $success = false;
                $flag = 'error';
                $subject = 'خطا در ارتباط با سرور';
                //$message = strchr($e);
                $message = 'اطلاعات زیرمنو ثبت نشد،لطفا بعدا مجدد تلاش نمایید ';
            }

            return response()->json(['success' => $success, 'subject' => $subject, 'flag' => $flag, 'message' => $message]);

        }

    public function edit($id)
    {
        $financialstatements    = Financial_statement::findOrFail($id);
        $projects               = Project::where('invest_step' , '>=', 6)->get();

        return view('panel.partials.edit-form-financialstatment', compact('financialstatements', 'projects'));
    }

    public function destroy($id)
    {
        try {
            $financialstatement = Financial_statement::findOrfail($id);
            $result = $financialstatement->delete();

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
