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
                    <form id="addminuteform" method="POST" class="row g-4 mb-4" action="{{route('minute.store')}}">
                    @csrf
                        <input type="hidden" name="project_id" value="{{ $company->id }}">
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
                                <input type="text" name="file_path" class="form-control" id="file_{{ $company->id }}" readonly placeholder="انتخاب فایل">
                                <button class="btn btn-outline-secondary file-selector" type="button" data-record-id="{{ $company->id }}" data-input-id="file_{{ $company->id }}">
                                    انتخاب فایل
                                </button>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                        <button type="button" class="btn btn-primary" id="submitaddminut">ذخیره</button>
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
