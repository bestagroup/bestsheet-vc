@extends('layouts.base')

@section('title', 'مدیریت زیرمنوهای سایت')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
    <link rel="stylesheet" href="{{'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'}}" />
@endsection
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
                        <th>ردیف</th>
                        <th>عنوان صفحه</th>
                        <th>آدرس صفحه</th>
                        <th>زیر منو</th>
                        <th>کلاس</th>
                        <th>کنترلر</th>
                        <th>وضعیت</th>
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
    @foreach($submenus as $submenu)
        <div class="modal fade" id="deleteModal{{$submenu->id}}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                        <button type="button" class="btn btn-danger" id="deletesubmit_{{$submenu->id}}" data-id="{{$submenu->id}}">حذف</button>
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
                            <div class="col-md-4">
                                <label class="form-label">عنوان صفحه</label>
                                <input type="text" name="title" id="title" data-required="1" placeholder="عنوان صفحه را وارد کنید" class="form-control" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">عنوان تب صفحه</label>
                                <input type="text" name="tab_title" id="tab_title" data-required="1" placeholder="عنوان تب صفحه را وارد کنید" class="form-control" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">عنوان درود صفحه</label>
                                <input type="text" name="page_title" id="page_title" data-required="1" placeholder="عنوان درود صفحه را وارد کنید" class="form-control" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">انتخاب صفحه</label>
                                <select name="menu_id" id="menu_id" class="form-control select-lg select2">
                                    <option value="" selected>انتخاب کنید</option>
                                    @foreach($menus as $menu)
                                        @if($menu->submenu == 1)
                                        <option value="{{$menu->id}}">{{$menu->title}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">نمایش/عدم نمایش</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="" selected>انتخاب کنید</option>
                                    <option value="4" >نمایش</option>
                                    <option value="0" >عدم نمایش</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">کلاس داشبورد</label>
                                <input type="text" name="class" id="class" data-required="1" placeholder="کلاس زیر صفحه را وارد کنید" class="form-control" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">کنترلر داشبورد</label>
                                <input type="text" name="controller" id="controller" data-required="1" placeholder="کلاس زیر صفحه را وارد کنید" class="form-control" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">کلمات کلیدی</label>
                                <input type="text" name="keyword" id="keyword" data-required="1" placeholder="کلمات کلیدی را وارد کنید" class="form-control" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">توضیحات صفحه</label>
                                <textarea name="description" id="description" cols="30" rows="10" class="form-control"></textarea>
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
    @foreach($submenus as $submenu)
        <div class="modal fade" id="editModal{{$submenu->id}}" tabindex="-1" aria-labelledby="editModalLabel{{$submenu->id}}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{$submenu->id}}">{{$thispage['edit']}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route(request()->segment(2).'.update' , $submenu->id)}}" id="editform_{{$submenu->id}}" method="POST">
                            {{csrf_field()}}
                            <input type="hidden" name="menu_id" id="menu_id_{{$submenu->id}}" value="{{$submenu->id}}" />
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">عنوان صفحه</label>
                                    <input type="text" name="title" id="title_{{$submenu->id}}" value="{{$submenu->title}}" class="form-control" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">عنوان تب صفحه</label>
                                    <input type="text" name="tab_title" id="tab_title_{{$submenu->id}}" value="{{$submenu->tab_title}}" data-required="1"  class="form-control" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">عنوان در صفحه</label>
                                    <input type="text" name="page_title" id="page_title_{{$submenu->id}}" value="{{$submenu->page_title}}" data-required="1"  class="form-control" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">انتخاب صفحه</label>
                                    <select name="menuid" id="menuid_{{$submenu->id}}" class="form-control select-lg select2">
                                        <option value="">انتخاب صفحه</option>
                                        @foreach($menus as $menu)
                                            @if($menu->submenu == 1)
                                            <option value="{{$menu->id}}" {{$submenu->menu_id == $menu->id ? 'selected' : '' }}>{{$menu->title}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">وضعیت نمایش</label>
                                    <select name="status" id="status_{{$submenu->id}}" class="form-control">
                                        <option value="4" {{$submenu->status == 4 ? 'selected' : '' }} >نمایش</option>
                                        <option value="0" {{$submenu->status == 0 ? 'selected' : '' }}>عدم نمایش</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">کلاس داشبورد</label>
                                    <input type="text" name="class" id="class_{{$submenu->id}}" value="{{$submenu->class}}"  class="form-control" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">کنترلر داشبورد</label>
                                    <input type="text" name="controller" id="controller_{{$submenu->id}}"  value="{{$submenu->controller}}" class="form-control" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">کلمات کلیدی</label>
                                    <input type="text" name="keyword" id="keyword_{{$submenu->id}}" value="{{$submenu->keyword}}" data-required="1" class="form-control" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">توضیحات صفحه</label>
                                    <textarea name="description" id="description_{{$submenu->id}}" cols="30" rows="10" class="form-control">{{$submenu->description}}</textarea>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" id="editsubmit_{{$submenu->id}}" class="btn btn-primary" >ذخیره اطلاعات</button>
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
    <script src="{{'https://cdn.jsdelivr.net/npm/sweetalert2@11'}}"></script>

    <script type="text/javascript">
        $(function () {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route(request()->segment(2) . '.index') }}",
                columns: [
                    {data: 'id'         , name: 'id'},
                    {data: 'title'      , name: 'title'},
                    {data: 'slug'       , name: 'slug'},
                    {data: 'menu'       , name: 'menu'},
                    {data: 'class'      , name: 'class'},
                    {data: 'controller' , name: 'controller'},
                    {data: 'status'     , name: 'status'},
                    {data: 'action'     , name: 'action', orderable: true, searchable: true},
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
                        title       : jQuery('#title').val(),
                        tab_title   : jQuery('#tab_title').val(),
                        page_title  : jQuery('#page_title').val(),
                        menu_id     : jQuery('#menu_id').val(),
                        class       : jQuery('#class').val(),
                        controller  : jQuery('#controller').val(),
                        submenu     : jQuery('#submenu').val(),
                        status      : jQuery('#status').val(),
                        keyword     : jQuery('#keyword').val(),
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
                        id          : jQuery('#menu_id_' + id).val(),
                        title       : jQuery('#title_' + id).val(),
                        tab_title   : jQuery('#tab_title_' + id).val(),
                        page_title  : jQuery('#page_title_' + id).val(),
                        menu_id     : jQuery('#menuid_' + id).val(),
                        class       : jQuery('#class_' + id).val(),
                        controller  : jQuery('#controller_' + id).val(),
                        submenu     : jQuery('#submenu_' + id).val(),
                        status      : jQuery('#status_' + id).val(),
                        keyword     : jQuery('#keyword_' + id).val(),
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
@endsection
