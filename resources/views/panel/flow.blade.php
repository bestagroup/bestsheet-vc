@extends('layouts.base')
@section('title', 'مدیریت طرح ها')
@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/dataTables.dataTables.min.css') }}"/>
<link rel="stylesheet" href="{{'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'}}" />
<style> table{margin: 0 auto;width: 100% !important;clear: both;border-collapse: collapse;table-layout: auto !important;word-wrap:break-word;white-space: nowrap;} .dt-layout-start{margin-right: 0 !important;} .dt-layout-end{margin-left: 0 !important;}</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{$thispage['list']}}</h5>
                @if (auth()->user()->can('can-access', ['flow', 'insert']))
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">{{$thispage['add']}}</a>
                @endif
            </div>
            <div class="table-responsive">
                <table id="sample1" class="table table-striped table-bordered yajra-datatable">
                <thead>
                    <tr class="table-light">
                        <th>تغییرات</th>
                        <th>نام طرح</th>
                        <th>مدیرعامل شرکت</th>
                        <th>وضعیت پرتفو</th>
                        <th>مرحله فرایند شرکت</th>
                        <th>درصد پیشرفت</th>
                        <th>تاریخ شروع قرارداد</th>
                        <th>کل مبلغ درخواستی</th>
                        <th>مجموع مبلغ واریزی</th>
                        <th>مانده مبلغ تعهدات</th>
                        <th>تغییرات</th>
                    </tr>
                    </thead>
                    <tbody>
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
                        <form id="addform" data-type="create" method="POST" class="row g-4 mb-4" action="{{ route('project.store') }}">
                            @csrf
                            <div class="col-12 col-md-4">
                                <div class="form-floating form-floating-outline">
                                    <input required type="text" class="form-control" id="company_name" name="company_name" placeholder="نام شرکت">
                                    <label for="company_name">نام شرکت</label>
                                    <div class="invalid-feedback" id="company_nameFeedback">نام شرکت اجباری می باشد.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-floating form-floating-outline">
                                    <input required type="text" class="form-control" id="title" name="title" placeholder="نام طرح">
                                    <label for="title">نام طرح</label>
                                    <div class="invalid-feedback" id="titleFeedback">نام طرح اجباری می باشد.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-floating form-floating-outline">
                                    <input required inputmode="numeric" pattern="^\d{3,20}$" maxlength="20" minlength="3" type="text" class="form-control" id="registration_number" name="registration_number" placeholder="شماره ثبت">
                                    <label for="registration_number">شماره ثبت</label>
                                    <div class="invalid-feedback" id="registration_numberFeedback">شماره ثبت اجباری و شامل عدد می باشد.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-floating form-floating-outline">
                                    <input required inputmode="numeric" pattern="^\d{3,20}$" maxlength="20" minlength="3" type="text" class="form-control" id="national_id" name="national_id" placeholder="شناسه ملی شرکت" >
                                    <label for="national_id">شناسه ملی شرکت</label>
                                    <div class="invalid-feedback" id="national_idFeedback">شناسه ملی شرکت اجباری و شامل عدد می باشد.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-floating form-floating-outline">
                                    <input required inputmode="numeric" pattern="^\d{3,20}$" maxlength="20" minlength="3" type="text" class="form-control" id="economic_code" name="economic_code" placeholder="کد اقتصادی شرکت" >
                                    <label for="economic_code">کد اقتصادی شرکت</label>
                                    <div class="invalid-feedback" id="economic_codeFeedback">کد اقتصادی اجباری، و شامل عدد می باشد.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-floating form-floating-outline">
                                    <select name="legal_type" id="legal_type" class="form-control">
                                        <option value="" selected>انتخاب کنید</option>
                                        <option value="مسئولیت محدود"   >مسئولیت محدود</option>
                                        <option value="سهامی خاص"       >سهامی خاص</option>
                                        <option value="سهامی عام"       >سهامی عام</option>
                                        <option value="تعاونی"          >تعاونی</option>
                                        <option value="موسسه غیر تجاری" >موسسه غیر تجاری</option>
                                    </select>
                                    <label for="legal_type">نوع شرکت</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-floating form-floating-outline">
                                    <input inputmode="numeric" pattern="^\d{3,20}$" type="text" class="form-control" id="tel" name="tel" placeholder="تلفن شرکت">
                                    <label for="tel">تلفن شرکت</label>
                                    <div class="invalid-feedback" id="telFeedback">شماره تلفن شامل عدد می باشد.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="ایمیل شرکت">
                                    <label for="email">ایمیل شرکت</label>
                                    <div class="invalid-feedback" id="emailFeedback">آدرس ایمیل را با دقت وارد کنید.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="website" name="website" placeholder="وبسایت">
                                    <label for="website">وبسایت</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-floating form-floating-outline">
                                    <input required type="text" class="form-control" id="postal_code" name="postal_code" placeholder="کد پستی" >
                                    <label for="postal_code">کد پستی</label>
                                    <div class="invalid-feedback" id="postal_codeFeedback">کد پستی باید به شکل عدد 10 رقمی وارد شود</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-floating form-floating-outline">
                                    <select name="state" id="state" class="form-control select2">
                                        <option value="" selected>انتخاب کنید</option>
                                        @foreach($states as $state)
                                            <option value="{{$state->id}}">
                                                {{$state->title}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="state">استان</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-floating form-floating-outline">
                                    <select name="city" id="city" class="form-control select2">
                                        <option value="" selected>انتخاب کنید</option>
                                        @foreach($cities as $city)
                                            <option value="{{$city->id}}">
                                                {{$city->title}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="city">شهرستان</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-floating form-floating-outline">
                                    <input required type="text" class="form-control" id="CEO" name="CEO" placeholder="مدیرعامل">
                                    <label for="CEO">مدیرعامل</label>
                                    <div class="invalid-feedback" id="CEOFeedback">نام مدیرعامل اجباری می باشد.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-floating form-floating-outline">
                                    <input required inputmode="numeric" pattern="^\d{3,20}$" maxlength="20" minlength="3" type="text" class="form-control" id="ceo_national_code" name="ceo_national_code"
                                           placeholder="کد ملی مدیرعامل" >
                                    <label for="ceo_national_code">کد ملی مدیرعامل</label>
                                    <div class="invalid-feedback" id="ceo_national_codeFeedback">کد ملی مدیرعامل اجباری می باشد و با دقت وارد شود</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <div class="form-floating form-floating-outline">
                                    <textarea rows="2" class="form-control" id="address" name="address" placeholder="آدرس"></textarea>
                                    <label for="address">آدرس شرکت</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <div class="form-floating form-floating-outline">
                                    <textarea name="description" class="form-control" id="description" style="height: 150px" placeholder="معرفی طرح" ></textarea>
                                    <label for="description">معرفی طرح</label>
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
    <!-- Profile Modal -->
    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showModalLabel">پروفایل شرکت</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="showModalBody">
                    <div class="text-center text-muted py-5">در حال بارگذاری...</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Media Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">{{$thispage['add']}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('storemedia') }}" enctype="multipart/form-data" class="dropzone dz-clickable border rounded-3 shadow-sm bg-light p-4" id="fileUploadZone" style="min-height: 220px; border-style: dashed;">
                        @csrf
                        <input type="hidden" id="recordIdInput" name="record_id">
                        <div class="dz-message text-center text-muted">
                            <div class="mb-3">
                                <i class="bi bi-cloud-arrow-up" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="fw-bold mb-2">برای آپلود فایل، کلیک کنید یا فایل را بکشید اینجا</h5>
                            <p class="small text-secondary mb-0">فرمت‌های مجاز: JPG, PNG, PDF, MP4, DOCX (حداکثر 40 مگابایت)</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- مودال پیش نمایش عمومی -->
    <div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">پیش نمایش فایل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body text-center" id="previewContent">
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{asset('assets/vendor/js/dataTables.min.js')}}"></script>
    <script src="{{'https://cdn.datatables.net/fixedcolumns/5.0.4/js/dataTables.fixedColumns.js'}}"></script>
    <script src="{{'https://cdn.datatables.net/fixedcolumns/5.0.4/js/fixedColumns.dataTables.js'}}"></script>
    <script src="{{asset('assets/vendor/js/formhandler.js')}}"></script>

     <script type="text/javascript">
        $(function () {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, 'desc']],
                scrollX: true,
                scrollCollapse: true,
                // fixedColumns: {
                //     start: 3
                // },
                ajax: "{{ route(request()->segment(2) . '.index') }}",
                columns: [
                    {data: 'action'                         , name: 'action', orderable: true, searchable: true},
                    {data: 'company_name'                  , name: 'company_name'},
                    {data: 'title'                          , name: 'title'},
                    {data: 'CEO'                            , name: 'CEO'},
                    {data: 'flow_level'                     , name: 'flow_level'},
                    {data: 'invest_step'                    , name: 'invest_step'},
                    {data: 'start_date'                     , name: 'start_date'},
                    {data: 'amount_request_accept'          , name: 'amount_request_accept'},
                    {data: 'amount_deposited'               , name: 'amount_deposited'},
                    {data: 'commitment_balance'             , name: 'commitment_balance'},
                    {data: 'action'                         , name: 'action', orderable: true, searchable: true},
                ],
                language: {
                    url: "{{asset('assets/vendor/js/fa.json')}}"
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

    <script>
        Dropzone.autoDiscover = false;

        document.addEventListener("DOMContentLoaded", function () {
            const fileFormSelector = "#fileUploadZone";
            //let currentRecordId = null;
            const recordId = $(this).data('id');

            const dz = new Dropzone(fileFormSelector, {
                url: "{{ route('storemedia') }}",
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                maxFilesize: 20,
                acceptedFiles: 'image/*,video/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                dictDefaultMessage: "فایل‌ها را اینجا رها کنید یا کلیک کنید برای انتخاب",
                init: function () {
                    this.on("sending", function (file, xhr, formData) {

                        formData.append("record_id", recordId || document.getElementById('recordIdInput').value);
                    });
                    this.on("success", function (file, response) {
                        const extension = file.name.split('.').pop().toLowerCase();
                        previewFile(response.file_path.replace(/^\/+/, ''), extension);
                        showToast("✅ فایل با موفقیت آپلود شد");
                        this.removeFile(file);
                    });
                    this.on("error", function (file, response) {
                        showToast("❌ خطا در آپلود فایل", "danger");
                    });
                }
            });

            $(document).on('click', '.upload-btn', function () {
                currentRecordId = $(this).data('id');
                $('#recordIdInput').val(currentRecordId);

                dz.removeAllFiles(true);

                $('#uploadModal').modal('show');
            });
        });
    </script>

    <script>
        //انتخاب و مدیریت فایل های یک پروژه
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('file-selector')) {
                e.preventDefault();

                const recordId = e.target.dataset.recordId;
                const inputId = e.target.dataset.inputId;
                const url = "{{ route('selectfile') }}?record_id=" + recordId;

                window.open(url, 'FileManager', 'width=800,height=600');

                window.setFileUrl = function (fileUrl) {
                    document.getElementById(inputId).value = fileUrl;
                };
            }
        });
    </script>

    <script>
        document.querySelectorAll('.send-btn').forEach(function(button) {
            button.addEventListener('click', function () {
                let recordId = this.getAttribute('data-id');
                let status   = this.getAttribute('data-status');
                let parent   = this.closest('.record-box'); // 👈 امن‌تر

                fetch("{{ route('filestatus') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        id: recordId,
                        status: status
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log("پاسخ سرور:", data);

                        if (status === "5") {
                            // ❌ حذف کل رکورد
                            parent.remove();
                        } else if (status === "4") {
                            // ✅ حذف دکمه‌ها و نمایش متن تایید شد
                            parent.querySelectorAll('.send-btn').forEach(btn => btn.remove());

                            let msg = document.createElement('span');
                            msg.textContent = "✔ تایید شد";
                            msg.style.color = "green";
                            msg.style.fontWeight = "bold";

                            parent.appendChild(msg);
                        }
                    })
                    .catch(error => {
                        console.error("خطا:", error);
                    });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
             $('#state').on('change', function () {
                let stateId = $(this).val();
                let $citySelect = $('#city');
                $citySelect.html('<option value="">در حال بارگذاری...</option>').trigger('change');

                if (stateId) {
                    $.get(`getcities/${stateId}`, function (data) {
                        $citySelect.empty().append('<option value="">انتخاب کنید</option>');
                        data.forEach(function (city) {
                            $citySelect.append(new Option(city.title, city.id));
                        });
                        $citySelect.trigger('change');
                    });
                } else {
                    $citySelect.html('<option value="">انتخاب کنید</option>').trigger('change');
                }
            });
        });
    </script>

@endsection
