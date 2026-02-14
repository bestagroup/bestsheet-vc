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
        @php
            $fields = [
                'net_sales',
                'operating_revenue',
                'cogs_goods',
                'cogs_services',
                'gross_profit',
                'selling_general_admin_expense',
                'operating_loss',
                'financial_expense',
                'other_income',
                'non_operating_net',
                'profit_before_tax',
                'income_tax_expense',
                'net_profit',

                'tangible_fixed_assets',
                'intangible_assets',
                'other_assets',
                'total_non_current_assets',

                'prepayments',
                'inventory',
                'trade_receivables',
                'other_receivables',
                'cash_and_equivalents',
                'total_current_assets',
                'total_assets',

                'capital',
                'capital_in_progress',
                'legal_reserve',
                'retained_earnings',
                'total_equity',

                'long_term_rnd_payable',
                'long_term_loans',
                'employee_benefit_reserve',
                'total_non_current_liabilities',

                'trade_payables',
                'tax_payable',
                'short_term_loans',
                'advances_received',
                'total_current_liabilities',

                'total_liabilities',
                'total_equity_and_liabilities',
            ];
        @endphp

        @foreach($fields as $field)
            <div class="col-6 col-md-3">
                <div class="form-floating form-floating-outline">
                    <input type="text"
                           class="form-control number-input"
                           id="{{ $field }}"
                           name="{{ $field }}"
                           value="{{ number_format($finance->$field) }}">
                    <label for="{{ $field }}">{{ __('finance.'.$field) }}</label>
                </div>
            </div>
        @endforeach

        <div class="text-center">
            <button type="submit" id="editsubmit_{{$finance->id}}" class="btn btn-primary" >ذخیره اطلاعات</button>
        </div>
    </form>
