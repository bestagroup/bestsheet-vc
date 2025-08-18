@extends('layouts.base')

@section('title', 'مدیریت پرداخت ها')
<link rel="stylesheet" href="{{ asset('https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css') }}"/>
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{$thispage['list']}}</h5>
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">{{$thispage['add']}}</a>
            </div>

            <div class="table-responsive">
                <style> table{margin: 0 auto;width: 100% !important;clear: both;border-collapse: collapse;table-layout: fixed;word-wrap:break-word;} .dt-layout-start{margin-right: 0 !important;} .dt-layout-end{margin-left: 0 !important;}</style>
                <table id="sample1" class="table table-striped table-bordered yajra-datatable">
                <thead>
                    <tr class="table-light">
                        <th>نام شرکت</th>
                        <th>مبلغ پرداختی</th>
                        <th>شماره چک/سند</th>
                        <th>تاریخ پرداخت</th>
                        <th>علت پرداخت</th>
                        <th>تغییر</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    @foreach($finances as $finance)
        <div class="modal fade" id="deleteModal{{$finance->id}}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title w-100" id="deleteModalLabel">{{$thispage['delete']}}</h5>
                        <button type="button" class="btn-close position-absolute start-0 mx-3" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        آیا از حذف این زیر منو مطمئن هستید؟
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">انصراف</button>
                        <button type="button" class="btn btn-danger" id="deletesubmit_{{$finance->id}}" data-id="{{$finance->id}}">حذف</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
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
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">نام پروژه</label>
                                <select name="project_id" id="project_id" class="form-control select-lg select2">
                                    <option value="" selected>انتخاب کنید</option>
                                    @foreach($projects as $project)
                                        <option value="{{$project->id}}">{{$project->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">مبلغ پرداختی</label>
                                <input type="text" name="amount" id="amount" class="form-control" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">شماره سند / شماره چک</label>
                                <input type="text" name="serial" id="serial" class="form-control" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">تاریخ پرداخت</label>
                                <input type="text" name="date" id="date" class="form-control" />
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">علت پرداخت</label>
                                <textarea name="description" id="description" class="form-control" cols="30" rows="10"></textarea>
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
                        <form action="{{route(request()->segment(2).'.update' , $finance->id)}}" id="editform_{{$finance->id}}" method="POST">
                            {{csrf_field()}}
                            <input type="hidden" name="rec_id" id="rec_id_{{$finance->id}}" value="{{$finance->id}}" />
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">نام پروژه</label>
                                    <select name="project_id" id="project_id_{{$finance->id}}" class="form-control select-lg select2">
                                        <option value="" selected>انتخاب کنید</option>
                                        @foreach($projects as $project)
                                            <option value="{{$project->id}}" {{$project->id == $finance->project_id ? 'selected' : '' }}>{{$project->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">مبلغ پرداختی</label>
                                    <input type="text" name="amount" id="amount_{{$finance->id}}" value="{{$finance->amount}}" class="form-control" />
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">شماره سند / شماره چک</label>
                                    <input type="text" name="serial" id="serial_{{$finance->id}}" value="{{$finance->serial}}" class="form-control" />
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">تاریخ واریز</label>
                                    <input type="text" name="date" id="date_{{$finance->id}}" value="{{$finance->date}}" class="form-control" />
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">علت پرداخت</label>
                                    <textarea name="description" id="description_{{$finance->id}}" class="form-control" cols="30" rows="10">{{$finance->description}}</textarea>
                                </div>
                                <div class="text-end">
                                    <button type="button" id="editsubmit_{{$finance->id}}" class="btn btn-primary" >ذخیره اطلاعات</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@section('script')
    <script src="{{ 'https://cdn.datatables.net/2.2.2/js/dataTables.min.js' }}"></script>

    <script type="text/javascript">
        $(function () {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route(request()->segment(2) . '.index') }}",
                columns: [
                    {data: 'title'       , name: 'title'},
                    {data: 'amount'      , name: 'amount'},
                    {data: 'serial'      , name: 'serial'},
                    {data: 'date'        , name: 'date'},
                    {data: 'description' , name: 'description'},
                    {data: 'action'      , name: 'action', orderable: true, searchable: true},
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.5/i18n/fa.json"
                }
            });
        });
    </script>
    <script>
        jQuery(document).ready(function(){
            jQuery('#submit').click(function(e){
                e.preventDefault();

                var button = jQuery(this);
                var originalButtonHtml = button.html();
                button.prop('disabled', true);
                button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                jQuery.ajax({
                    url: "{{route(request()->segment(2).'.'.'store')}}",
                    method: 'POST',
                    data: {
                        "_token"    : "{{ csrf_token() }}",
                        project_id  : jQuery('#project_id').val(),
                        amount      : jQuery('#amount').val(),
                        serial      : jQuery('#serial').val(),
                        date        : jQuery('#date').val(),
                        description : jQuery('#description').val(),
                    },
                    success: function (data) {
                        if(data.success == true){
                            // بستن مدال
                            var modal = bootstrap.Modal.getInstance(document.querySelector('#addModal'));
                            if (modal) modal.hide();
                            $('#addform')[0].reset();
                            $('.yajra-datatable').DataTable().ajax.reload(null, false);
                            //swal(data.subject, data.message, data.flag);

                        } else {
                            swal(data.subject, data.message, data.flag);
                        }
                    },
                    error: function () {
                        swal('خطا', 'مشکلی پیش آمد. لطفاً دوباره تلاش کنید.', 'error');
                    },
                    complete: function () {
                        button.prop('disabled', false);
                        button.html(originalButtonHtml);
                    }
                });
            });
        });
    </script>
    <script>
        jQuery(document).ready(function(){
            jQuery('[id^=editsubmit_]').click(function(e){
                e.preventDefault();
                var button = jQuery(this);
                var originalButtonHtml = button.html(); // متن اصلی دکمه رو ذخیره کن

                button.prop('disabled', true);
                button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...');

                var id = jQuery(this).attr('id').split('_')[1];

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                jQuery.ajax({
                    url: "{{ route(request()->segment(2).'.update' , 0) }}",
                    method: 'PATCH',
                    data: {
                        "_token"    : "{{ csrf_token() }}",
                        id          : jQuery('#rec_id_' + id).val(),
                        project_id  : jQuery('#project_id_' + id).val(),
                        amount      : jQuery('#amount_' + id).val(),
                        serial      : jQuery('#serial_' + id).val(),
                        date        : jQuery('#date_' + id).val(),
                        description : jQuery('#description_' + id).val(),
                    },
                    success: function (data) {
                        if(data.success == true){
                            // بستن مدال
                            var modalId = '#editModal' + id;
                            var modal = bootstrap.Modal.getInstance(document.querySelector(modalId)); // اینجا #myModal باید id مدال شما باشه
                            if (modal) modal.hide();
                            $('.yajra-datatable').DataTable().ajax.reload(null, false);
                            //swal(data.subject, data.message, data.flag);

                        } else {
                            swal(data.subject, data.message, data.flag);
                        }
                    },
                    error: function () {
                        swal('خطا', 'مشکلی پیش آمد. لطفاً دوباره تلاش کنید.', 'error');
                    },
                    complete: function () {

                        button.prop('disabled', false);
                        button.html(originalButtonHtml);
                    }
                });
            });
        });
    </script>
    <script>
        jQuery(document).ready(function(){
            jQuery('[id^=deletesubmit_]').click(function(e){
                e.preventDefault();

                var button = jQuery(this);
                var id = button.data('id');
                var originalButtonHtml = button.html(); // متن اصلی دکمه رو ذخیره کن

                // قفل کردن دکمه + گذاشتن اسپینر
                button.prop('disabled', true);
                button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال حذف...');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                jQuery.ajax({
                    url: "{{ route(request()->segment(2).'.destroy', 0) }}",
                    method: 'delete',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: id,
                    },
                    success: function (data) {
                        // مدال را ببند
                        var modalId = '#deleteModal' + id;
                        var modal = bootstrap.Modal.getInstance(document.querySelector(modalId));
                        modal.hide();

                        // جدول را رفرش کن
                        $('.yajra-datatable').DataTable().ajax.reload(null, false);
                    },
                    error: function () {
                        alert('مشکلی پیش آمد. لطفاً دوباره تلاش کنید.');
                    },
                    complete: function () {
                        // چه موفق باشه چه خطا بده، دکمه رو برگردون
                        button.prop('disabled', false);
                        button.html(originalButtonHtml);
                    }
                });
            });
        });
    </script>
    <script>
        document.getElementById('amount').addEventListener('input', function (e) {
            let value = e.target.value.replace(/,/g, '');
            if (!isNaN(value) && value.length > 0) {
                e.target.value = Number(value).toLocaleString('en-US');
            } else {
                e.target.value = '';
            }
        });
    </script>
@endsection
