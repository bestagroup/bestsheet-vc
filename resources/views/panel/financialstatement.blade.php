@extends('layouts.base')
@section('title', 'مدیریت طرح ها')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{$thispage['list']}}</h5>
                @if (auth()->user()->can('can-access', ['financialstatement', 'insert']))
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">{{$thispage['add']}}</a>
                @endif
            </div>
            <div class="table-responsive">
                <table id="financialstatement" class="table table-striped table-bordered yajra-datatable">
                    <thead>
                    <tr class="table-light">
                        <th>نام شرکت</th>
                        <th>سال و ماه دوره</th>
                        <th>فروش خالص</th>
                        <th>درآمدهای عملیاتی</th>
                        <th>بهای تمام شده کالای فروش رفته</th>
                        <th>بهای تمام شده خدمات</th>
                        <th>سود ناخالص</th>
                        <th>هزینه‌های فروش، اداری و عمومی</th>
                        <th>سود / زیان عملیاتی</th>
                        <th>هزینه‌های مالی</th>
                        <th>سایر درآمدها</th>
                        <th>خالص غیرعملیاتی</th>
                        <th>سود / زیان قبل از مالیات</th>
                        <th>هزینه مالیات</th>
                        <th>سود / زیان خالص</th>
                        <th>دارایی‌های ثابت مشهود</th>
                        <th>دارایی‌های نامشهود</th>
                        <th>سایر دارایی‌ها</th>
                        <th>جمع دارایی‌های غیرجاری</th>
                        <th>پیش‌پرداخت‌ها</th>
                        <th>موجودی مواد و کالا</th>
                        <th>دریافتنی‌های تجاری</th>
                        <th>سایر دریافتنی‌ها</th>
                        <th>موجودی نقد</th>
                        <th>جمع دارایی‌های جاری</th>
                        <th>جمع دارایی‌ها</th>
                        <th>سرمایه</th>
                        <th>سرمایه در جریان</th>
                        <th>اندوخته قانونی</th>
                        <th>سود / زیان انباشته</th>
                        <th>جمع حقوق مالکانه</th>
                        <th>پرداختنی بلندمدت تحقیق و توسعه</th>
                        <th>تسهیلات مالی بلندمدت</th>
                        <th>ذخیره مزایای پایان خدمت</th>
                        <th>جمع بدهی‌های غیرجاری</th>
                        <th>پرداختنی‌های تجاری</th>
                        <th>مالیات پرداختنی</th>
                        <th>تسهیلات مالی</th>
                        <th>پیش‌دریافت‌ها</th>
                        <th>جمع بدهی‌های جاری</th>
                        <th>جمع بدهی‌ها</th>
                        <th>جمع حقوق مالکانه و بدهی‌ها</th>
                        <th>تغییرات</th>
                    </tr>
                    </thead>
                    <tbody style="direction: ltr">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title w-100" id="deleteModalLabel">{{ $thispage['delete'] }}</h5>
                    <button type="button" class="btn-close position-absolute start-0 mx-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    آیا از حذف این زیر منو مطمئن هستید؟
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">حذف</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">{{$thispage['add']}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <form id="addform" data-type="create" method="POST" class="row g-4 mb-4"
                          action="{{ route(request()->segment(2).'.store') }}">
                        @csrf
                        <div class="col-12 col-md-3">
                            <div class="form-floating form-floating-outline">
                                <select required name="project_id" id="project_id" class="form-control select2">
                                    <option value="">انتخاب کنید</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->company_name }} - {{ $project->title }}</option>
                                    @endforeach
                                </select>
                                <label>پروژه</label>
                            </div>
                        </div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control " name="year" id="year"><label for="year">سال دوره</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control " name="month" id="month"><label for="month">ماه دوره</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="net_sales" id="net_sales"><label for="net_sales">فروش خالص</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="operating_revenue" id="operating_revenue"><label for="operating_revenue">درآمدهای عملیاتی</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="cogs_goods" id="cogs_goods"><label for="cogs_goods">بهای تمام شده کالای فروش رفته</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="cogs_services" id="cogs_services"><label for="cogs_services">بهای تمام شده خدمات</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="gross_profit" id="gross_profit"><label for="gross_profit">سود ناخالص</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="selling_general_admin_expense" id="selling_general_admin_expense"><label for="selling_general_admin_expense">هزینه‌های فروش، اداری و عمومی</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="operating_loss" id="operating_loss"><label for="operating_loss">سود / زیان عملیاتی</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="financial_expense" id="financial_expense"><label for="financial_expense">هزینه‌های مالی</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="other_income" id="other_income"><label for="other_income">سایر درآمدها</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="non_operating_net" id="non_operating_net"><label for="non_operating_net">خالص غیرعملیاتی</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="profit_before_tax" id="profit_before_tax"><label for="profit_before_tax">سود / زیان قبل از مالیات</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="income_tax_expense" id="income_tax_expense"><label for="income_tax_expense">هزینه مالیات</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="net_profit" id="net_profit"><label for="net_profit">سود / زیان خالص</label></div></div>

                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="tangible_fixed_assets" id="tangible_fixed_assets"><label for="tangible_fixed_assets">دارایی‌های ثابت مشهود</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="intangible_assets" id="intangible_assets"><label for="intangible_assets">دارایی‌های نامشهود</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="other_assets" id="other_assets"><label for="other_assets">سایر دارایی‌ها</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="total_non_current_assets" id="total_non_current_assets"><label for="total_non_current_assets">جمع دارایی‌های غیرجاری</label></div></div>

                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="prepayments" id="prepayments"><label for="prepayments">پیش‌پرداخت‌ها</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="inventory" id="inventory"><label for="inventory">موجودی مواد و کالا</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="trade_receivables" id="trade_receivables"><label for="trade_receivables">دریافتنی‌های تجاری</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="other_receivables" id="other_receivables"><label for="other_receivables">سایر دریافتنی‌ها</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="cash_and_equivalents" id="cash_and_equivalents"><label for="cash_and_equivalents">موجودی نقد</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="total_current_assets" id="total_current_assets"><label for="total_current_assets">جمع دارایی‌های جاری</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="total_assets" id="total_assets"><label for="total_assets">جمع دارایی‌ها</label></div></div>

                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="capital" id="capital"><label for="capital">سرمایه</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="capital_in_progress" id="capital_in_progress"><label for="capital_in_progress">سرمایه در جریان</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="legal_reserve" id="legal_reserve"><label for="legal_reserve">اندوخته قانونی</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="retained_earnings" id="retained_earnings"><label for="retained_earnings">سود / زیان انباشته</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="total_equity" id="total_equity"><label for="total_equity">جمع حقوق مالکانه</label></div></div>

                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="long_term_rnd_payable" id="long_term_rnd_payable"><label for="long_term_rnd_payable">پرداختنی بلندمدت تحقیق و توسعه</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="long_term_loans" id="long_term_loans"><label for="long_term_loans">تسهیلات مالی بلندمدت</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="employee_benefit_reserve" id="employee_benefit_reserve"><label for="employee_benefit_reserve">ذخیره مزایای پایان خدمت</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="total_non_current_liabilities" id="total_non_current_liabilities"><label for="total_non_current_liabilities">جمع بدهی‌های غیرجاری</label></div></div>

                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="trade_payables" id="trade_payables"><label for="trade_payables">پرداختنی‌های تجاری</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="tax_payable" id="tax_payable"><label for="tax_payable">مالیات پرداختنی</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="short_term_loans" id="short_term_loans"><label for="short_term_loans">تسهیلات مالی</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="advances_received" id="advances_received"><label for="advances_received">پیش‌دریافت‌ها</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="total_current_liabilities" id="total_current_liabilities"><label for="total_current_liabilities">جمع بدهی‌های جاری</label></div></div>

                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="total_liabilities" id="total_liabilities"><label for="total_liabilities">جمع بدهی‌ها</label></div></div>
                        <div class="col-6 col-md-3"><div class="form-floating form-floating-outline"><input type="text" class="form-control number-input" name="total_equity_and_liabilities" id="total_equity_and_liabilities"><label for="total_equity_and_liabilities">جمع حقوق مالکانه و بدهی‌ها</label></div></div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">ذخیره اطلاعات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">ویرایش اطلاعات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="editModalBody">
                    <div class="text-center text-muted py-5">در حال بارگذاری...</div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{asset('assets/vendor/js/dataTables.min.js')}}"></script>
    <script src="{{asset('assets/vendor/js/formhandler.js')}}"></script>

    @yield('filescript')

    <script type="text/javascript">
        $(function () {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, 'desc']],
                scrollX: true,
                scrollCollapse: true,
                ajax: "{{ route(request()->segment(2).'.index') }}",
                columns: [
                    { data: 'company_name'      , name: 'company_name' },
                    { data: 'year'              , name: 'year' },
                    { data: 'net_sales'         , name: 'net_sales' },
                    { data: 'operating_revenue' , name: 'operating_revenue' },
                    { data: 'cogs_goods'        , name: 'cogs_goods' },
                    { data: 'cogs_services'     , name: 'cogs_services' },
                    { data: 'gross_profit'      , name: 'gross_profit' },
                    { data: 'selling_general_admin_expense', name: 'selling_general_admin_expense' },
                    { data: 'operating_loss'    , name: 'operating_loss' },
                    { data: 'financial_expense' , name: 'financial_expense' },
                    { data: 'other_income'      , name: 'other_income' },
                    { data: 'non_operating_net' , name: 'non_operating_net' },
                    { data: 'profit_before_tax' , name: 'profit_before_tax' },
                    { data: 'income_tax_expense', name: 'income_tax_expense' },
                    { data: 'net_profit'        , name: 'net_profit' },
                    { data: 'tangible_fixed_assets', name: 'tangible_fixed_assets' },
                    { data: 'intangible_assets' , name: 'intangible_assets' },
                    { data: 'other_assets'      , name: 'other_assets' },
                    { data: 'total_non_current_assets', name: 'total_non_current_assets' },
                    { data: 'prepayments'       , name: 'prepayments' },
                    { data: 'inventory'         , name: 'inventory' },
                    { data: 'trade_receivables' , name: 'trade_receivables' },
                    { data: 'other_receivables' , name: 'other_receivables' },
                    { data: 'cash_and_equivalents', name: 'cash_and_equivalents' },
                    { data: 'total_current_assets', name: 'total_current_assets' },
                    { data: 'total_assets'      , name: 'total_assets' },
                    { data: 'capital'           , name: 'capital' },
                    { data: 'capital_in_progress', name: 'capital_in_progress' },
                    { data: 'legal_reserve'     , name: 'legal_reserve' },
                    { data: 'retained_earnings' , name: 'retained_earnings' },
                    { data: 'total_equity'      , name: 'total_equity' },
                    { data: 'long_term_rnd_payable', name: 'long_term_rnd_payable' },
                    { data: 'long_term_loans'   , name: 'long_term_loans' },
                    { data: 'employee_benefit_reserve', name: 'employee_benefit_reserve' },
                    { data: 'total_non_current_liabilities', name: 'total_non_current_liabilities' },
                    { data: 'trade_payables'    , name: 'trade_payables' },
                    { data: 'tax_payable'       , name: 'tax_payable' },
                    { data: 'short_term_loans'  , name: 'short_term_loans' },
                    { data: 'advances_received' , name: 'advances_received' },
                    { data: 'total_current_liabilities', name: 'total_current_liabilities' },
                    { data: 'total_liabilities' , name: 'total_liabilities' },
                    { data: 'total_equity_and_liabilities', name: 'total_equity_and_liabilities'},
                    { data: 'action'             , name: 'action', orderable: true, searchable: true},
                ],
                language: {
                    url: "{{ asset('assets/vendor/js/fa.json') }}"
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('input', function (e) {
                if (!e.target.matches('input.number-input')) return;
                const input = e.target;
                const cursorPos = input.selectionStart;
                const valueBefore = input.value;
                const isNegative = valueBefore.trim().startsWith('-');
                const digitsBeforeCursor = valueBefore
                    .slice(0, cursorPos)
                    .replace(/[^0-9]/g, '').length;
                let raw = valueBefore.replace(/[^0-9]/g, '');
                if (raw === '') {
                    input.value = isNegative ? '-' : '';
                    return;
                }
                let formatted = raw.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                if (isNegative) {
                    formatted = '-' + formatted;
                }
                input.value = formatted;
                let newCursorPos = 0;
                let digitsCount = 0;
                while (newCursorPos < formatted.length && digitsCount < digitsBeforeCursor) {
                    if (/\d/.test(formatted[newCursorPos])) digitsCount++;
                    newCursorPos++;
                }
                if (isNegative) newCursorPos++;
                input.setSelectionRange(newCursorPos, newCursorPos);
            });
        });
    </script>


@endsection
