@extends('layouts.base')
@section('title', 'لیست دریافت و پرداخت')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
    <link rel="stylesheet" href="{{'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'}}" />
    <link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">
    <script type="text/javascript" src="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>
    <style>
        jdp-container{z-index:99999999 !important;}
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{$thispage['list']}}</h5>

                @if (auth()->user()->can('can-access', ['finance', 'insert']))
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">{{$thispage['add']}}</a>
                @endif

            </div>

            <div class="table-responsive">
                <style> table{margin: 0 auto;width: 100% !important;clear: both;border-collapse: collapse;table-layout: fixed;word-wrap:break-word;} .dt-layout-start{margin-right: 0 !important;} .dt-layout-end{margin-left: 0 !important;}</style>
                <table id="sample1" class="table table-striped table-bordered yajra-datatable">
                    <thead>
                    <tr class="table-light">
                        <th>نام شرکت</th>
                        <th>نام تجاری طرح</th>
                        <th>مبلغ کل قرارداد</th>
                        <th>تاریخ قرارداد</th>
                        <th>شماره سند</th>
                        <th>مرحله پرداخت</th>
                        <th>تاریخ پرداخت</th>
                        <th>مبلغ پرداخت</th>
                        <th>تغییرات</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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
                    <form id="addform" data-type="create" method="POST" class="row g-4 mb-4" action="{{route(request()->segment(2).'.'.'store')}}">
                        @csrf
                            <div class="col-6 col-md-3">
                                <div class="form-floating form-floating-outline">
                                    <select required name="project_id" id="project_id" class="form-control select-lg select2">
                                        <option value="" selected>انتخاب کنید</option>
                                        @foreach($projects as $project)
                                            <option value="{{$project->id}}">{{$project->company_name .'-'. $project->title}}</option>
                                        @endforeach
                                    </select>
                                    <label for="serial">نام شرکت</label>
                                    <div class="invalid-feedback" id="serialFeedback">نام شرکت اجباری می باشد.</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-floating form-floating-outline">
                                    <label for="serial">شماره مرحله پرداخت</label>
                                    <select name="serial" id="serial" class="form-control select-lg select2">
                                        <option value="" selected>انتخاب کنید</option>
                                        <option value="1" >1</option>
                                        <option value="2" >2</option>
                                        <option value="3" >3</option>
                                        <option value="4" >4</option>
                                        <option value="5" >5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-floating form-floating-outline">
                                    <input required type="text" class="form-control" id="docserial" name="docserial" placeholder="شماره سند بایگانی مالی" >
                                    <label for="docserial">شماره سند بایگانی مالی</label>
                                    <div class="invalid-feedback" id="docserialFeedback">شماره سند بایگانی مالی اجباری می باشد.</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-floating form-floating-outline">
                                    <input required type="text" class="form-control number-input" id="amount" name="amount" placeholder="مبلغ پرداختی" >
                                    <label for="amount">مبلغ پرداختی</label>
                                    <div class="invalid-feedback" id="amountFeedback">مبلغ پرداختی اجباری می باشد.</div>
                                </div>
                            </div>
                        <div class="col-6 col-md-3">
                            <div class="form-floating form-floating-outline">
                                <input required type="text" data-jdp class="form-control" id="date" name="date" placeholder="تاریخ واریز" >
                                <label for="date">تاریخ واریز</label>
                                <div class="invalid-feedback" id="dateFeedback">تاریخ واریز اجباری می باشد.</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-9">
                            <div class="form-floating form-floating-outline">
                                <textarea name="description" id="description" required cols="30" rows="10" class="form-control" placeholder="توضیحات"></textarea>
                                <label for="description">توضیحات</label>
                                <div class="invalid-feedback" id="dateFeedback">توضیحات اجباری می باشد.</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">ذخیره اطلاعات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    @foreach($finances as $finance)
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
    @endforeach

@endsection
@section('script')
    <script src="{{asset('assets/vendor/js/dataTables.min.js')}}"></script>
    <script src="{{asset('assets/vendor/js/sweetalert2.js')}}"></script>
    <script src="{{asset('assets/vendor/js/formhandler.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route(request()->segment(2).'.index')}}",
                columns: [
                    {data: 'company_name'   , name: 'company_name'    },
                    {data: 'title'          , name: 'title'           },
                    {data: 'contract_amount', name: 'contract_amount' },
                    {data: 'contract_date'  , name: 'contract_date'   },
                    {data: 'installment'    , name: 'installment'     },
                    {data: 'serial'         , name: 'serial'          },
                    {data: 'date'           , name: 'date'            },
                    {data: 'amount'         , name: 'amount'          },
                    {data: 'action'         , name: 'action', orderable: true, searchable: true},
                ],
                language: {
                    url: "{{asset('assets/vendor/js/fa.json')}}"
                }
            });
        });
    </script>
    <script>
        jalaliDatepicker.startWatch();

        document.querySelector("[data-jdp-miladi-input]").addEventListener("jdp:change", function (e) {
            var miladiInput = document.getElementById(this.getAttribute("data-jdp-miladi-input"));
            if (!this.value) {
                miladiInput.value = "";
                return;
            }
            var date = this.value.split("/");
            miladiInput.value = jalali_to_gregorian(date[0], date[1], date[2]).join("/")
        });

        function jalali_to_gregorian(jy, jm, jd) {
            jy = Number(jy);
            jm = Number(jm);
            jd = Number(jd);
            var gy = (jy <= 979) ? 621 : 1600;
            jy -= (jy <= 979) ? 0 : 979;
            var days = (365 * jy) + ((parseInt(jy / 33)) * 8) + (parseInt(((jy % 33) + 3) / 4))
                + 78 + jd + ((jm < 7) ? (jm - 1) * 31 : ((jm - 7) * 30) + 186);
            gy += 400 * (parseInt(days / 146097));
            days %= 146097;
            if (days > 36524) {
                gy += 100 * (parseInt(--days / 36524));
                days %= 36524;
                if (days >= 365) days++;
            }
            gy += 4 * (parseInt((days) / 1461));
            days %= 1461;
            gy += parseInt((days - 1) / 365);
            if (days > 365) days = (days - 1) % 365;
            var gd = days + 1;
            var sal_a = [0, 31, ((gy % 4 == 0 && gy % 100 != 0) || (gy % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            var gm
            for (gm = 0; gm < 13; gm++) {
                var v = sal_a[gm];
                if (gd <= v) break;
                gd -= v;
            }
            return [gy, gm, gd];
        }
    </script>
    <script>
        document.querySelectorAll('.number-input').forEach(function(input) {
            input.addEventListener('input', function(e) {
                // حذف ویرگول‌های قبلی و کاراکترهای غیرعددی
                let value = e.target.value.replace(/,/g, '').replace(/\D/g, '');

                // فرمت سه‌رقمی
                if (value) {
                    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }

                e.target.value = value;
            });
        });
    </script>

@endsection
