<style>
    .bg-light-subtle { background: #f8f9fb !important; }

    .card { transition: all .25s ease; }
    /*.card:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(0,0,0,.07); }*/

    .contract-card { transition: box-shadow .25s ease, transform .25s ease; }
    /*.contract-card:hover { box-shadow: 0 14px 28px rgba(0,0,0,.08); transform: translateY(-2px); }*/

    .file-badge {
        display:inline-flex; align-items:center; justify-content:center;
        width:36px; height:36px; border-radius:12px; background:#f3f4f6;
        font-size:18px;
    }

    .text-truncate-2{
        display:-webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2;
        overflow:hidden; line-height:1.6;
    }

    .empty-icon{ font-size: 2rem; opacity:.85; }

    /* Subtle badge palette */
    .bg-primary-subtle{ background: rgba(99,102,241,.12) !important; color:#4f46e5 !important; }
    .bg-success-subtle{ background: rgba(22,163,74,.12) !important; color:#16a34a !important; }
    .bg-warning-subtle{ background: rgba(245,158,11,.14) !important; color:#d97706 !important; }
    .bg-danger-subtle{  background: rgba(239,68,68,.12) !important; color:#dc2626 !important; }
    .bg-secondary-subtle{ background: rgba(100,116,139,.14) !important; color:#475569 !important; }
    .bg-dark-subtle{ background: rgba(15,23,42,.10) !important; color:#0f172a !important; }

    #contractSignModal iframe { border: 0; }

</style>

<div class="tab-pane fade justify-content-center" id="navs-contracts-card" role="tabpanel">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-light-subtle">

        <!-- Header + Controls -->
        <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
                <h6 class="fw-bold mb-0">قراردادها</h6>
                <span class="badge bg-primary-subtle text-primary fw-semibold">5 مورد</span>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <input id="contractSearch" type="text" class="form-control form-control-sm rounded-3"
                       placeholder="جستجو در عنوان..." dir="rtl" style="min-width:220px;">
                <select id="contractSort" class="form-select form-select-sm rounded-3" style="min-width:180px;">
                    <option value="date_desc">جدیدترین</option>
                    <option value="date_asc">قدیمی‌ترین</option>
                    <option value="title_asc">عنوان (الف→ی)</option>
                    <option value="title_desc">عنوان (ی→الف)</option>
                    <option value="status">وضعیت</option>
                </select>
            </div>
        </div>

        <!-- Legend -->
        <div class="d-flex flex-wrap gap-2 small text-muted mb-3">
            <span class="badge bg-secondary-subtle text-secondary">پیش‌نویس</span>
            <span class="badge bg-warning-subtle text-warning">در انتظار امضای شما</span>
            <span class="badge bg-success-subtle text-success">امضا شده</span>
            <span class="badge bg-danger-subtle text-danger">رد شده</span>
            <span class="badge bg-dark-subtle text-dark">منقضی</span>
        </div>

        <!-- Demo contracts -->
        <div id="contractsGrid" class="row g-3">

            <!-- 1 -->
            <div class="col-md-4 contract-item" data-title="قرارداد سرمایه اولیه" data-date="1739129600" data-status="signed">
                <div class="contract-card bg-white rounded-4 shadow-sm p-3 h-100 d-flex flex-column" dir="rtl">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="file-badge">📄</span>
                        <span class="badge bg-success-subtle text-success">امضا شده</span>
                    </div>
                    <h6 class="mb-1 fw-semibold text-dark text-truncate-2 text-end">قرارداد سرمایه اولیه</h6>
                    <div class="small text-muted mb-3 d-flex justify-content-between">
                        <span>نوع: سرمایه‌گذاری</span><span>تاریخ: ۱۴۰۳/۰۷/۲۱</span>
                    </div>
                    <div class="mt-auto d-flex gap-2">
                        <a href="#" class="btn btn-sm btn-outline-primary px-3">مشاهده</a>
                        <a href="#" class="btn btn-sm btn-outline-secondary px-3">دانلود</a>
                    </div>
                </div>
            </div>

            <!-- 2 -->
            <div class="col-md-4 contract-item" data-title="تفاهم‌نامه همکاری فناورانه" data-date="1738524800" data-status="awaiting_applicant">
                <div class="contract-card bg-white rounded-4 shadow-sm p-3 h-100 d-flex flex-column" dir="rtl">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="file-badge">📄</span>
                        <span class="badge bg-warning-subtle text-warning">در انتظار امضای شما</span>
                    </div>
                    <h6 class="mb-1 fw-semibold text-dark text-truncate-2 text-end">تفاهم‌نامه همکاری فناورانه</h6>
                    <div class="small text-muted mb-3 d-flex justify-content-between">
                        <span>نوع: تفاهم‌نامه</span><span>تاریخ: ۱۴۰۳/۰۶/۲۸</span>
                    </div>
                    <div class="mt-auto d-flex gap-2">
                        <a href="#" class="btn btn-sm btn-outline-primary px-3">مشاهده</a>
                        <button class="btn btn-sm btn-primary px-3">امضا</button>
                    </div>
                </div>
            </div>

            <!-- 3 -->
            <div class="col-md-4 contract-item" data-title="قرارداد خدمات مشاوره" data-date="1737811200" data-status="rejected">
                <div class="contract-card bg-white rounded-4 shadow-sm p-3 h-100 d-flex flex-column" dir="rtl">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="file-badge">📄</span>
                        <span class="badge bg-danger-subtle text-danger">رد شده</span>
                    </div>
                    <h6 class="mb-1 fw-semibold text-dark text-truncate-2 text-end">قرارداد خدمات مشاوره</h6>
                    <div class="small text-muted mb-3 d-flex justify-content-between">
                        <span>نوع: خدمات</span><span>تاریخ: ۱۴۰۳/۰۵/۱۵</span>
                    </div>
                    <div class="mt-auto d-flex gap-2">
                        <a href="#" class="btn btn-sm btn-outline-primary px-3">مشاهده</a>
                        <button class="btn btn-sm btn-outline-secondary px-3" disabled>دانلود</button>
                    </div>
                </div>
            </div>

            <!-- 4 -->
            <div class="col-md-4 contract-item" data-title="پیوست حقوقی سرمایه‌گذاری" data-date="1736697600" data-status="draft">
                <div class="contract-card bg-white rounded-4 shadow-sm p-3 h-100 d-flex flex-column" dir="rtl">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="file-badge">📄</span>
                        <span class="badge bg-secondary-subtle text-secondary">پیش‌نویس</span>
                    </div>
                    <h6 class="mb-1 fw-semibold text-dark text-truncate-2 text-end">پیوست حقوقی سرمایه‌گذاری</h6>
                    <div class="small text-muted mb-3 d-flex justify-content-between">
                        <span>نوع: ضمیمه</span><span>تاریخ: ۱۴۰۳/۰۴/۲۰</span>
                    </div>
                    <div class="mt-auto d-flex gap-2">
                        <a href="#" class="btn btn-sm btn-outline-primary px-3">مشاهده</a>
                        <button class="btn btn-sm btn-outline-secondary px-3" disabled>دانلود</button>
                    </div>
                </div>
            </div>

            <!-- 5 -->
            <div class="col-md-4 contract-item" data-title="قرارداد انتقال سهام" data-date="1735584000" data-status="expired">
                <div class="contract-card bg-white rounded-4 shadow-sm p-3 h-100 d-flex flex-column" dir="rtl">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="file-badge">📄</span>
                        <span class="badge bg-dark-subtle text-dark">منقضی</span>
                    </div>
                    <h6 class="mb-1 fw-semibold text-dark text-truncate-2 text-end">قرارداد انتقال سهام</h6>
                    <div class="small text-muted mb-3 d-flex justify-content-between">
                        <span>نوع: حقوقی</span><span>تاریخ: ۱۴۰۳/۰۳/۱۰</span>
                    </div>
                    <div class="mt-auto d-flex gap-2">
                        <a href="#" class="btn btn-sm btn-outline-primary px-3">مشاهده</a>
                        <button class="btn btn-sm btn-outline-secondary px-3" disabled>دانلود</button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
