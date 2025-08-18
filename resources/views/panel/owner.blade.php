@extends('layouts.base')

@section('title', 'اطلاعات کارفرما')
<link rel="stylesheet" href="{{ asset('https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css') }}"/>
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{$thispage['list']}}</h5>
                @if(!$owners)<a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">{{$thispage['add']}}</a>@endif
            </div>

            <div class="table-responsive">
                <style> table{margin: 0 auto;width: 100% !important;clear: both;border-collapse: collapse;table-layout: fixed;word-wrap:break-word;} .dt-layout-start{margin-right: 0 !important;} .dt-layout-end{margin-left: 0 !important;}</style>
                <table id="sample1" class="table table-striped table-bordered yajra-datatable">
                    <thead>
                    <tr class="table-light">
                        <th>عنوان شرکت</th>
                        <th>تلفن</th>
                        <th>موبایل</th>
                        <th>ایمیل</th>
                        <th>نام مدیرعامل</th>
                        <th>کد ملی شرکت</th>
                        <th>کد اقتصادی شرکت</th>
                        <th>تاریخ ثبت شرکت</th>
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
    @foreach($owners as $owner)
        <div class="modal fade" id="deleteModal{{$owner->id}}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title w-100" id="deleteModalLabel">{{$thispage['delete']}}</h5>
                        <button type="button" class="btn-close position-absolute start-0 mx-3" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        آیا از حذف این منو مطمئن هستید؟
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">انصراف</button>
                        <button type="button" class="btn btn-danger" id="deletesubmit_{{$owner->id}}" data-id="{{$owner->id}}">حذف</button>
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
                                <label class="form-label">عنوان شرکت</label>
                                <input type="text" name="title" id="title" data-required="1" placeholder="عنوان شرکت را وارد کنید" class="form-control" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">شماره تماس</label>
                                <input type="text" name="tel" id="tel" data-required="1" placeholder="شماره تماس را وارد کنید" class="form-control" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">موبایل</label>
                                <input type="text" name="mobile" id="mobile" data-required="1" placeholder="موبایل را وارد کنید" class="form-control" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">ایمیل</label>
                                <input type="text" name="email" id="email" data-required="1" placeholder="ایمیل را وارد کنید" class="form-control" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">نام مدیرعامل</label>
                                <input type="text" name="ceo" id="ceo" data-required="1" placeholder="نام مدیرعامل را وارد کنید" class="form-control" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">کد ملی شرکت</label>
                                <input type="text" name="meli_code" id="meli_code" data-required="1" placeholder="کد ملی شرکت را وارد کنید" class="form-control" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">کد اقتصادی شرکت</label>
                                <input type="text" name="eghtesadi_code" id="eghtesadi_code" data-required="1" placeholder="کد اقتصادی شرکت را وارد کنید" class="form-control" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">تاریخ ثبت شرکت</label>
                                <input type="text" name="date_sabt" id="date_sabt" data-required="1" placeholder="تاریخ ثبت شرکت را وارد کنید" class="form-control" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">شبکه های اجتماعی شرکت</label>
                                <input type="text" name="social" id="social" data-required="1" placeholder="شبکه های اجتماعی را وارد به شکل instageram:bestagroup,linkedin:bestagroup کنید" class="form-control" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">توضیحات شرکت</label>
                                <textarea name="summery" id="summery" cols="30" rows="10" class="form-control"></textarea>
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
    @foreach($menupanels as $menupanel)
        <div class="modal fade" id="editModal{{$menupanel->id}}" tabindex="-1" aria-labelledby="editModalLabel{{$menupanel->id}}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{$menupanel->id}}">{{$thispage['edit']}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route(request()->segment(2).'.update' , $menupanel->id)}}" id="editform_{{$menupanel->id}}" method="POST">
                            {{csrf_field()}}
                            <input type="hidden" name="menu_id" id="menu_id_{{$menupanel->id}}" value="{{$menupanel->id}}" />
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">نام  منو داشبورد فارسی</label>
                                    <input type="text" name="label" id="label_{{$menupanel->id}}" value="{{$menupanel->label}}" class="form-control" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">نام  منو داشبورد</label>
                                    <input type="text" name="title" id="title_{{$menupanel->id}}" value="{{$menupanel->title}}" class="form-control" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">زیر  منو داشبورد</label>
                                    <select name="submenu" id="submenu_{{$menupanel->id}}" class="form-control">
                                        <option value="1" selected>دارد</option>
                                        <option value="0">ندارد</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">کلاس داشبورد</label>
                                    <input type="text" name="class" id="class_{{$menupanel->id}}" value="{{$menupanel->class}}"  class="form-control" />
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">کنترلر داشبورد</label>
                                    <input type="text" name="controller" id="controller_{{$menupanel->id}}"  value="{{$menupanel->controller}}" class="form-control" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">نمایش/عدم نمایش</label>
                                    <select name="status" id="status_{{$menupanel->id}}" class="form-control">
                                        <option value="4" >نمایش</option>
                                        <option value="0" >عدم نمایش</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" id="editsubmit_{{$menupanel->id}}" class="btn btn-primary" >ذخیره اطلاعات</button>
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
                ajax: "{{route(request()->segment(2).'.index')}}",
                columns: [
                    {data: 'title'          , name: 'title'         },
                    {data: 'tel'            , name: 'tel'           },
                    {data: 'mobile'         , name: 'mobile'        },
                    {data: 'email'          , name: 'email'         },
                    {data: 'ceo'            , name: 'ceo'           },
                    {data: 'meli_code'      , name: 'meli_code'     },
                    {data: 'eghtesadi_code' , name: 'eghtesadi_code'},
                    {data: 'date_sabt'      , name: 'date_sabt'     },
                    {data: 'social'         , name: 'social'        },
                    {data: 'summery'        , name: 'summery'       },
                    {data: 'action'         , name: 'action', orderable: true, searchable: true},
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
                        "_token"        : "{{ csrf_token() }}",
                        title           : jQuery('#title').val(),
                        tel             : jQuery('#tel').val(),
                        mobile          : jQuery('#mobile').val(),
                        email           : jQuery('#email').val(),
                        ceo             : jQuery('#ceo').val(),
                        meli_code       : jQuery('#meli_code').val(),
                        eghtesadi_code  : jQuery('#eghtesadi_code').val(),
                        date_sabt       : jQuery('#date_sabt').val(),
                        social          : jQuery('#social').val(),
                        summery         : jQuery('#summery').val(),
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

                // قفل کردن دکمه + گذاشتن اسپینر
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
                        "_token"        : "{{ csrf_token() }}",
                        title           : jQuery('#title_'          + id).val(),
                        tel             : jQuery('#tel_'            + id).val(),
                        mobile          : jQuery('#mobile_'         + id).val(),
                        email           : jQuery('#email_'          + id).val(),
                        ceo             : jQuery('#ceo_'            + id).val(),
                        meli_code       : jQuery('#meli_code_'      + id).val(),
                        eghtesadi_code  : jQuery('#eghtesadi_code_' + id).val(),
                        date_sabt       : jQuery('#date_sabt_'      + id).val(),
                        social          : jQuery('#social_'         + id).val(),
                        summery         : jQuery('#summery_'        + id).val(),
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

@endsection
