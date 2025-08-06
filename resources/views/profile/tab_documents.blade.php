@php
    $files = [
        ['name' => 'صورت‌های مالی ۱۴۰۲.pdf', 'type' => 'pdf', 'category' => 'مالی', 'size' => '1.3 MB', 'uploaded_at' => '1403/04/10', 'desc' => 'گزارش مالی سال ۱۴۰۲'],
        ['name' => 'قرارداد نهایی.docx', 'type' => 'docx', 'category' => 'قرارداد', 'size' => '420 KB', 'uploaded_at' => '1403/03/29', 'desc' => 'متن قرارداد مصوب'],
        ['name' => 'لوگوی شرکت.png', 'type' => 'png', 'category' => 'سایر', 'size' => '110 KB', 'uploaded_at' => '1403/01/10', 'desc' => 'لوگوی رسمی شرکت'],
    ];
@endphp

<div class="tab-pane fade justify-content-center" id="navs-file-and-doc-card" role="tabpanel">
    {{-- فرم آپلود --}}
    <div class="card mb-4 shadow-sm" style="max-width:520px;margin:0 auto;">
        <div class="card-body">
            <h6 class="fw-bold mb-3">بارگذاری فایل جدید</h6>
            <form>
                <div class="row g-2">
                    <div class="col-md-6"><input type="file" class="form-control"></div>
                    <div class="col-md-6"><input type="text" class="form-control" placeholder="توضیح (اختیاری)"></div>
                    <div class="col-md-6">
                        <select class="form-select">
                            <option value="">دسته‌بندی</option>
                            <option>مالی</option>
                            <option>قرارداد</option>
                            <option>سایر</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button class="btn btn-primary w-100">آپلود فایل</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- جدول نمایش فایل‌ها --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold mb-3">لیست فایل‌ها و مستندات</h6>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th style="width:40px"></th>
                        <th>نام فایل</th>
                        <th>دسته</th>
                        <th>توضیح</th>
                        <th>حجم</th>
                        <th>تاریخ بارگذاری</th>
                        <th class="text-center" style="width:120px">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($files as $file)
                        <tr>
                            <td>
                                @switch($file['type'])
                                    @case('pdf') <i class="mdi mdi-file-pdf-box text-danger fs-4"></i> @break
                                    @case('docx') <i class="mdi mdi-file-word-box text-primary fs-4"></i> @break
                                    @case('png') @case('jpg') <i class="mdi mdi-file-image-box text-warning fs-4"></i> @break
                                    @default <i class="mdi mdi-file-document-outline text-secondary fs-4"></i>
                                @endswitch
                            </td>
                            <td>{{ $file['name'] }}</td>
                            <td>{{ $file['category'] }}</td>
                            <td>{{ $file['desc'] }}</td>
                            <td>{{ $file['size'] }}</td>
                            <td>{{ $file['uploaded_at'] }}</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-primary" title="دانلود"><i class="mdi mdi-download"></i></a>
                                <a href="#" class="btn btn-sm btn-outline-danger" title="حذف"><i class="mdi mdi-trash-can-outline"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">هیچ فایلی ثبت نشده است.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
