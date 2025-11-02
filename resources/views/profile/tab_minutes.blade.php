<div class="tab-pane fade justify-content-center" id="navs-minutes-card" role="tabpanel">
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMinutesModal">
            <i class="mdi mdi-plus"></i>مدیریت صورتجلسات
        </button>
    </div>
    <div class="modal fade" id="addMinutesModal" tabindex="-1" aria-labelledby="addMinutesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">مدیریت صورتجلسات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="card-body">
                    <div class="modal-body">
                    <form id="addform" method="POST" action="{{ route('minute.store') }}" class="row g-4 mb-4">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="title" name="title" placeholder="عنوان">
                            <label for="title">عنوان</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="date" name="date" placeholder="تاریخ برگزاری">
                            <label for="date">تاریخ برگزاری</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select name="type" id="type" class="form-control">
                                <option value="" selected>انتخاب کنید</option>
                                <option value="صورتجلسه هیئت مدیره" >صورتجلسه هیئت مدیره</option>
                            </select>
                            <label for="type">نوع شرکت</label>
                        </div>
                    </div>
                        <div class="col-12 col-md-6">
                            <div class="input-group">
                                <input type="text" name="file_path" class="form-control" id="file_{{ $project->id }}" readonly placeholder="انتخاب فایل">
                                <button class="btn btn-outline-secondary file-selector" type="button" data-record-id="{{ $project->id }}" data-input-id="file_{{ $project->id }}">
                                    انتخاب فایل
                                </button>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" id="submit" class="btn btn-primary">ذخیره اطلاعات</button>
                        </div>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="sample1" class="table table-striped table-bordered yajra-datatable">
                    <thead>
                    <tr class="table-light">
                        <th>عنوان</th>
                        <th>تاریخ</th>
                        <th>نوع</th>
                        <th>فایل</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @if(Auth::user()->level == 'applicant')
        <script type="text/javascript">
            $(document).ready(function () {
                const minuteTable = $('#sample1.yajra-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('minute.index') }}",
                        data: function (d) {
                            d.id = "{{ $project->id }}";
                        }
                    },
                    columns: [
                        { data: 'title', name: 'title' },
                        { data: 'date', name: 'date' },
                        { data: 'type', name: 'type' },
                        {
                            data: 'file_path',
                            name: 'file_path',
                            orderable: false,
                            searchable: false,
                            render: function (data) {
                                if (!data) {
                                    return '<span class="text-muted">فاقد فایل</span>';
                                }

                                let fileUrl = data.startsWith('http')
                                    ? data
                                    : `{{ asset('storage') }}/${data}`;

                                return `<a href="${fileUrl}" target="_blank" class="text-primary text-decoration-none">
                    مشاهده فایل <i class="mdi mdi-file-outline"></i>
                </a>`;
                            }
                        }
                    ],
                    columnDefs: [
                        { targets: '_all', render: $.fn.dataTable.render.text() } // حذف کن یا تغییر بده
                    ],
                    order: [[0, 'desc']],
                    paging: false,
                    searching: false,
                    info: false,
                    language: {
                        url: "{{ asset('assets/vendor/js/fa.json') }}"
                    }
                });
            });
        </script>

    @endif
@endpush
