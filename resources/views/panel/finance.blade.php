@extends('layouts.base')
@section('title', 'لیست دریافت و پرداخت')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
    <link rel="stylesheet" href="{{'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'}}" />
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
                    <form action="{{route(request()->segment(2).'.'.'store')}}" id="addform" method="POST">
                        {{csrf_field()}}
                            <div class="col-6 col-md-3">
                                <div class="form-floating form-floating-outline">
                                    <select required name="project_id" id="project_id" class="form-control select-lg select2">
                                        <option value="" selected>انتخاب کنید</option>
                                        @foreach($projects as $project)
                                            <option value="{{$project->id}}">{{$project->company_name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="serial">نام شرکت</label>
                                    <div class="invalid-feedback" id="serialFeedback">نام شرکت اجباری می باشد.</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-floating form-floating-outline">
                                    <input required type="text" class="form-control" id="serial" name="serial" placeholder="شماره مرحله پرداخت" >
                                    <label for="serial">شماره مرحله پرداخت</label>
                                    <div class="invalid-feedback" id="serialFeedback">شماره مرحله پرداخت اجباری می باشد.</div>
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
                                    <input required type="text" class="form-control" id="amount" name="amount" placeholder="مبلغ پرداختی" >
                                    <label for="amount">مبلغ پرداختی</label>
                                    <div class="invalid-feedback" id="amountFeedback">مبلغ پرداختی اجباری می باشد.</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-floating form-floating-outline">
                                    <input required type="text" class="form-control" id="date" name="date" placeholder="تاریخ واریز" >
                                    <label for="date">تاریخ واریز</label>
                                    <div class="invalid-feedback" id="dateFeedback">تاریخ واریز اجباری می باشد.</div>
                                </div>
                            </div>
                        <div class="text-end">
                            <button type="button" id="submit" class="btn btn-primary">ذخیره اطلاعات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    @foreach($finances as $finance)
        <div class="modal fade" id="editModal{{$finance->id}}" tabindex="-1" aria-labelledby="editModalLabel{{$finance->id}}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{$finance->id}}">{{$thispage['edit']}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editform_{{ $finance->id }}" method="POST" action="{{ route(request()->segment(2).'.update', $finance->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="menu_id" id="menu_id_{{$finance->id}}" value="{{$finance->id}}" />
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">نام شرکت</label>
                                    <select name="project_id" id="project_id" class="form-control select-lg select2">
                                        <option value="" selected>انتخاب کنید</option>
                                        @foreach($projects as $project)
                                            <option value="{{$project->id}}" {{$project->id == $finance->project_id ? 'selected' : ''}}>{{$project->company_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">شماره قسط</label>
                                    <input type="text" name="serial" id="serial" class="form-control" value="{{$finance->serial}}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">شماره سند</label>
                                    <input type="text" name="docserial" id="docserial" class="form-control" value="{{$finance->docserial}}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">تاریخ</label>
                                    <input type="text" name="date" id="date" class="form-control" value="{{$finance->date}}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">مبلغ</label>
                                    <input type="text" name="amount" id="amount" class="form-control" value="{{$finance->amount}}">
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" id="editsubmit_{{$finance->id}}" class="btn btn-primary" >ذخیره اطلاعات</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
@section('script')
    <script src="{{asset('assets/vendor/js/dataTables.min.js')}}"></script>
    <script src="{{asset('assets/vendor/js/sweetalert2.js')}}"></script>

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
        jQuery(function($){
            // ✅ تابع نهایی showToast با toastr.js
            function showToast(message, type = 'success') {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 3000,
                    rtl: true
                };

                if (toastr[type]) {
                    toastr[type](message);
                } else {
                    toastr.success(message);
                }
            }

            // 👇 منطق ارسال فرم بدون تغییر
            $('#submit').on('click', function(e){
                e.preventDefault();
                var $btn  = $(this);
                var $form = $('#addform');
                var originalHtml = $btn.html();

                $btn.prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route(request()->segment(2).'.store') }}",
                    method: 'POST',
                    data: $form.serialize(),
                    success: function (data) {
                        if (data.success) {
                            const modalEl = document.getElementById('addModal');
                            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);

                            modalEl.addEventListener('hidden.bs.modal', function handler(){
                                modalEl.removeEventListener('hidden.bs.modal', handler);
                                $('.yajra-datatable').DataTable().ajax.reload(null, false);
                            }, { once: true });

                            modal.hide();
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            $('body').css('padding-right', '');
                            showToast('آیتم با موفقیت افزوده شد!', 'success');
                        } else {
                            swal(data.subject, data.message, data.flag);
                        }
                    },
                    error: function () {
                        swal('خطا', 'مشکلی پیش آمد. لطفاً دوباره تلاش کنید.', 'error');
                    },
                    complete: function () {
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });
        });
    </script>
    <script>
        jQuery(function($){
            function showToast(message, type = 'success') {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-center",
                    timeOut: 3000,
                    rtl: true
                };

                if (toastr[type]) {
                    toastr[type](message);
                } else {
                    toastr.success(message);
                }
            }

            // 🚫 هیچ چیز دیگه‌ای تغییر نکرده، فقط از تابع بالا استفاده شده
            $(document).on('click', '[id^=editsubmit_]', function(e){
                e.preventDefault();
                const $btn = $(this);
                const id = this.id.split('_')[1];
                const $form = $('#editform_' + id);

                if (!$form.length) {
                    console.error('فرم editform_' + id + ' پیدا نشد!');
                    return;
                }

                const url = $form.attr('action'); // استفاده از URL داینامیک
                const originalHtml = $btn.html();
                disableBtnWithSpinner($btn);

                $.ajax({
                    url: url,
                    method: 'PATCH',
                    data: $form.serialize(),
                    success: function (data) {
                        if (data.success) {
                            const modalEl = document.getElementById('editModal' + id);
                            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                            modalEl.addEventListener('hidden.bs.modal', function handler(){
                                modalEl.removeEventListener('hidden.bs.modal', handler);
                                $('.yajra-datatable').DataTable().ajax.reload(null, false);
                                showToast('آیتم با موفقیت ویرایش شد!', 'success');
                            }, { once: true });
                            modal.hide();
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open').css('padding-right', '');
                        } else {
                            swal(data.subject, data.message, data.flag);
                        }
                    },
                    error: function () {
                        swal('خطا', 'مشکلی پیش آمد. لطفاً دوباره تلاش کنید.', 'error');
                    },
                    complete: function () {
                        restoreBtn($btn, originalHtml);
                    }
                });
            });

            function disableBtnWithSpinner($btn){
                $btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...'
                );
            }
            function restoreBtn($btn, html){
                $btn.prop('disabled', false).html(html);
            }
        });
    </script>

@endsection
