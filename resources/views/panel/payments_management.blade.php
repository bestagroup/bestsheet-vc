@extends('layouts.base')

@section('title', 'مدیریت پرداخت‌ها')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">لیست پرداخت‌ها</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead>
                    <tr class="table-light">
                        <th>شماره پرداخت</th>
                        <th>نام کاربر</th>
                        <th>شماره موبایل</th>
                        <th>عنوان دوره</th>
                        <th>نوع شرکت در دوره</th>
                        <th>گواهی دوره</th>
                        <th>نام پدر</th>
                        <th>کد ملی</th>
                        <th>تاریخ تولد</th>
                        <th>مبلغ پرداخت</th>
                        <th>وضعیت پرداخت</th>
                        <th>تاریخ پرداخت</th>
                        <th>کد پیگیری پرداخت</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>50</td>
                        <td>محمد</td>
                        <td>09055583936</td>
                        <td>قانون جدید شورا</td>
                        <td>حضوری</td>
                        <td>بدون گواهی</td>
                        <td>---</td>
                        <td>---</td>
                        <td>---</td>
                        <td>50000</td>
                        <td><span class="badge bg-success">پرداخت موفق</span></td>
                        <td>1403/05/03</td>
                        <td>56191501801</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-icon btn-outline-primary" title="مشاهده">
                                <i class="mdi mdi-eye-outline"></i>
                            </a>
                        </td>
                    </tr>
                    <!-- ردیف‌های دیگر پرداخت‌ها -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav class="mt-4" aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#">قبلی</a>
                    </li>
                    <li class="page-item active">
                        <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">بعدی</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
@endsection
