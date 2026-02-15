    <form data-type="update" data-id="{{ $finance->id }}"  class="row g-4 mb-4" method="POST" action="{{ route('financialstatement.update', $finance->id) }}">
        @csrf
        @method('PATCH')
        <input type="hidden" name="menu_id" id="menu_id_{{$finance->id}}" value="{{$finance->id}}" />
        <div class="col-6 col-md-3">
            <div class="form-floating form-floating-outline">
                <label for="project_id">نام شرکت</label>
                <select name="project_id" id="project_id" class="form-control select-lg select2">
                    <option value="" selected>انتخاب کنید</option>
                    @foreach($projects as $project)
                        <option value="{{$project->id}}" {{$project->id == $finance->project_id ? 'selected' : ''}}>{{$project->company_name}} - {{$project->title}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="form-floating form-floating-outline">
                <label for="serial">شماره مرحله پرداخت</label>
                <select name="serial" id="serial" class="form-control select-lg select2">
                    <option value="" selected>انتخاب کنید</option>
                    <option value="1" {{$finance->serial == 1 ? 'selected' : ''}}>1</option>
                    <option value="2" {{$finance->serial == 2 ? 'selected' : ''}}>2</option>
                    <option value="3" {{$finance->serial == 3 ? 'selected' : ''}}>3</option>
                    <option value="4" {{$finance->serial == 4 ? 'selected' : ''}}>4</option>
                    <option value="5" {{$finance->serial == 5 ? 'selected' : ''}}>5</option>
                </select>
            </div>
        </div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="net_sales" value="{{ number_format($finance->net_sales) }}"><label>فروش خالص</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="operating_revenue" value="{{ number_format($finance->operating_revenue) }}"><label>درآمدهای عملیاتی</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="cogs_goods" value="{{ number_format($finance->cogs_goods) }}"><label>بهای تمام شده کالای فروش رفته</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="cogs_services" value="{{ number_format($finance->cogs_services) }}"><label>بهای تمام شده خدمات</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="gross_profit" value="{{ number_format($finance->gross_profit) }}"><label>سود ناخالص</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="selling_general_admin_expense" value="{{ number_format($finance->selling_general_admin_expense) }}"><label>هزینه‌های فروش، اداری و عمومی</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="operating_loss" value="{{ number_format($finance->operating_loss) }}"><label>سود / زیان عملیاتی</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="financial_expense" value="{{ number_format($finance->financial_expense) }}"><label>هزینه‌های مالی</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="other_income" value="{{ number_format($finance->other_income) }}"><label>سایر درآمدها</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="non_operating_net" value="{{ number_format($finance->non_operating_net) }}"><label>خالص غیرعملیاتی</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="profit_before_tax" value="{{ number_format($finance->profit_before_tax) }}"><label>سود / زیان قبل از مالیات</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="income_tax_expense" value="{{ number_format($finance->income_tax_expense) }}"><label>هزینه مالیات</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="net_profit" value="{{ number_format($finance->net_profit) }}"><label>سود / زیان خالص</label></div></div>

        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="tangible_fixed_assets" value="{{ number_format($finance->tangible_fixed_assets) }}"><label>دارایی‌های ثابت مشهود</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="intangible_assets" value="{{ number_format($finance->intangible_assets) }}"><label>دارایی‌های نامشهود</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="other_assets" value="{{ number_format($finance->other_assets) }}"><label>سایر دارایی‌ها</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="total_non_current_assets" value="{{ number_format($finance->total_non_current_assets) }}"><label>جمع دارایی‌های غیرجاری</label></div></div>

        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="prepayments" value="{{ number_format($finance->prepayments) }}"><label>پیش‌پرداخت‌ها</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="inventory" value="{{ number_format($finance->inventory) }}"><label>موجودی مواد و کالا</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="trade_receivables" value="{{ number_format($finance->trade_receivables) }}"><label>دریافتنی‌های تجاری</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="other_receivables" value="{{ number_format($finance->other_receivables) }}"><label>سایر دریافتنی‌ها</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="cash_and_equivalents" value="{{ number_format($finance->cash_and_equivalents) }}"><label>موجودی نقد</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="total_current_assets" value="{{ number_format($finance->total_current_assets) }}"><label>جمع دارایی‌های جاری</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="total_assets" value="{{ number_format($finance->total_assets) }}"><label>جمع دارایی‌ها</label></div></div>

        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="capital" value="{{ number_format($finance->capital) }}"><label>سرمایه</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="capital_in_progress" value="{{ number_format($finance->capital_in_progress) }}"><label>سرمایه در جریان</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="legal_reserve" value="{{ number_format($finance->legal_reserve) }}"><label>اندوخته قانونی</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="retained_earnings" value="{{ number_format($finance->retained_earnings) }}"><label>سود / زیان انباشته</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="total_equity" value="{{ number_format($finance->total_equity) }}"><label>جمع حقوق مالکانه</label></div></div>

        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="long_term_rnd_payable" value="{{ number_format($finance->long_term_rnd_payable) }}"><label>پرداختنی بلندمدت تحقیق و توسعه</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="long_term_loans" value="{{ number_format($finance->long_term_loans) }}"><label>تسهیلات مالی بلندمدت</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="employee_benefit_reserve" value="{{ number_format($finance->employee_benefit_reserve) }}"><label>ذخیره مزایای پایان خدمت</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="total_non_current_liabilities" value="{{ number_format($finance->total_non_current_liabilities) }}"><label>جمع بدهی‌های غیرجاری</label></div></div>

        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="trade_payables" value="{{ number_format($finance->trade_payables) }}"><label>پرداختنی‌های تجاری</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="tax_payable" value="{{ number_format($finance->tax_payable) }}"><label>مالیات پرداختنی</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="short_term_loans" value="{{ number_format($finance->short_term_loans) }}"><label>تسهیلات مالی</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="advances_received" value="{{ number_format($finance->advances_received) }}"><label>پیش‌دریافت‌ها</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="total_current_liabilities" value="{{ number_format($finance->total_current_liabilities) }}"><label>جمع بدهی‌های جاری</label></div></div>

        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="total_liabilities" value="{{ number_format($finance->total_liabilities) }}"><label>جمع بدهی‌ها</label></div></div>
        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="total_equity_and_liabilities" value="{{ number_format($finance->total_equity_and_liabilities) }}"><label>جمع حقوق مالکانه و بدهی‌ها</label></div></div>


        <div class="text-center">
            <button type="submit" id="editsubmit_{{$finance->id}}" class="btn btn-primary" >ذخیره اطلاعات</button>
        </div>
    </form>
