@extends('layouts.base')

@section('title', 'مدیریت نقش کاربران داشبورد')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
    <link rel="stylesheet" href="{{'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'}}" />
    <style>
        /* برای اینکه داخل مودال روی همه چیز دیده شود */
        .select2-container {
            z-index: 9999 !important;
        }

        /* ظرف اصلی چند انتخابی – شبیه input متریال، راست‌چین و مرتب */
        .select2-container--default .select2-selection--multiple {
            direction: rtl;
            text-align: right;
            min-height: 44px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 4px 6px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 4px;
            cursor: text;
        }

        /* خود input کوچک داخل فیلد */
        .select2-container--default .select2-search--inline .select2-search__field {
            margin-top: 0;
            padding: 4px 0;
            font-size: .85rem;
        }

        /* استایل هر آیتم انتخاب شده (chip) */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 2px 8px;
            margin: 2px 2px;
            border: none;
            font-size: .8rem;
            /* رنگ پس‌زمینه و متن از تم فعلی می‌آد */
            background-color: rgba(0,0,0,0.06);
            color: inherit;
        }

        /* دکمه حذف روی chip (x) */
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            margin-left: 4px;
            margin-right: 0;
            font-size: 1rem;
            line-height: 1;
        }

        /* در RTL ترتیب text و X را برعکس کن */
        .select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice {
            flex-direction: row-reverse;
        }

        .select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice__remove {
            margin-right: 4px;
            margin-left: 0;
            padding-left: 0;
            border-right: 1px;
        }

        /* Dropdown هم راست‌چین و تمیز */
        .select2-container--default .select2-results > .select2-results__options {
            direction: rtl;
            text-align: right;
            font-size: .85rem;
        }




        /* استایل آیتم‌های انتخاب‌شده (chip) */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;      /* متن سمت راست، ضربدر سمت چپ */
            flex-direction: row;
            border-radius: 999px;
            padding: 2px 10px;
            margin: 2px 3px;
            border: none;
            font-size: .85rem;
            background-color: rgba(0,0,0,0.06);
            color: inherit;
        }

        /* خود ضربدر (×) */
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            float: none;                     /* حذف float پیش‌فرض */
            position: static;                /* از حالت عجیب پیش‌فرض دربیاد */
            margin: 0 0 0 .4rem;             /* فاصله بین ضربدر و متن */
            padding: 0 .3rem 0 0;
            border: none !important;                    /* خط عمودی کنارش حذف بشه */
            border-right: 1px solid rgba(0,0,0,0.12); !important; /* خط جداساز ظریف مثل اسکرین دوم */
            font-size: .9rem;
            line-height: 1;
            color: inherit;
        }

        /* در حالت RTL، متن راست‌چین بماند */
        .select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice {
            text-align: right;
            padding-left: 2px;
        }
    </style>

@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{$thispage['list']}}</h5>
                @if(Gate::allows('can-access', ['roleuser', 'insert']))
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">{{$thispage['add']}}</a>
                @endif
            </div>

            <div class="table-responsive">
                <style> table{margin: 0 auto;width: 100% !important;clear: both;border-collapse: collapse;table-layout: fixed;word-wrap:break-word;} .dt-layout-start{margin-right: 0 !important;} .dt-layout-end{margin-left: 0 !important;}</style>
                <table id="sample1" class="table table-striped table-bordered yajra-datatable">
                    <thead>
                    <tr class="table-light">
                        <th>عنوان فارسی</th>
                        <th>عنوان انگلیسی</th>
                        <th>دسترسی</th>
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
    @foreach($roles as $role)
        <div class="modal fade" id="deleteModal{{$role->id}}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                        <button type="button" class="btn btn-danger" id="deletesubmit_{{$role->id}}" data-id="{{$role->id}}">حذف</button>
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
                                <label class="form-label">عنوان فارسی</label>
                                <input type="text" name="title_fa" id="title_fa" data-required="1" placeholder="عنوان فارسی را وارد کنید" class="form-control" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">عنوان انگلیسی</label>
                                <input type="text" name="title" id="title" data-required="1" placeholder="عنوان را وارد کنید" class="form-control" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">فعال/غیر فعال</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="" selected>انتخاب کنید</option>
                                    <option value="4" >فعال</option>
                                    <option value="0" >غیر فعال</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="button" id="submit" class="btn btn-primary">ذخیره اطلاعات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    @foreach($roles as $role)
        <div class="modal fade" id="editModal{{$role->id}}" tabindex="-1" aria-labelledby="editModalLabel{{$role->id}}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{$role->id}}">{{$thispage['edit']}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route(request()->segment(2).'.update' , $role->id)}}" id="editform_{{$role->id}}" method="POST">
                            {{csrf_field()}}
                            <input type="hidden" name="role_id" id="role_id_{{$role->id}}" value="{{$role->id}}" />
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">عنوان فارسی</label>
                                    <input type="text" name="title_fa" id="title_fa_{{$role->id}}" value="{{$role->title_fa}}" class="form-control" />
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">عنوان انگلیسی</label>
                                    <input type="text" name="title" id="title_{{$role->id}}" value="{{$role->title}}" class="form-control" />
                                </div>

                                <div class="col-md-3">
                                    <label for="permission_id_{{ $role->id }}" class="form-label">انتخاب دسترسی</label>
                                    <select name="permission_id[]" id="permission_id_{{ $role->id }}" multiple class="form-control select2-permission">
                                        <option value="all">انتخاب همه</option>
                                        @foreach(\App\Models\Permission::where('submenu_panel_id' , '<>' , null )->get() as $permission)
                                            <option value="{{ $permission->id }}" {{ in_array($permission->id, $role->permissions->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                {{ $permission->label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">فعال/غیر فعال</label>
                                    <select name="status" id="status_{{$role->id}}" class="form-control">
                                        <option value="" selected>انتخاب کنید</option>
                                        <option value="4" {{$role->status == 4 ? 'selected' : '' }}>فعال</option>
                                        <option value="0" {{$role->status == 0 ? 'selected' : '' }}>غیر فعال</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" id="editsubmit_{{$role->id}}" class="btn btn-primary" >ذخیره اطلاعات</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach($roles as $role)
        <div class="modal fade" id="permissionsModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <form id="editpermission_{{$role->id}}">
                    @csrf
                    <input type="hidden" name="role_id" value="{{ $role->id }}">
                    <input type="hidden" name="type" value="permission_update">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">مدیریت دسترسی‌ها: {{ $role->title_fa }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>صفحه</th>
                                    <th>مشاهده</th>
                                    <th>افزودن</th>
                                    <th>ویرایش</th>
                                    <th>حذف</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($role->permissions as $permission)
                                    @php $pivot = $permission->pivot; @endphp
                                    <tr>
                                        <td>{{ $permission->label }}</td>
                                        @foreach(['can_view', 'can_insert', 'can_edit', 'can_delete'] as $field)
                                            <td>
                                                <input type="checkbox"
                                                       name="permissions[{{ $permission->id }}][{{ $field }}]"
                                                    {{ $pivot->$field ? 'checked' : '' }}>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="modal-footer">
                            <button type="button" id="editpermissionsubmit_{{$role->id}}" class="btn btn-primary">ذخیره اطلاعات</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

@endsection
@section('script')
    <script src="{{'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'}}"></script>
    <script src="{{asset('assets/vendor/js/dataTables.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route(request()->segment(2).'.index')}}",
                columns: [
                    {data: 'title_fa'       , name: 'title_fa'  },
                    {data: 'title'          , name: 'title'     },
                    {data: 'permission'     , name: 'permission'},
                    {data: 'status'         , name: 'status',className:'text-center'    },
                    {data: 'action'         , name: 'action', orderable: true, searchable: true,className:'text-center'},
                ],
                language: {
                    url: "{{asset('assets/vendor/js/fa.json')}}"
                }
            });
        });
    </script>
    <script>
        jQuery(document).ready(function () {
            $('[id^=editpermissionsubmit_]').click(function (e) {
                e.preventDefault();
                var id = $(this).attr('id').split('_')[1];
                var form = $('#editpermission_' + id);
                var formData = form.serialize();

                var button = $(this);
                var originalButtonHtml = button.html();
                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> در حال ارسال...');

                $.ajax({
                    url: "{{ route(request()->segment(2).'.update' , 0) }}",
                    method: 'PUT',
                    data: formData,
                    success: function (data) {
                        if (data.success) {
                            var modal = bootstrap.Modal.getInstance(document.getElementById('permissionsModal' + id));
                            if (modal) modal.hide();
                            $('.yajra-datatable').DataTable().ajax.reload(null, false);
                        } else {
                            swal(data.subject, data.message, data.flag);
                        }
                    },
                    error: function () {
                        swal('خطا', 'مشکلی پیش آمد.', 'error');
                    },
                    complete: function () {
                        button.prop('disabled', false).html(originalButtonHtml);
                    }
                });
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
                        title_fa    : jQuery('#title_fa').val(),
                        title       : jQuery('#title').val(),
                        status      : jQuery('#status').val()
                    },
                    success: function (data) {
                        if(data.success == true){
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
                var originalButtonHtml = button.html();
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
                        id              : jQuery('#role_id_' + id).val(),
                        title_fa        : jQuery('#title_fa_' + id).val(),
                        permission_id   : jQuery('#permission_id_' + id).val(),
                        title           : jQuery('#title_' + id).val(),
                        status          : jQuery('#status_' + id).val()
                    },
                    success: function (data) {
                        if(data.success == true){
                            var modalId = '#editModal' + id;
                            var modal = bootstrap.Modal.getInstance(document.querySelector(modalId));
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
                var originalButtonHtml = button.html();
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
                        var modalId = '#deleteModal' + id;
                        var modal = bootstrap.Modal.getInstance(document.querySelector(modalId));
                        modal.hide();
                        $('.yajra-datatable').DataTable().ajax.reload(null, false);
                    },
                    error: function () {
                        alert('مشکلی پیش آمد. لطفاً دوباره تلاش کنید.');
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
        $(document).ready(function () {
            $('.select2-permission').select2({
                placeholder: "دسترسی‌ها را انتخاب کنید",
                dir: "rtl",
                closeOnSelect: false,
                width: '100%'
            }).on('select2:select', function (e) {
                const $select = $(this);
                if (e.params.data.id === 'all') {
                    const values = [];
                    $select.find('option').each(function () {
                        if (this.value !== 'all') {
                            values.push(this.value);
                        }
                    });
                    $select.val(values).trigger('change');
                }
            }).on('select2:unselect', function (e) {
                if (e.params.data.id === 'all') {
                    $(this).val(null).trigger('change');
                }
            });
        });
    </script>



@endsection
