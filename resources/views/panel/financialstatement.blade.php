@extends('layouts.base')
@section('title', 'مدیریت طرح ها')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    @php
        $fields = [
            'net_sales'             => 'فروش خالص',
            'operating_revenue'     => 'درآمدهای عملیاتی',
            'cogs_goods'            => 'بهای تمام شده کالای فروش رفته',
            'cogs_services'         => 'بهای تمام شده درآمدهای عملیاتی',
            'gross_profit'          => 'سود ناخالص',
            'selling_general_admin_expense' => 'هزینه‌های فروش، اداری و عمومی',
            'operating_loss'        => 'سود / زیان عملیاتی',
            'financial_expense'     => 'هزینه‌های مالی',
            'other_income'          => 'سایر درآمدها',
            'non_operating_net'     => 'خالص غیرعملیاتی',
            'profit_before_tax'     => 'سود / زیان قبل از مالیات',
            'income_tax_expense'    => 'هزینه مالیات',
            'net_profit'            => 'سود / زیان خالص',

            'tangible_fixed_assets' => 'دارایی‌های ثابت مشهود',
            'intangible_assets'     => 'دارایی‌های نامشهود',
            'other_assets'          => 'سایر دارایی‌ها',
            'total_non_current_assets' => 'جمع دارایی‌های غیرجاری',

            'prepayments'           => 'پیش‌پرداخت‌ها',
            'inventory'             => 'موجودی مواد و کالا',
            'trade_receivables'     => 'دریافتنی‌های تجاری',
            'other_receivables'     => 'سایر دریافتنی‌ها',
            'cash_and_equivalents'  => 'موجودی نقد',
            'total_current_assets'  => 'جمع دارایی‌های جاری',
            'total_assets'          => 'جمع دارایی‌ها',

            'capital'               => 'سرمایه',
            'capital_in_progress'   => 'سرمایه در جریان',
            'legal_reserve'         => 'اندوخته قانونی',
            'retained_earnings'     => 'سود / زیان انباشته',
            'total_equity'          => 'جمع حقوق مالکانه',

            'long_term_rnd_payable' => 'پرداختنی بلندمدت تحقیق و توسعه',
            'long_term_loans'       => 'تسهیلات مالی بلندمدت',
            'employee_benefit_reserve' => 'ذخیره مزایای پایان خدمت',
            'total_non_current_liabilities' => 'جمع بدهی‌های غیرجاری',

            'trade_payables'        => 'پرداختنی‌های تجاری',
            'tax_payable'           => 'مالیات پرداختنی',
            'short_term_loans'      => 'تسهیلات مالی',
            'advances_received'     => 'پیش‌دریافت‌ها',
            'total_current_liabilities' => 'جمع بدهی‌های جاری',

            'total_liabilities'     => 'جمع بدهی‌ها',
            'total_equity_and_liabilities' => 'جمع حقوق مالکانه و بدهی‌ها',
        ];
    @endphp
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{$thispage['list']}}</h5>
                @if (auth()->user()->can('can-access', ['financialstatement', 'insert']))
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">{{$thispage['add']}}</a>
                @endif
            </div>
            <div class="table-responsive">
                <table id="sample1" class="table table-striped table-bordered yajra-datatable">
                    <thead>
                    <tr class="table-light">
                        @foreach($fields as $name => $label)
                            <th>{{ $label }}</th>
                        @endforeach
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
                        @foreach($fields as $name => $label)
                            <div class="col-6 col-md-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control number-input" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $label }}">
                                    <label for="{{ $name }}">{{ $label }}</label>
                                </div>
                            </div>
                        @endforeach
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
                    { data: 'company_name', name: 'company_name' },

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
        //تبدیل اعداد با جدا کننده
        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('input', function (e) {
                if (!e.target.matches('input.numeric')) return;
                const input = e.target;

                const selStart = input.selectionStart;
                const rawBefore = input.value;
                const digitsLeft = rawBefore.slice(0, selStart).replace(/[^0-9]/g, '').length;

                let unformatted = rawBefore.replace(/[^0-9]/g, '');
                if (!unformatted) { input.value = ''; return; }

                const formatted = unformatted.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

                input.value = formatted;

                let pos = 0, digitsCount = 0;
                while (pos < formatted.length && digitsCount < digitsLeft) {
                    if (/\d/.test(formatted[pos])) digitsCount++;
                    pos++;
                }
                input.setSelectionRange(pos, pos);
            });
        });
    </script>

@endsection
