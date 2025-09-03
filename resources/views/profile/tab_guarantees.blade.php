<div class="tab-pane fade justify-content-center" id="navs-guarantee-card" role="tabpanel">

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold mb-3">لیست تعهدات</h6>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>ردیف </th>
                        <th>تعهدات </th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($commitments as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->title }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">موردی ثبت نشده است.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
