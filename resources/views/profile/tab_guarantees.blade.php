<style>
    .bg-light-subtle { background: var(--bs-body-bg) !important; }

    .card { transition: all .25s ease; }
    /*.card:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(0,0,0,.06); }*/

    .commitment-card { transition: box-shadow .25s ease, transform .25s ease; }
    .commitment-card:hover { box-shadow: 0 12px 26px rgba(0,0,0,.08); transform: translateY(-2px); }

    .index-badge{
        display:inline-flex; align-items:center; justify-content:center;
        width:34px; height:34px; border-radius:50%;
        background:var(--bs-body-bg); color:var(--bs-link-color); font-weight:700;
        box-shadow: inset 0 0 0 2px rgba(79,70,229,.08);
    }

    .text-truncate-2{
        display:-webkit-box; -webkit-box-orient:vertical;
        overflow:hidden; line-height:1.8;
    }

    .bg-primary-subtle{
        background: rgba(99,102,241,.12) !important; color:#4f46e5 !important;
    }

    .empty-icon{ font-size: 2rem; opacity:.8; }

</style>

<div class="tab-pane fade justify-content-center" id="navs-guarantee-card" role="tabpanel">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-light-subtle">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">لیست تعهدات</h6>
            <span class="badge bg-primary-subtle text-primary fw-semibold">
        {{ isset($commitments) ? $commitments->count() : 0 }} مورد
      </span>
        </div>

        @if(isset($commitments) && $commitments->isNotEmpty())
            <div class="row g-3">
                @foreach($commitments as $item)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="commitment-card bg-white rounded-4 shadow-sm p-3 h-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="index-badge">{{ $loop->iteration }}</span>
                                <span class="badge bg-light text-secondary">تعهد</span>
                            </div>
                            <div class="mt-4">
                                <h6 class="mb-1 fw-semibold text-dark text-truncate-2  text-start" title="{{ $item->title }}">
                                    {{ $item->title }}
                                </h6>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state text-center py-5">
                <div class="empty-icon mb-3">📝</div>
                <p class="text-muted mb-0">موردی ثبت نشده است.</p>
            </div>
        @endif
    </div>
</div>
