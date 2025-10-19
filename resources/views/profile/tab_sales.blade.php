<div class="tab-pane fade justify-content-center" id="navs-sales-card" role="tabpanel">
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSale">
            <i class="mdi mdi-plus"></i>افزودن
        </button>
    </div>
    <div class="modal fade" id="addSale" tabindex="-1" aria-labelledby="addSaleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">اطلاعات مالی</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="card-body">
                    <div class="modal-body">
                        <form id="addsaleform" method="POST" class="row g-4 mb-4" action="{{route('minute.store')}}">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="price-input form-control" id="count_customers" name="count_customers" placeholder="تعداد مشتریان">
                                    <label for="count_customers">تعداد مشتریان</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="price-input form-control" id="count_sales" name="count_sales" placeholder="تعداد فروش">
                                    <label for="count_sales">تعداد فروش</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="price-input form-control" id="amount_sales" name="amount_sales" placeholder="مبلغ فروش">
                                    <label for="amount_sales">مبلغ فروش</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="price-input form-control" id="monthly_income" name="monthly_income" placeholder="درآمد ماهانه">
                                    <label for="monthly_income">درآمد ماهانه</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="price-input form-control" id="current_cost" name="current_cost" placeholder="مجموع هزینه های جاری">
                                    <label for="current_cost">مجموع هزینه های جاری</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="price-input form-control" id="financial_cost" name="financial_cost" placeholder="مجموع هزینه های اداری">
                                    <label for="financial_cost">مجموع هزینه های اداری</label>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-primary" id="submitaddsale">ذخیره</button>
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
                        <th>تعداد مشتریان</th>
                        <th>تعداد فروش</th>
                        <th>مبلغ فروش</th>
                        <th>درآمد ماهانه</th>
                        <th>مجموع هزینه ها جاری</th>
                        <th>مجموع هزینه ها اداری </th>
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
