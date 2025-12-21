@extends('layouts.base')

@section('title')
    <title>{{ $thispage['title'] }}</title>
@endsection

@section('content')
    <style>
        .report-wrap { direction: rtl; }
        .report-title { margin-bottom: 6px; font-weight: 700; }
        .report-subtitle { margin-top: 0; opacity: .75; }

        .kpi-row {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 18px;
        }
        .kpi-col { flex: 1 1 220px; }

        .kpi-card {
            border-radius: 14px;
            box-shadow: 0 8px 22px rgba(17,24,39,.08) !important;
        }
        .kpi-card .card-content { padding: 16px 16px; }
        .kpi-value { font-size: 22px; font-weight: 800; margin: 0; }
        .kpi-label { margin: 6px 0 0; opacity: .9; }

        .report-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }
        .report-col { flex: 1 1 calc(33.333% - 14px); min-width: 340px; }

        .report-card {
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(17,24,39,.08) !important;
            overflow: hidden;
        }
        .report-card .card-content { padding: 16px 16px 10px; }
        .card-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; }
        .card-head h6 { margin:0; font-weight:800; font-size: 14px; color: #1f2937; }
        .card-hint { font-size: 12px; opacity: .65; }

        /* fixed height for chart areas */
        .chart-box { position: relative; height: 240px; }
        .chart-box.tall { height: 280px; }
        .chart-box.full { height: 320px; }

        /* make canvas fill */
        .chart-box canvas { width: 100% !important; height: 100% !important; }

        @media (max-width: 1100px) { .report-col { flex: 1 1 calc(50% - 14px); } }
        @media (max-width: 700px)  { .report-col { flex: 1 1 100%; min-width: unset; } }
    </style>

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-email.css') }}" />


    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-email card" len="59747">
            <div class="border-0" len="49335">
                <div class="row g-0" len="49300">
                    <!-- Email Sidebar -->
                    <div class="col app-email-sidebar border-end flex-grow-0" id="app-email-sidebar" len="4259">
                        <div class="btn-compost-wrapper d-grid" len="166">
                            <button class="btn btn-primary btn-compose waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#emailComposeSidebar" len="7" lang="fa" style="">نوشتن</button>
                        </div>
                        <!-- Email Filters -->
                        <div class="email-filters pt-2 pb-2 ps ps__rtl" len="3937">
                            <!-- Email Filters: Folder -->
                            <ul class="email-filter-folders list-unstyled" len="2252">
                                <li class="active d-flex justify-content-between align-items-center" data-target="inbox" len="322">
                                    <a href="javascript:void(0);" class="d-flex flex-wrap align-items-center" len="144">
                                        <i class="mdi mdi-email-outline mdi-20px me-1" len="0"></i>
                                        <span class="align-middle ms-2" len="5" lang="fa">صندوق پستی</span>
                                    </a>
                                    <div class="badge bg-label-primary rounded-pill" len="2" lang="fa">21</div>
                                </li>
                                <li class="d-flex" data-target="sent" len="248">
                                    <a href="javascript:void(0);" class="d-flex flex-wrap align-items-center" len="142">
                                        <i class="mdi mdi-send-outline mdi-20px me-1" len="0"></i>
                                        <span class="align-middle ms-2" len="4" lang="fa">فرستاده</span>
                                    </a>
                                </li>
                                <li class="d-flex justify-content-between align-items-center" data-target="draft" len="322">
                                    <a href="javascript:void(0);" class="d-flex flex-wrap align-items-center" len="145">
                                        <i class="mdi mdi-pencil-outline mdi-20px me-1" len="0"></i>
                                        <span class="align-middle ms-2" len="5" lang="fa">پیش نویس</span>
                                    </a>
                                    <div class="badge bg-label-warning rounded-pill" len="1" lang="fa" style="">1</div>
                                </li>
                                <li class="d-flex justify-content-between" data-target="starred" len="251">
                                    <a href="javascript:void(0);" class="d-flex flex-wrap align-items-center" len="145">
                                        <i class="mdi mdi-star-outline mdi-20px me-1" len="0"></i>
                                        <span class="align-middle ms-2" len="7" lang="fa">ستاره دار</span>
                                    </a>
                                </li>
                                <li class="d-flex justify-content-between align-items-center" data-target="spam" len="326">
                                    <a href="javascript:void(0);" class="d-flex flex-wrap align-items-center" len="150">
                                        <i class="mdi mdi-alert-circle-outline mdi-20px me-1" len="0"></i>
                                        <span class="align-middle ms-2" len="4" lang="fa">اسپم</span>
                                    </a>
                                    <div class="badge bg-label-danger rounded-pill" len="1" lang="fa">6</div>
                                </li>
                                <li class="d-flex align-items-center" data-target="trash" len="251">
                                    <a href="javascript:void(0);" class="d-flex flex-wrap align-items-center" len="145">
                                        <i class="mdi mdi-delete-outline mdi-20px me-1" len="0"></i>
                                        <span class="align-middle ms-2" len="5" lang="fa">زباله</span>
                                    </a>
                                </li>
                            </ul>
                            <!-- Email Filters: Labels -->
                            <div class="email-filter-labels pt-2" len="1168">
                                <small class="mb-2 mx-4 text-muted text-uppercase" len="6" lang="fa">برچسب</small>
                                <ul class="list-unstyled mb-0" len="1030">
                                    <li data-target="work" len="206">
                                        <a href="javascript:void(0);" len="140">
                                            <i class="badge badge-dot bg-success" len="0"></i>
                                            <span class="align-middle ms-3" len="4" lang="fa">کار</span>
                                        </a>
                                    </li>
                                    <li data-target="company" len="209">
                                        <a href="javascript:void(0);" len="143">
                                            <i class="badge badge-dot bg-primary" len="0"></i>
                                            <span class="align-middle ms-3" len="7" lang="fa">شرکت</span>
                                        </a>
                                    </li>
                                    <li data-target="important" len="211">
                                        <a href="javascript:void(0);" len="145">
                                            <i class="badge badge-dot bg-warning" len="0"></i>
                                            <span class="align-middle ms-3" len="9" lang="fa">مهم</span>
                                        </a>
                                    </li>
                                    <li data-target="private" len="208">
                                        <a href="javascript:void(0);" len="142">
                                            <i class="badge badge-dot bg-danger" len="0"></i>
                                            <span class="align-middle ms-3" len="7" lang="fa">خصوصی</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!--/ Email Filters -->
                            <div class="ps__rail-x" style="left: 0px; bottom: 0px;" len="75"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;" len="0"></div></div><div class="ps__rail-y" style="top: 0px; right: 246px;" len="75"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;" len="0"></div></div></div>
                    </div>
                    <!--/ Email Sidebar -->

                    <!-- Emails List -->
                    <div class="col app-emails-list" len="26910">
                        <div class="card shadow-none border-0" len="26809">
                            <div class="card-body emails-list-header p-3 py-2" len="6389">
                                <!-- Email List: Search -->
                                <div class="d-flex justify-content-between align-items-center" len="1935">
                                    <div class="d-flex align-items-center w-100" len="706">
                                        <i class="mdi mdi-menu mdi-24px cursor-pointer d-block d-lg-none me-3" data-bs-toggle="sidebar" data-target="#app-email-sidebar" data-overlay="" len="0"></i>
                                        <div class="mb-0 mb-lg-1 w-100" len="470">
                                            <div class="input-group input-group-merge shadow-none" len="373">
                                  <span class="input-group-text border-0 ps-0" id="email-search" len="95">
                                    <i class="mdi mdi-magnify mdi-20px text-muted" len="0"></i>
                                  </span>
                                                <input type="text" class="form-control email-search-input border-0"  placeholder="جست و جو..." aria-label="Search..." aria-describedby="email-search" len="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-0 mb-md-2" len="1077">
                              <span class="btn btn-icon btn-text-secondary rounded-pill waves-effect waves-light" len="119">
                                <i class="mdi mdi-refresh mdi-20px scaleX-n1-rtl cursor-pointer email-refresh" len="0"></i>
                              </span>
                                        <div class="dropdown btn btn-icon btn-text-secondary rounded-pill waves-effect waves-light" len="720">
                                            <i class="mdi mdi-dots-vertical mdi-20px cursor-pointer" id="emailsActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" len="19">
                                            </i>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="emailsActions" len="414">
                                                <a class="dropdown-item waves-effect" href="javascript:void(0)" len="12" lang="fa">نشانگذاری به عنوان خوانده شده</a>
                                                <a class="dropdown-item waves-effect" href="javascript:void(0)" len="14" lang="fa">نشاندار کردن به عنوان خواندهنشده</a>
                                                <a class="dropdown-item waves-effect" href="javascript:void(0)" len="6" lang="fa">حذف</a>
                                                <a class="dropdown-item waves-effect" href="javascript:void(0)" len="7" lang="fa">بایگانی</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mx-n3 emails-list-header-hr" len="0">
                                <!-- Email List: Actions -->
                                <div class="d-flex justify-content-between align-items-center mb-2" len="4140">
                                    <div class="d-flex align-items-center" len="3370">
                                        <div class="form-check me-1 mb-0" len="188">
                                            <input class="form-check-input" type="checkbox" id="email-select-all" len="0">
                                            <label class="form-check-label" for="email-select-all" len="0"></label>
                                        </div>
                                        <div class="btn btn-text-secondary btn-icon rounded-pill me-1 waves-effect waves-light" len="116">
                                            <i class="mdi mdi-delete-outline mdi-24px email-list-delete cursor-pointer" len="0"></i>
                                        </div>
                                        <div class="btn btn-text-secondary btn-icon rounded-pill me-1 waves-effect waves-light" len="118">
                                            <i class="mdi mdi-email-open-outline mdi-24px email-list-read cursor-pointer" len="0"></i>
                                        </div>
                                        <div class="dropdown btn btn-text-secondary btn-icon rounded-pill me-1 waves-effect waves-light" len="1042">
                                            <i class="mdi mdi-folder-outline mdi-24px cursor-pointer" id="dropdownMenuFolderOne" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" len="0"></i>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuFolderOne" len="738">
                                                <a class="dropdown-item waves-effect" href="javascript:void(0)" len="154">
                                                    <i class="mdi mdi-alert-circle-outline me-1" len="0"></i>
                                                    <span class="align-middle" len="4" lang="fa">اسپم</span>
                                                </a>
                                                <a class="dropdown-item waves-effect" href="javascript:void(0)" len="149">
                                                    <i class="mdi mdi-pencil-outline me-1" len="0"></i>
                                                    <span class="align-middle" len="5" lang="fa">پیش نویس</span>
                                                </a>
                                                <a class="dropdown-item waves-effect" href="javascript:void(0)" len="149">
                                                    <i class="mdi mdi-delete-outline me-1" len="0"></i>
                                                    <span class="align-middle" len="5" lang="fa">زباله</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dropdown btn btn-text-secondary btn-icon rounded-pill waves-effect waves-light" len="1377">
                                            <i class="mdi mdi-label-outline mdi-24px cursor-pointer" id="dropdownLabelOne" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" len="19">
                                            </i>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownLabelOne" len="1065">
                                                <a class="dropdown-item waves-effect" href="javascript:void(0)" len="173">
                                                    <i class="mdi mdi-circle-medium mdi-24px text-success me-1" len="0"></i>
                                                    <span class="align-middle" len="8" lang="fa">کارگاه</span>
                                                </a>
                                                <a class="dropdown-item waves-effect" href="javascript:void(0)" len="172">
                                                    <i class="mdi mdi-circle-medium mdi-24px text-primary me-1" len="0"></i>
                                                    <span class="align-middle" len="7" lang="fa">شرکت</span>
                                                </a>
                                                <a class="dropdown-item waves-effect" href="javascript:void(0)" len="174">
                                                    <i class="mdi mdi-circle-medium mdi-24px text-warning me-1" len="0"></i>
                                                    <span class="align-middle" len="9" lang="fa">مهم</span>
                                                </a>
                                                <a class="dropdown-item waves-effect" href="javascript:void(0)" len="171">
                                                    <i class="mdi mdi-circle-medium mdi-24px text-danger me-1" len="0"></i>
                                                    <span class="align-middle" len="7" lang="fa">خصوصی</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="email-pagination d-sm-flex d-none align-items-center flex-wrap justify-content-between justify-sm-content-end" len="553">
                                        <span class="d-sm-block d-none mx-3" len="11" lang="fa">1-10 از 653</span>
                                        <span class="btn btn-icon btn-text-secondary rounded-pill btn-sm waves-effect waves-light" len="123">
                                <i class="email-prev mdi mdi-chevron-left cursor-pointer text-muted scaleX-n1-rtl" len="0"></i>
                              </span>
                                        <span class="btn btn-icon btn-text-secondary rounded-pill btn-sm waves-effect waves-light" len="113">
                                <i class="email-next mdi mdi-chevron-right cursor-pointer scaleX-n1-rtl" len="0"></i>
                              </span>
                                    </div>
                                </div>
                            </div>
                            <hr class="container-m-nx m-0" len="0">
                            <!-- Email List: Items -->
                            <div class="email-list pt-0 ps ps__rtl ps--active-y" len="20207">
                                <ul class="list-unstyled m-0" len="19598">
                                    <li class="email-list-item email-marked-read" data-starred="true" data-bs-toggle="sidebar" data-target="#app-email-view" len="1761">
                                        <div class="d-flex align-items-center" len="1684">
                                            <div class="form-check mb-0" len="198">
                                                <input class="email-list-item-input form-check-input" type="checkbox" id="email-1" len="0">
                                                <label class="form-check-label" for="email-1" len="0"></label>
                                            </div>
                                            <i class="email-list-item-bookmark mdi mdi-star-outline mdi-24px d-sm-inline-block d-none cursor-pointer ms-1 me-3" len="0"></i>
                                            <img src="../../assets/img/avatars/1.png" alt="user-avatar" class="d-block flex-shrink-0 rounded-circle me-sm-3 me-0" height="32" width="32" len="0">
                                            <div class="email-list-item-content ms-2 ms-sm-0 me-2" len="256">
                                                <span class="email-list-item-username me-2 h6" len="13" lang="fa">چندلر بینگ</span>
                                                <span class="email-list-item-subject d-xl-inline-block d-block" len="57" lang="fa" style=""> تمرکز بر روی مسائل باز تاثیر گذار از پروژه GitHub</span>
                                            </div>
                                            <div class="email-list-item-meta ms-auto d-flex align-items-center" len="687" style="text-align: right;">
                                                <span class="email-list-item-label badge badge-dot bg-danger d-none d-md-inline-block me-2" data-label="private" len="0"></span>
                                                <small class="email-list-item-time text-muted" len="8" lang="fa">08:40 صبح</small>
                                                <ul class="list-inline email-list-item-actions" len="369">
                                                    <li class="list-inline-item email-unread" len="48"> <i class="mdi mdi-email-outline mdi-24px" len="0"></i> </li>
                                                    <li class="list-inline-item email-delete" len="48"> <i class="mdi mdi-delete-outline mdi-24px" len="0"></i></li>
                                                    <li class="list-inline-item" len="55"> <i class="mdi mdi-alert-circle-outline mdi-24px" len="0"></i> </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="email-list-item email-marked-read" data-sent="true" data-bs-toggle="sidebar" data-target="#app-email-view" len="1920">
                                        <div class="d-flex align-items-center" len="1843">
                                            <div class="form-check mb-0" len="198">
                                                <input class="email-list-item-input form-check-input" type="checkbox" id="email-2" len="0">
                                                <label class="form-check-label" for="email-2" len="0"></label>
                                            </div>
                                            <i class="email-list-item-bookmark mdi mdi-star-outline mdi-24px d-sm-inline-block d-none cursor-pointer ms-1 me-3" len="0"></i>
                                            <img src="../../assets/img/avatars/2.png" alt="user-avatar" class="d-block flex-shrink-0 rounded-circle me-sm-3 me-0" height="32" width="32" len="0">
                                            <div class="email-list-item-content ms-2 ms-sm-0 me-2" len="270">
                                                <span class="email-list-item-username me-2 h6" len="11" lang="fa" style="">راس گلر</span>
                                                <span class="email-list-item-subject d-xl-inline-block d-block" len="73" lang="fa" style=""> هی کیتی، دسر سوفله توتسی رول سوفله هویج کیک هالوا ژله.</span>
                                            </div>
                                            <div class="email-list-item-meta ms-auto d-flex align-items-center" len="832" style="text-align: right;">
                                                <span class="email-list-item-label badge badge-dot bg-primary d-none d-md-inline-block me-2" data-label="important" len="0"></span>
                                                <span class="email-list-item-label badge badge-dot bg-warning d-none d-md-inline-block me-2" data-label="private" len="0"></span>
                                                <small class="email-list-item-time text-muted" len="8" lang="fa">10:12 صبح</small>
                                                <ul class="list-inline email-list-item-actions" len="369">
                                                    <li class="list-inline-item email-unread" len="48"> <i class="mdi mdi-email-outline mdi-24px" len="0"></i> </li>
                                                    <li class="list-inline-item email-delete" len="48"> <i class="mdi mdi-delete-outline mdi-24px" len="0"></i></li>
                                                    <li class="list-inline-item" len="55"> <i class="mdi mdi-alert-circle-outline mdi-24px" len="0"></i> </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="email-list-item email-marked-read" data-draft="true" data-bs-toggle="sidebar" data-target="#app-email-view" len="1952">
                                        <div class="d-flex align-items-center" len="1875">
                                            <div class="form-check mb-0" len="198">
                                                <input class="email-list-item-input form-check-input" type="checkbox" id="email-3" len="0">
                                                <label class="form-check-label" for="email-3" len="0"></label>
                                            </div>
                                            <i class="email-list-item-bookmark mdi mdi-star-outline mdi-24px d-sm-inline-block d-none cursor-pointer ms-1 me-3" len="0"></i>
                                            <div class="avatar avatar-sm d-block flex-shrink-0 me-sm-3 me-0" len="110">
                                                <span class="avatar-initial rounded-circle bg-label-success" len="2" lang="fa">کارشناسی</span>
                                            </div>
                                            <div class="email-list-item-content ms-2 ms-sm-0 me-2" len="265">
                                                <span class="email-list-item-username me-2 h6" len="14" lang="fa">بارنی استینسون</span>
                                                <span class="email-list-item-subject d-xl-inline-block d-block" len="65" lang="fa" style=""> هی کیتی، سوفله پای سیب کارامل سوفله تیرامیسو پنجه خرس.</span>
                                            </div>
                                            <div class="email-list-item-meta ms-auto d-flex align-items-center" len="829" style="text-align: right;">
                                                <span class="email-list-item-attachment mdi mdi-attachment mdi-20px cursor-pointer me-2 float-end float-sm-none" len="0"></span>
                                                <span class="email-list-item-label badge badge-dot bg-primary d-none d-md-inline-block me-2" data-label="company" len="0"></span>
                                                <small class="email-list-item-time text-muted" len="8" lang="fa">12:44 صبح</small>
                                                <ul class="list-inline email-list-item-actions" len="369">
                                                    <li class="list-inline-item email-unread" len="48"> <i class="mdi mdi-email-outline mdi-24px" len="0"></i> </li>
                                                    <li class="list-inline-item email-delete" len="48"> <i class="mdi mdi-delete-outline mdi-24px" len="0"></i></li>
                                                    <li class="list-inline-item" len="55"> <i class="mdi mdi-alert-circle-outline mdi-24px" len="0"></i> </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="email-list-item" data-starred="true" data-bs-toggle="sidebar" data-target="#app-email-view" len="1769">
                                        <div class="d-flex align-items-center" len="1692">
                                            <div class="form-check mb-0" len="198">
                                                <input class="email-list-item-input form-check-input" type="checkbox" id="email-4" len="0">
                                                <label class="form-check-label" for="email-4" len="0"></label>
                                            </div>
                                            <i class="email-list-item-bookmark mdi mdi-star-outline mdi-24px d-sm-inline-block d-none cursor-pointer ms-1 me-3" len="0"></i>
                                            <img src="../../assets/img/avatars/3.png" alt="user-avatar" class="d-block flex-shrink-0 rounded-circle me-sm-3 me-0" height="32" width="32" len="0">
                                            <div class="email-list-item-content ms-2 ms-sm-0 me-2" len="262">
                                                <span class="email-list-item-username me-2 h6" len="13" lang="fa">فوبه بوفی</span>
                                                <span class="email-list-item-subject d-xl-inline-block d-block" len="63" lang="fa" style=""> هی کیتی، تارت کروسانت عناب gummies ماکارون یخ شیرین.</span>
                                            </div>
                                            <div class="email-list-item-meta ms-auto d-flex align-items-center" len="689" style="text-align: right;">
                                                <span class="email-list-item-label badge badge-dot bg-success d-none d-md-inline-block me-2" data-label="work" len="0"></span>
                                                <small class="email-list-item-time text-muted" len="9" lang="fa">دیروز</small>
                                                <ul class="list-inline email-list-item-actions" len="372">
                                                    <li class="list-inline-item email-read" len="53"> <i class="mdi mdi-email-open-outline mdi-24px" len="0"></i> </li>
                                                    <li class="list-inline-item email-delete" len="48"> <i class="mdi mdi-delete-outline mdi-24px" len="0"></i></li>
                                                    <li class="list-inline-item" len="55"> <i class="mdi mdi-alert-circle-outline mdi-24px" len="0"></i> </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="email-list-item email-marked-read" data-spam="true" data-bs-toggle="sidebar" data-target="#app-email-view" len="1781">
                                        <div class="d-flex align-items-center" len="1704">
                                            <div class="form-check mb-0" len="198">
                                                <input class="email-list-item-input form-check-input" type="checkbox" id="email-5" len="0">
                                                <label class="form-check-label" for="email-5" len="0"></label>
                                            </div>
                                            <i class="email-list-item-bookmark mdi mdi-star-outline mdi-24px d-sm-inline-block d-none cursor-pointer ms-1 me-3" len="0"></i>
                                            <img src="../../assets/img/avatars/4.png" alt="user-avatar" class="d-block flex-shrink-0 rounded-circle me-sm-3 me-0" height="32" width="32" len="0">
                                            <div class="email-list-item-content ms-2 ms-sm-0 me-2" len="275">
                                                <span class="email-list-item-username me-2 h6" len="9" lang="fa">تد موزبی</span>
                                                <span class="email-list-item-subject d-xl-inline-block d-block" len="80" lang="fa"> هی کیتی، من عاشق کیک پودینگ شکلات شیرین تیرامیسو عناب هستم من عاشق دانمارکی هستم.</span>
                                            </div>
                                            <div class="email-list-item-meta ms-auto d-flex align-items-center" len="688" style="text-align: right;">
                                                <span class="email-list-item-label badge badge-dot bg-danger d-none d-md-inline-block me-2" data-label="company" len="0"></span>
                                                <small class="email-list-item-time text-muted" len="9" lang="fa">دیروز</small>
                                                <ul class="list-inline email-list-item-actions" len="369">
                                                    <li class="list-inline-item email-unread" len="48"> <i class="mdi mdi-email-outline mdi-24px" len="0"></i> </li>
                                                    <li class="list-inline-item email-delete" len="48"> <i class="mdi mdi-delete-outline mdi-24px" len="0"></i></li>
                                                    <li class="list-inline-item" len="55"> <i class="mdi mdi-alert-circle-outline mdi-24px" len="0"></i> </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="email-list-item" data-trash="true" data-bs-toggle="sidebar" data-target="#app-email-view" len="1809">
                                        <div class="d-flex align-items-center" len="1732">
                                            <div class="form-check mb-0" len="198">
                                                <input class="email-list-item-input form-check-input" type="checkbox" id="email-6" len="0">
                                                <label class="form-check-label" for="email-6" len="0"></label>
                                            </div>
                                            <i class="email-list-item-bookmark mdi mdi-star-outline mdi-24px d-sm-inline-block d-none cursor-pointer ms-1 me-3" len="0"></i>
                                            <div class="avatar avatar-sm d-block flex-shrink-0 me-sm-3 me-0" len="107">
                                                <span class="avatar-initial rounded-circle bg-label-info" len="2" lang="fa">Sk</span>
                                            </div>
                                            <div class="email-list-item-content ms-2 ms-sm-0 me-2" len="269">
                                                <span class="email-list-item-username me-2 h6" len="12" lang="fa">استیسی کوپر</span>
                                                <span class="email-list-item-subject d-xl-inline-block d-block" len="71"><font lang="fa"> هی کیتی، من دانمارکی رو دوست دارم </font><font lang="fa">کیک کوچک من عاشق کیک هویج شکر الو من دوست دارم.</font></span>
                                            </div>
                                            <div class="email-list-item-meta ms-auto d-flex align-items-center" len="685" style="text-align: right;">
                                                <span class="email-list-item-label badge badge-dot bg-primary d-none d-md-inline-block me-2" data-label="work" len="0"></span>
                                                <small class="email-list-item-time text-muted" len="5" lang="fa">5 می</small>
                                                <ul class="list-inline email-list-item-actions" len="372">
                                                    <li class="list-inline-item email-read" len="53"> <i class="mdi mdi-email-open-outline mdi-24px" len="0"></i> </li>
                                                    <li class="list-inline-item email-delete" len="48"> <i class="mdi mdi-delete-outline mdi-24px" len="0"></i></li>
                                                    <li class="list-inline-item" len="55"> <i class="mdi mdi-alert-circle-outline mdi-24px" len="0"></i> </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="email-list-item email-marked-read" data-draft="true" data-bs-toggle="sidebar" data-target="#app-email-view" len="1776">
                                        <div class="d-flex align-items-center" len="1699">
                                            <div class="form-check mb-0" len="198">
                                                <input class="email-list-item-input form-check-input" type="checkbox" id="email-7" len="0">
                                                <label class="form-check-label" for="email-7" len="0"></label>
                                            </div>
                                            <i class="email-list-item-bookmark mdi mdi-star-outline mdi-24px d-sm-inline-block d-none cursor-pointer ms-1 me-3" len="0"></i>
                                            <img src="../../assets/img/avatars/5.png" alt="user-avatar" class="d-block flex-shrink-0 rounded-circle me-sm-3 me-0" height="32" width="32" len="0">
                                            <div class="email-list-item-content ms-2 ms-sm-0 me-2" len="272">
                                                <span class="email-list-item-username me-2 h6" len="12" lang="fa">راشل گرین</span>
                                                <span class="email-list-item-subject d-xl-inline-block d-block" len="74" lang="fa"> هی کیتی، کیک شکلاتی پودینگ شکلاتی بستنی اب نبات چوبی بونبون</span>
                                            </div>
                                            <div class="email-list-item-meta ms-auto d-flex align-items-center" len="686" style="text-align: right;">
                                                <span class="email-list-item-label badge badge-dot bg-warning d-none d-md-inline-block me-2" data-label="company" len="0"></span>
                                                <small class="email-list-item-time text-muted" len="6" lang="fa">15 می</small>
                                                <ul class="list-inline email-list-item-actions" len="369">
                                                    <li class="list-inline-item email-unread" len="48"> <i class="mdi mdi-email-outline mdi-24px" len="0"></i> </li>
                                                    <li class="list-inline-item email-delete" len="48"> <i class="mdi mdi-delete-outline mdi-24px" len="0"></i></li>
                                                    <li class="list-inline-item" len="55"> <i class="mdi mdi-alert-circle-outline mdi-24px" len="0"></i> </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="email-list-item" data-starred="true" data-bs-toggle="sidebar" data-target="#app-email-view" len="1908">
                                        <div class="d-flex align-items-center" len="1831">
                                            <div class="form-check mb-0" len="198">
                                                <input class="email-list-item-input form-check-input" type="checkbox" id="email-8" len="0">
                                                <label class="form-check-label" for="email-8" len="0"></label>
                                            </div>
                                            <i class="email-list-item-bookmark mdi mdi-star-outline mdi-24px d-sm-inline-block d-none cursor-pointer ms-1 me-3" len="0"></i>
                                            <img src="../../assets/img/avatars/6.png" alt="user-avatar" class="d-block flex-shrink-0 rounded-circle me-sm-3 me-0" height="32" width="32" len="0">
                                            <div class="email-list-item-content ms-2 ms-sm-0 me-2" len="261">
                                                <span class="email-list-item-username me-2 h6" len="12" lang="fa">گریس شلبی</span>
                                                <span class="email-list-item-subject d-xl-inline-block d-block" len="63" lang="fa"> هی کیتی، ادامس بستنی خرس ویفر دسر کروسانت.</span>
                                            </div>
                                            <div class="email-list-item-meta ms-auto d-flex align-items-center" len="829" style="text-align: right;">
                                                <span class="email-list-item-attachment mdi mdi-attachment mdi-20px cursor-pointer me-2 float-end float-sm-none" len="0"></span>
                                                <span class="email-list-item-label badge badge-dot bg-danger d-none d-md-inline-block me-2" data-label="private" len="0"></span>
                                                <small class="email-list-item-time text-muted" len="6" lang="fa">20 اوریل</small>
                                                <ul class="list-inline email-list-item-actions" len="372">
                                                    <li class="list-inline-item email-read" len="53"> <i class="mdi mdi-email-open-outline mdi-24px" len="0"></i> </li>
                                                    <li class="list-inline-item email-delete" len="48"> <i class="mdi mdi-delete-outline mdi-24px" len="0"></i></li>
                                                    <li class="list-inline-item" len="55"> <i class="mdi mdi-alert-circle-outline mdi-24px" len="0"></i> </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="email-list-item" data-spam="true" data-bs-toggle="sidebar" data-target="#app-email-view" len="1808">
                                        <div class="d-flex align-items-center" len="1731">
                                            <div class="form-check mb-0" len="198">
                                                <input class="email-list-item-input form-check-input" type="checkbox" id="email-9" len="0">
                                                <label class="form-check-label" for="email-9" len="0"></label>
                                            </div>
                                            <i class="email-list-item-bookmark mdi mdi-star-outline mdi-24px d-sm-inline-block d-none cursor-pointer ms-1 me-3" len="0"></i>
                                            <div class="avatar avatar-sm d-block flex-shrink-0 me-sm-3 me-0" len="109">
                                                <span class="avatar-initial rounded-circle bg-label-danger" len="2" lang="fa">جی اف</span>
                                            </div>
                                            <div class="email-list-item-content ms-2 ms-sm-0 me-2" len="260">
                                                <span class="email-list-item-username me-2 h6" len="10" lang="fa">جیکوب فرای</span>
                                                <span class="email-list-item-subject d-xl-inline-block d-block" len="64" lang="fa"> هی کیتی، کیک شکلاتی پودینگ شکلات نوار بستنی شیرین.</span>
                                            </div>
                                            <div class="email-list-item-meta ms-auto d-flex align-items-center" len="691" style="text-align: right;">
                                                <span class="email-list-item-label badge badge-dot bg-primary d-none d-md-inline-block me-2" data-label="important" len="0"></span>
                                                <small class="email-list-item-time text-muted" len="6" lang="fa">25 مارس</small>
                                                <ul class="list-inline email-list-item-actions" len="372">
                                                    <li class="list-inline-item email-read" len="53"> <i class="mdi mdi-email-open-outline mdi-24px" len="0"></i> </li>
                                                    <li class="list-inline-item email-delete" len="48"> <i class="mdi mdi-delete-outline mdi-24px" len="0"></i></li>
                                                    <li class="list-inline-item" len="55"> <i class="mdi mdi-alert-circle-outline mdi-24px" len="0"></i> </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="email-list-item email-marked-read" data-trash="true" data-bs-toggle="sidebar" data-target="#app-email-view" len="1780">
                                        <div class="d-flex align-items-center" len="1703">
                                            <div class="form-check mb-0" len="200">
                                                <input class="email-list-item-input form-check-input" type="checkbox" id="email-10" len="0">
                                                <label class="form-check-label" for="email-10" len="0"></label>
                                            </div>
                                            <i class="email-list-item-bookmark mdi mdi-star-outline mdi-24px d-sm-inline-block d-none cursor-pointer ms-1 me-3" len="0"></i>
                                            <img src="../../assets/img/avatars/9.png" alt="user-avatar" class="d-block flex-shrink-0 rounded-circle me-sm-3 me-0" height="32" width="32" len="0">
                                            <div class="email-list-item-content ms-2 ms-sm-0 me-2" len="274">
                                                <span class="email-list-item-username me-2 h6" len="17" lang="fa">الیستر کراولی </span>
                                                <span class="email-list-item-subject d-xl-inline-block d-block" len="71"><font lang="fa"> هی کیتی، من دانمارکی رو دوست دارم </font><font lang="fa">کیک کوچک من عاشق کیک هویج شکر الو من دوست دارم.</font></span>
                                            </div>
                                            <div class="email-list-item-meta ms-auto d-flex align-items-center" len="686" style="text-align: right;">
                                                <span class="email-list-item-label badge badge-dot bg-success d-none d-md-inline-block me-2" data-label="company" len="0"></span>
                                                <small class="email-list-item-time text-muted" len="6" lang="fa">25 فوریه</small>
                                                <ul class="list-inline email-list-item-actions" len="369">
                                                    <li class="list-inline-item email-unread" len="48"> <i class="mdi mdi-email-outline mdi-24px" len="0"></i> </li>
                                                    <li class="list-inline-item email-delete" len="48"> <i class="mdi mdi-delete-outline mdi-24px" len="0"></i></li>
                                                    <li class="list-inline-item" len="55"> <i class="mdi mdi-alert-circle-outline mdi-24px" len="0"></i> </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <div class="ps__rail-x" style="left: 0px; bottom: -400px;" len="75"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;" len="0"></div></div><div class="ps__rail-y" style="top: 400px; right: 1119px; height: 249px;" len="75"><div class="ps__thumb-y" tabindex="0" style="top: 154px; height: 95px;" len="0"></div></div><div class="ps__rail-x" style="left: 0px; bottom: -400px;" len="75"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;" len="0"></div></div><div class="ps__rail-y" style="top: 400px; right: 1119px; height: 249px;" len="75"><div class="ps__thumb-y" tabindex="0" style="top: 154px; height: 95px;" len="0"></div></div></div>
                        </div>
                        <div class="app-overlay" len="0"></div>
                    </div>
                    <!-- /Emails List -->

                    <!-- Email View -->
                    <div class="col app-email-view flex-grow-0 bg-body" id="app-email-view" len="17733">
                        <div class="card-body app-email-view-header p-3 py-2" len="5732">
                            <!-- Email View : Title  bar-->
                            <div class="d-flex justify-content-between align-items-center py-2" len="2292">
                                <div class="d-flex align-items-center overflow-hidden" len="324">
                                    <i class="mdi mdi-chevron-left mdi-20px cursor-pointer me-2" data-bs-toggle="sidebar" data-target="#app-email-view" len="0"></i>
                                    <h6 class="text-truncate mb-0 me-2 fw-normal" len="29" lang="fa">تمرکز بر مسائل باز تاثیر گذار</h6>
                                    <span class="badge bg-label-warning rounded-pill" len="9" lang="fa">مهم</span>
                                </div>
                                <!-- Email View : Action  bar-->
                                <div class="d-flex" len="1799">
                                    <div class="dropdown ms-3" len="1738">
                                        <i class="mdi mdi-dots-vertical mdi-24px cursor-pointer" id="dropdownMoreOptions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" len="17">
                                        </i>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMoreOptions" len="1428">
                                            <a class="dropdown-item waves-effect" href="javascript:void(0)" len="151">
                                                <i class="mdi mdi-email-outline me-1" len="0"></i>
                                                <span class="align-middle" len="14" lang="fa">نشاندار کردن به عنوان خواندهنشده</span>
                                            </a>
                                            <a class="dropdown-item waves-effect" href="javascript:void(0)" len="154">
                                                <i class="mdi mdi-email-open-outline me-1" len="0"></i>
                                                <span class="align-middle" len="12" lang="fa">نشانگذاری به عنوان خوانده شده</span>
                                            </a>
                                            <a class="dropdown-item waves-effect" href="javascript:void(0)" len="144">
                                                <i class="mdi mdi-star-outline me-1" len="0"></i>
                                                <span class="align-middle" len="8" lang="fa">افزودن ستاره</span>
                                            </a>
                                            <a class="dropdown-item waves-effect" href="javascript:void(0)" len="158">
                                                <i class="mdi mdi-calendar-month-outline me-1" len="0"></i>
                                                <span class="align-middle" len="12" lang="fa">ایجاد رویداد</span>
                                            </a>
                                            <a class="dropdown-item waves-effect" href="javascript:void(0)" len="138">
                                                <i class="mdi mdi-volume-off me-1" len="0"></i>
                                                <span class="align-middle" len="4" lang="fa">قطع</span>
                                            </a>
                                            <a class="dropdown-item waves-effect" href="javascript:void(0)" len="144">
                                                <i class="mdi mdi-printer-outline me-1" len="0"></i>
                                                <span class="align-middle" len="5" lang="fa">چاپ</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="app-email-view-hr mx-n3 my-2" len="0">
                            <div class="d-flex justify-content-between align-items-center" len="3172">
                                <div class="d-flex align-items-center" len="2783">
                                    <div class="btn btn-text-secondary btn-icon rounded-pill waves-effect waves-light" len="94">
                                        <i class="mdi mdi-delete-outline mdi-24px cursor-pointer" len="0"></i>
                                    </div>
                                    <div class="btn btn-text-secondary btn-icon rounded-pill waves-effect waves-light" len="148">
                                        <i class="mdi mdi-email-outline mdi-24px cursor-pointer" data-bs-toggle="sidebar" data-target="#app-email-view" len="0"></i>
                                    </div>
                                    <div class="dropdown btn btn-text-secondary btn-icon rounded-pill waves-effect waves-light" len="1026">
                                        <i class="mdi mdi-folder-outline cursor-pointer mdi-24px" id="dropdownMenuFolderTwo" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" len="17">
                                        </i>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuFolderTwo" len="711">
                                            <a class="dropdown-item waves-effect" href="javascript:void(0)" len="148">
                                                <i class="mdi mdi-alert-circle-outline me-1" len="0"></i>
                                                <span class="align-middle" len="4" lang="fa">اسپم</span>
                                            </a>
                                            <a class="dropdown-item waves-effect" href="javascript:void(0)" len="143">
                                                <i class="mdi mdi-pencil-outline me-1" len="0"></i>
                                                <span class="align-middle" len="5" lang="fa">پیش نویس</span>
                                            </a>
                                            <a class="dropdown-item waves-effect" href="javascript:void(0)" len="142">
                                                <i class="mdi mdi-email-outline me-1" len="0"></i>
                                                <span class="align-middle" len="5" lang="fa">زباله</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="dropdown btn btn-text-secondary btn-icon rounded-pill waves-effect waves-light" len="1068">
                                        <i class="mdi mdi-label-outline cursor-pointer mdi-24px" id="dropdownLabelTwo" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" len="17">
                                        </i>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownLabelTwo" len="764">
                                            <a class="dropdown-item waves-effect" href="javascript:void(0)" len="162">
                                                <i class="mdi mdi-circle-medium text-success mdi-24px" len="0"></i>
                                                <span class="align-middle" len="8" lang="fa">کارگاه</span>
                                            </a>
                                            <a class="dropdown-item waves-effect" href="javascript:void(0)" len="161">
                                                <i class="mdi mdi-circle-medium text-primary mdi-24px" len="0"></i>
                                                <span class="align-middle" len="7" lang="fa">شرکت</span>
                                            </a>
                                            <a class="dropdown-item waves-effect" href="javascript:void(0)" len="163">
                                                <i class="mdi mdi-circle-medium text-warning mdi-24px" len="0"></i>
                                                <span class="align-middle" len="9" lang="fa">مهم</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center flex-wrap justify-content-end" len="232">
                                    <span class="d-sm-block d-none mx-3" len="11" lang="fa">1-10 از 653</span>
                                    <i class="mdi mdi-chevron-left cursor-pointer text-muted me-2" len="0"></i>
                                    <i class="mdi mdi-chevron-right cursor-pointer" len="0"></i>
                                </div>
                            </div>
                        </div>
                        <hr class="m-0" len="0">
                        <!-- Email View : Content-->
                        <div class="app-email-view-content py-4 ps ps__rtl ps--active-y" len="11783">
                            <p class="email-earlier-msgs text-center text-muted cursor-pointer mb-5" len="17" lang="fa">۱ پیام قبلی</p>
                            <!-- Email View : Previous mails-->
                            <div class="card email-card-prev mx-sm-4 mx-3 border" len="3277">
                                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-bottom" len="1894">
                                    <div class="d-flex align-items-center mb-sm-0 mb-3" len="351">
                                        <img src="../../assets/img/avatars/2.png" alt="user-avatar" class="flex-shrink-0 rounded-circle me-3" height="40" width="40" len="0">
                                        <div class="flex-grow-1 ms-1" len="141">
                                            <h6 class="m-0" len="11" lang="fa">راس گلر</h6>
                                            <small class="text-muted" len="20" lang="fa">rossGeller@email.com</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center" len="1397">
                                        <p class="mb-0 me-3 text-muted" len="24" lang="fa">20 ژوئن 2020، 08:30</p>
                                        <i class="mdi mdi-attachment mdi-20px cursor-pointer me-3" len="0"></i>
                                        <i class="email-list-item-bookmark mdi mdi-star-outline mdi-20px cursor-pointer me-3" len="0"></i>
                                        <div class="dropdown me-3" len="1068">
                                            <i class="mdi mdi-dots-vertical mdi-20px cursor-pointer" id="dropdownEmailTwo" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" len="19">
                                            </i>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownEmailTwo" len="756">
                                                <a class="dropdown-item scroll-to-reply waves-effect" href="javascript:void(0)" len="148">
                                                    <i class="mdi mdi-reply-outline me-1" len="0"></i>
                                                    <span class="align-middle" len="5" lang="fa">پاسخ</span>
                                                </a>
                                                <a class="dropdown-item waves-effect" href="javascript:void(0)" len="150">
                                                    <i class="mdi mdi-share-outline me-1" len="0"></i>
                                                    <span class="align-middle" len="7" lang="fa">جلو</span>
                                                </a>
                                                <a class="dropdown-item waves-effect" href="javascript:void(0)" len="156">
                                                    <i class="mdi mdi-alert-circle-outline me-1" len="0"></i>
                                                    <span class="align-middle" len="6" lang="fa">گزارش</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body" len="1212">
                                    <p class="fw-semibold mt-5" len="10" lang="fa">سلام و احوال&zwnj;پرسی!</p>
                                    <p len="431"><font lang="fa"> این یک واقعیت قدیمی است که یک خواننده با محتوای قابل خواندن یک صفحه در هنگام نگاه کردن به طرح ان منحرف می شود. </font><font lang="fa">نکته استفاده از Lorem Ipsum این است که توزیع حروف کم و بیش عادی است، به جای استفاده از "محتوا در اینجا، محتوا در اینجا"، و ان را مانند انگلیسی قابل خواندن به نظر می رسد. </font></p>
                                    <p len="282" lang="fa"> تغییرات زیادی از معابر Lorem Ipsum در دسترس است، اما اکثر انها دچار تغییر در برخی از فرم ها، با طنز تزریق شده یا کلمات تصادفی شده اند که حتی کمی باور نکردنی نیستند. </p>
                                    <p class="mb-0" len="16" lang="fa">با احترام،</p>
                                    <p class="fw-semibold mb-0" len="18" lang="fa">تیم طراحی تیک تاک</p>
                                    <hr len="0">
                                    <p class="text-muted mb-2" len="11" lang="fa">پیوست</p>
                                    <div class="cursor-pointer" len="144">
                                        <i class="mdi mdi-file-document-outline" len="0"></i>
                                        <span class="align-middle ms-1" len="11" lang="fa">گزارش.xlsx</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Email View : Last mail-->
                            <div class="card email-card-last mx-sm-4 mx-3 mt-4" len="3258">
                                <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-bottom" len="1875">
                                    <div class="d-flex align-items-center mb-sm-0 mb-3" len="351">
                                        <img src="../../assets/img/avatars/1.png" alt="user-avatar" class="flex-shrink-0 rounded-circle me-3" height="40" width="40" len="0">
                                        <div class="flex-grow-1 ms-1" len="141">
                                            <h6 class="m-0" len="13" lang="fa">چندلر بینگ</h6>
                                            <small class="text-muted" len="18" lang="fa">iAmAhoot@email.com</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center" len="1378">
                                        <p class="mb-0 me-3 text-muted" len="24" lang="fa">20 ژوئن 2020، 08:10</p>
                                        <i class="mdi mdi-attachment cursor-pointer me-3 mdi-20px" len="0"></i>
                                        <i class="email-list-item-bookmark mdi mdi-star-outline mdi-20px cursor-pointer me-3" len="0"></i>
                                        <div class="dropdown me-3" len="1049">
                                            <i class="mdi mdi-dots-vertical cursor-pointer mdi-20px" id="dropdownEmailOne" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" len="0"></i>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownEmailOne" len="756">
                                                <a class="dropdown-item scroll-to-reply waves-effect" href="javascript:void(0)" len="148">
                                                    <i class="mdi mdi-reply-outline me-1" len="0"></i>
                                                    <span class="align-middle" len="5" lang="fa">پاسخ</span>
                                                </a>
                                                <a class="dropdown-item waves-effect" href="javascript:void(0)" len="150">
                                                    <i class="mdi mdi-share-outline me-1" len="0"></i>
                                                    <span class="align-middle" len="7" lang="fa">جلو</span>
                                                </a>
                                                <a class="dropdown-item waves-effect" href="javascript:void(0)" len="156">
                                                    <i class="mdi mdi-alert-circle-outline me-1" len="0"></i>
                                                    <span class="align-middle" len="6" lang="fa">گزارش</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body" len="1212">
                                    <p class="fw-semibold mt-5" len="10" lang="fa">سلام و احوال&zwnj;پرسی!</p>
                                    <p len="431"><font lang="fa"> این یک واقعیت قدیمی است که یک خواننده با محتوای قابل خواندن یک صفحه در هنگام نگاه کردن به طرح ان منحرف می شود. </font><font lang="fa">نکته استفاده از Lorem Ipsum این است که توزیع حروف کم و بیش عادی است، به جای استفاده از "محتوا در اینجا، محتوا در اینجا"، و ان را مانند انگلیسی قابل خواندن به نظر می رسد. </font></p>
                                    <p len="282" lang="fa"> تغییرات زیادی از معابر Lorem Ipsum در دسترس است، اما اکثر انها دچار تغییر در برخی از فرم ها، با طنز تزریق شده یا کلمات تصادفی شده اند که حتی کمی باور نکردنی نیستند. </p>
                                    <p class="mb-0" len="16" lang="fa">با احترام،</p>
                                    <p class="fw-semibold mb-0" len="18" lang="fa">تیم طراحی تیک تاک</p>
                                    <hr len="0">
                                    <p class="text-muted mb-2" len="11" lang="fa">پیوست</p>
                                    <div class="cursor-pointer" len="144">
                                        <i class="mdi mdi-file-document-outline" len="0"></i>
                                        <span class="align-middle ms-1" len="11" lang="fa">گزارش.xlsx</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Email View : Reply mail-->
                            <div class="email-reply card mt-4 mx-sm-4 mx-3 border" len="4501">
                                <h6 class="card-header border-0" len="20" lang="fa">پاسخ به راس گلر</h6>
                                <div class="card-body pt-0 px-3" len="4367">
                                    <div class="d-flex justify-content-start" len="3240">
                                        <div class="email-reply-toolbar border-0 w-100 ps-0 ql-toolbar ql-snow" len="3130">
                                <span class="ql-formats me-0" len="3057">
                                  <button class="ql-bold" type="button" len="277"><svg viewBox="0 0 18 18" len="246"> <path class="ql-stroke" d="M5,4H9.5A2.5,2.5,0,0,1,12,6.5v0A2.5,2.5,0,0,1,9.5,9H5A0,0,0,0,1,5,9V4A0,0,0,0,1,5,4Z" len="0"></path> <path class="ql-stroke" d="M5,9h5.5A2.5,2.5,0,0,1,13,11.5v0A2.5,2.5,0,0,1,10.5,14H5a0,0,0,0,1,0,0V9A0,0,0,0,1,5,9Z" len="0"></path> </svg></button>
                                  <button class="ql-italic" type="button" len="218"><svg viewBox="0 0 18 18" len="187"> <line class="ql-stroke" x1="7" x2="13" y1="4" y2="4" len="0"></line> <line class="ql-stroke" x1="5" x2="11" y1="14" y2="14" len="0"></line> <line class="ql-stroke" x1="8" x2="10" y1="14" y2="4" len="0"></line> </svg></button>
                                  <button class="ql-underline" type="button" len="206"><svg viewBox="0 0 18 18" len="175"> <path class="ql-stroke" d="M5,3V9a4.012,4.012,0,0,0,4,4H9a4.012,4.012,0,0,0,4-4V3" len="0"></path> <rect class="ql-fill" height="1" rx="0.5" ry="0.5" width="12" x="3" y="15" len="0"></rect> </svg></button>
                                  <button class="ql-list" value="ordered" type="button" len="750"><svg viewBox="0 0 18 18" len="719"> <line class="ql-stroke" x1="7" x2="15" y1="4" y2="4" len="0"></line> <line class="ql-stroke" x1="7" x2="15" y1="9" y2="9" len="0"></line> <line class="ql-stroke" x1="7" x2="15" y1="14" y2="14" len="0"></line> <line class="ql-stroke ql-thin" x1="2.5" x2="4.5" y1="5.5" y2="5.5" len="0"></line> <path class="ql-fill" d="M3.5,6A0.5,0.5,0,0,1,3,5.5V3.085l-0.276.138A0.5,0.5,0,0,1,2.053,3c-0.124-.247-0.023-0.324.224-0.447l1-.5A0.5,0.5,0,0,1,4,2.5v3A0.5,0.5,0,0,1,3.5,6Z" len="0"></path> <path class="ql-stroke ql-thin" d="M4.5,10.5h-2c0-.234,1.85-1.076,1.85-2.234A0.959,0.959,0,0,0,2.5,8.156" len="0"></path> <path class="ql-stroke ql-thin" d="M2.5,14.846a0.959,0.959,0,0,0,1.85-.109A0.7,0.7,0,0,0,3.75,14a0.688,0.688,0,0,0,.6-0.736,0.959,0.959,0,0,0-1.85-.109" len="0"></path> </svg></button>
                                  <button class="ql-list" value="bullet" type="button" len="399"><svg viewBox="0 0 18 18" len="368"> <line class="ql-stroke" x1="6" x2="15" y1="4" y2="4" len="0"></line> <line class="ql-stroke" x1="6" x2="15" y1="9" y2="9" len="0"></line> <line class="ql-stroke" x1="6" x2="15" y1="14" y2="14" len="0"></line> <line class="ql-stroke" x1="3" x2="3" y1="4" y2="4" len="0"></line> <line class="ql-stroke" x1="3" x2="3" y1="9" y2="9" len="0"></line> <line class="ql-stroke" x1="3" x2="3" y1="14" y2="14" len="0"></line> </svg></button>
                                  <button class="ql-link" type="button" len="425"><svg viewBox="0 0 18 18" len="394"> <line class="ql-stroke" x1="7" x2="11" y1="7" y2="11" len="0"></line> <path class="ql-even ql-stroke" d="M8.9,4.577a3.476,3.476,0,0,1,.36,4.679A3.476,3.476,0,0,1,4.577,8.9C3.185,7.5,2.035,6.4,4.217,4.217S7.5,3.185,8.9,4.577Z" len="0"></path> <path class="ql-even ql-stroke" d="M13.423,9.1a3.476,3.476,0,0,0-4.679-.36,3.476,3.476,0,0,0,.36,4.679c1.392,1.392,2.5,2.542,4.679.36S14.815,10.5,13.423,9.1Z" len="0"></path> </svg></button>
                                  <button class="ql-image" type="button" len="248"><svg viewBox="0 0 18 18" len="217"> <rect class="ql-stroke" height="10" width="12" x="3" y="4" len="0"></rect> <circle class="ql-fill" cx="6" cy="7" r="1" len="0"></circle> <polyline class="ql-even ql-fill" points="5 12 5 11 7 9 8 10 11 7 13 9 13 12 5 12" len="0"></polyline> </svg></button>
                                </span>
                                        </div>
                                    </div>
                                    <div class="email-reply-editor ql-container ql-snow" len="475"><div class="ql-editor ql-blank" data-gramm="false" contenteditable="true" data-placeholder="پیام خودرا بنویسید..." len="11"><p len="4"><br len="0"></p></div><div class="ql-clipboard" contenteditable="true" tabindex="-1" len="0"></div><div class="ql-tooltip ql-hidden" len="233"><a class="ql-preview" rel="noopener noreferrer" target="_blank" href="about:blank" len="0"></a><input type="text" data-formula="e=mc^2" data-link="https://quilljs.com" data-video="Embed URL" len="0"><a class="ql-action" len="0"></a><a class="ql-remove" len="0"></a></div></div>
                                    <div class="d-flex justify-content-end align-items-center" len="422">
                                        <div class="cursor-pointer me-3" len="134">
                                            <i class="mdi mdi-attachment" len="0"></i>
                                            <span class="align-middle" len="11" lang="fa">پیوست</span>
                                        </div>
                                        <button class="btn btn-primary waves-effect waves-light" len="134">
                                            <i class="mdi mdi-send-outline me-1" len="0"></i>
                                            <span class="align-middle" len="4" lang="fa">ارسال</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="ps__rail-x" style="left: 0px; bottom: 0px;" len="75"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;" len="0"></div></div><div class="ps__rail-y" style="top: 0px; height: 639px; right: 1120px;" len="77"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 547px;" len="0"></div></div></div>
                    </div>
                    <!-- Email View -->
                </div>
            </div>

            <!-- Compose Email -->
            <div class="app-email-compose modal" id="emailComposeSidebar" tabindex="-1" aria-labelledby="emailComposeSidebar" aria-hidden="true" len="10186">
                <div class="modal-dialog m-0 me-md-4 mb-4 modal-lg" len="10120">
                    <div class="modal-content p-0" len="10071">
                        <div class="modal-header bg-body py-3" len="296">
                            <p class="modal-title fw-semibold fs-5" len="12" lang="fa">نوشتن ایمیل</p>
                            <div class="d-flex align-items-center gap-2" len="158">
                                <i class="mdi mdi-minus" len="0"></i>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" len="0"></button>
                            </div>
                        </div>
                        <div class="modal-body flex-grow-1 pb-sm-0 p-4 py-2" len="9646">
                            <form class="email-compose-form">
                                <div class="email-compose-to d-flex justify-content-between align-items-center" len="2003">
                                    <label class="form-label mb-0 fs-6 text-muted" for="emailContacts" len="3" lang="fa">به:</label>
                                    <div class="select2-primary border-0 shadow-none flex-grow-1 mx-2" len="1537">
                                        <div class="position-relative" len="1468"><select class="select2 select-email-contacts form-select select2-hidden-accessible" id="emailContacts" name="emailContacts" multiple="" data-select2-id="emailContacts" tabindex="-1" aria-hidden="true" style="text-align: right;">
                                                <option data-avatar="1.png" value="Jane Foster" len="11">جین فاستر</option>
                                                <option data-avatar="3.png" value="Donna Frank" len="11">دونا فرانک</option>
                                                <option data-avatar="5.png" value="Gabrielle Robertson" len="19">گابریل رابرتسون</option>
                                                <option data-avatar="7.png" value="Lori Spears" len="11">لوری اسپیرز</option>
                                                <option data-avatar="9.png" value="Sandy Vega" len="10">سندی وگا</option>
                                                <option data-avatar="11.png" value="Cheryl May" len="10">شریل می</option>
                                            </select><span class="select2 select2-container select2-container--default" dir="rtl" data-select2-id="1" style="width: auto;" len="581"><span class="selection" len="493"><span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1" aria-disabled="false" len="331"><ul class="select2-selection__rendered" len="286"><li class="select2-search select2-search--inline" len="231"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="searchbox" aria-autocomplete="list" placeholder="انتخاب ارزش" style="width: 0px;" len="0"></li></ul></span></span><span class="dropdown-wrapper" aria-hidden="true" len="0"></span></span></div>
                                    </div>
                                    <div class="email-compose-toggle-wrapper" len="209">
                                        <font><font lang="fa"><a class="email-compose-toggle-cc text-body" href="javascript:void(0);" len="4">سی سی |</a> </font><font lang="fa"><a class="email-compose-toggle-bcc text-body" href="javascript:void(0);" len="3">Bcc</a></font></font>
                                    </div>
                                </div>

                                <div class="email-compose-cc d-none" len="366">
                                    <hr class="container-m-nx my-2" len="0">
                                    <div class="d-flex align-items-center" len="246">
                                        <label for="email-cc" class="form-label mb-0 fw-6 text-muted" len="3" lang="fa">Cc:</label>
                                        <input type="text" class="form-control border-0 shadow-none flex-grow-1 mx-2" id="email-cc" placeholder="someone@email.com" len="0">
                                    </div>
                                </div>
                                <div class="email-compose-bcc d-none" len="353">
                                    <hr class="container-m-nx my-2" len="0">
                                    <div class="d-flex align-items-center" len="233">
                                        <label for="email-bcc" class="form-label mb-0" len="4" lang="fa">Bcc:</label>
                                        <input type="text" class="form-control border-0 shadow-none flex-grow-1 mx-2" id="email-bcc" placeholder="someone@email.com" len="0">
                                    </div>
                                </div>
                                <hr class="container-m-nx my-2" len="0">
                                <div class="email-compose-subject d-flex align-items-center mb-2" len="253">
                                    <label for="email-subject" class="form-label mb-0 fs-6 text-muted" len="8" lang="fa">موضوع:</label>
                                    <input type="text" class="form-control border-0 shadow-none flex-grow-1 mx-2" id="email-subject" placeholder="Project Details" len="0">
                                </div>
                                <div class="email-compose-message container-m-nx" len="3860">
                                    <div class="d-flex justify-content-end" len="3243">
                                        <div class="email-editor-toolbar border-bottom-0 w-100 ql-toolbar ql-snow" len="3130">
                                <span class="ql-formats me-0" len="3057">
                                  <button class="ql-bold" type="button" len="277"><svg viewBox="0 0 18 18" len="246"> <path class="ql-stroke" d="M5,4H9.5A2.5,2.5,0,0,1,12,6.5v0A2.5,2.5,0,0,1,9.5,9H5A0,0,0,0,1,5,9V4A0,0,0,0,1,5,4Z" len="0"></path> <path class="ql-stroke" d="M5,9h5.5A2.5,2.5,0,0,1,13,11.5v0A2.5,2.5,0,0,1,10.5,14H5a0,0,0,0,1,0,0V9A0,0,0,0,1,5,9Z" len="0"></path> </svg></button>
                                  <button class="ql-italic" type="button" len="218"><svg viewBox="0 0 18 18" len="187"> <line class="ql-stroke" x1="7" x2="13" y1="4" y2="4" len="0"></line> <line class="ql-stroke" x1="5" x2="11" y1="14" y2="14" len="0"></line> <line class="ql-stroke" x1="8" x2="10" y1="14" y2="4" len="0"></line> </svg></button>
                                  <button class="ql-underline" type="button" len="206"><svg viewBox="0 0 18 18" len="175"> <path class="ql-stroke" d="M5,3V9a4.012,4.012,0,0,0,4,4H9a4.012,4.012,0,0,0,4-4V3" len="0"></path> <rect class="ql-fill" height="1" rx="0.5" ry="0.5" width="12" x="3" y="15" len="0"></rect> </svg></button>
                                  <button class="ql-list" value="ordered" type="button" len="750"><svg viewBox="0 0 18 18" len="719"> <line class="ql-stroke" x1="7" x2="15" y1="4" y2="4" len="0"></line> <line class="ql-stroke" x1="7" x2="15" y1="9" y2="9" len="0"></line> <line class="ql-stroke" x1="7" x2="15" y1="14" y2="14" len="0"></line> <line class="ql-stroke ql-thin" x1="2.5" x2="4.5" y1="5.5" y2="5.5" len="0"></line> <path class="ql-fill" d="M3.5,6A0.5,0.5,0,0,1,3,5.5V3.085l-0.276.138A0.5,0.5,0,0,1,2.053,3c-0.124-.247-0.023-0.324.224-0.447l1-.5A0.5,0.5,0,0,1,4,2.5v3A0.5,0.5,0,0,1,3.5,6Z" len="0"></path> <path class="ql-stroke ql-thin" d="M4.5,10.5h-2c0-.234,1.85-1.076,1.85-2.234A0.959,0.959,0,0,0,2.5,8.156" len="0"></path> <path class="ql-stroke ql-thin" d="M2.5,14.846a0.959,0.959,0,0,0,1.85-.109A0.7,0.7,0,0,0,3.75,14a0.688,0.688,0,0,0,.6-0.736,0.959,0.959,0,0,0-1.85-.109" len="0"></path> </svg></button>
                                  <button class="ql-list" value="bullet" type="button" len="399"><svg viewBox="0 0 18 18" len="368"> <line class="ql-stroke" x1="6" x2="15" y1="4" y2="4" len="0"></line> <line class="ql-stroke" x1="6" x2="15" y1="9" y2="9" len="0"></line> <line class="ql-stroke" x1="6" x2="15" y1="14" y2="14" len="0"></line> <line class="ql-stroke" x1="3" x2="3" y1="4" y2="4" len="0"></line> <line class="ql-stroke" x1="3" x2="3" y1="9" y2="9" len="0"></line> <line class="ql-stroke" x1="3" x2="3" y1="14" y2="14" len="0"></line> </svg></button>
                                  <button class="ql-link" type="button" len="425"><svg viewBox="0 0 18 18" len="394"> <line class="ql-stroke" x1="7" x2="11" y1="7" y2="11" len="0"></line> <path class="ql-even ql-stroke" d="M8.9,4.577a3.476,3.476,0,0,1,.36,4.679A3.476,3.476,0,0,1,4.577,8.9C3.185,7.5,2.035,6.4,4.217,4.217S7.5,3.185,8.9,4.577Z" len="0"></path> <path class="ql-even ql-stroke" d="M13.423,9.1a3.476,3.476,0,0,0-4.679-.36,3.476,3.476,0,0,0,.36,4.679c1.392,1.392,2.5,2.542,4.679.36S14.815,10.5,13.423,9.1Z" len="0"></path> </svg></button>
                                  <button class="ql-image" type="button" len="248"><svg viewBox="0 0 18 18" len="217"> <rect class="ql-stroke" height="10" width="12" x="3" y="4" len="0"></rect> <circle class="ql-fill" cx="6" cy="7" r="1" len="0"></circle> <polyline class="ql-even ql-fill" points="5 12 5 11 7 9 8 10 11 7 13 9 13 12 5 12" len="0"></polyline> </svg></button>
                                </span>
                                        </div>
                                    </div>
                                    <div class="email-editor ql-container ql-snow" len="475"><div class="ql-editor ql-blank" data-gramm="false" contenteditable="true" data-placeholder="پیام خودرا بنویسید..." len="11"><p len="4"><br len="0"></p></div><div class="ql-clipboard" contenteditable="true" tabindex="-1" len="0"></div><div class="ql-tooltip ql-hidden" len="233"><a class="ql-preview" rel="noopener noreferrer" target="_blank" href="about:blank" len="0"></a><input type="text" data-formula="e=mc^2" data-link="https://quilljs.com" data-video="Embed URL" len="0"><a class="ql-action" len="0"></a><a class="ql-remove" len="0"></a></div></div>
                                </div>
                                <hr class="container-m-nx mt-0 mb-2" len="0">
                                <div class="email-compose-actions d-flex justify-content-between align-items-center mb-2" len="2169">
                                    <div class="d-flex align-items-center" len="1010">
                                        <div class="btn-group" len="750">
                                            <button type="reset" class="btn btn-primary waves-effect waves-light" data-bs-dismiss="modal" aria-label="Close" len="4" lang="fa">ارسال</button>
                                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split waves-effect waves-light" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" len="92">
                                                <span class="visually-hidden" len="15" lang="fa">زدن ضامن کشویی</span>
                                            </button>
                                            <ul class="dropdown-menu" len="240">
                                                <li len="82"><a class="dropdown-item waves-effect" href="javascript:void(0);" len="13" lang="fa">زمانبندی ارسال</a></li>
                                                <li len="79"><a class="dropdown-item waves-effect" href="javascript:void(0);" len="10" lang="fa">ذخیرۀ پیشنویس</a></li>
                                            </ul>
                                        </div>
                                        <label for="attach-file" len="63"><i class="mdi mdi-attachment mdi-20px cursor-pointer ms-2" len="0"></i></label>
                                        <input type="file" name="file-input" class="d-none" id="attach-file" len="0">
                                    </div>
                                    <div class="d-flex align-items-center" len="1026">
                                        <div class="dropdown" len="807">
                                            <i class="mdi mdi-dots-vertical mdi-20px cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false" id="dropdownMoreActions" len="0"></i>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMoreActions" len="549">
                                                <li len="75"><button type="button" class="dropdown-item waves-effect" len="9" lang="fa">افزودن برچسب</button></li>
                                                <li len="81"><button type="button" class="dropdown-item waves-effect" len="15" lang="fa">حالت متن ساده</button></li>
                                                <li len="73">
                                                    <hr class="dropdown-divider" len="0">
                                                </li>
                                                <li len="71"><button type="button" class="dropdown-item waves-effect" len="5" lang="fa">چاپ</button></li>
                                                <li len="80"><button type="button" class="dropdown-item waves-effect" len="14" lang="fa">بررسی املا</button></li>
                                            </ul>
                                        </div>
                                        <button type="reset" class="btn ps-3 pe-0" data-bs-dismiss="modal" aria-label="Close" len="47"><i class="mdi mdi-delete-outline mdi-20px" len="0"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Compose Email -->
        </div>
    </div>
    <!-- / Content -->

    <div class="report-wrap">

        <div class="row" style="margin-bottom:10px;">
            <div class="col s12">
                <h4 class="report-title">{{ $thispage['title'] }}</h4>
                <p class="report-subtitle">نمای کلی از قیف پذیرش، وضعیت پورتفو، عملکرد و بازدهی سرمایه‌گذاری.</p>
            </div>
        </div>

        {{-- KPI --}}
        <div class="kpi-row">
            <div class="kpi-col">
                <div class="card kpi-card" style="background:#0ea5e9;color:#fff;">
                    <div class="card-content center">
                        <p class="kpi-value">{{ $dealFunnel['data'][0] }}</p>
                        <p class="kpi-label">کل ورودی‌ها (YTD)</p>
                    </div>
                </div>
            </div>

            <div class="kpi-col">
                <div class="card kpi-card" style="background:#10b981;color:#fff;">
                    <div class="card-content center">
                        <p class="kpi-value">{{ end($fundMetrics['tvpi']) }}</p>
                        <p class="kpi-label">TVPI فعلی</p>
                    </div>
                </div>
            </div>

            <div class="kpi-col">
                <div class="card kpi-card" style="background:#6366f1;color:#fff;">
                    <div class="card-content center">
                        <p class="kpi-value">{{ $portfolioHealth['data'][1] }}</p>
                        <p class="kpi-label">شرکت‌های «در حال رشد»</p>
                    </div>
                </div>
            </div>

            <div class="kpi-col">
                <div class="card kpi-card" style="background:#f97316;color:#fff;">
                    <div class="card-content center">
                        <p class="kpi-value">{{ $portfolioKpi['runway'][count($portfolioKpi['runway'])-1] }}</p>
                        <p class="kpi-label">میانگین Runway (ماه)</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="report-grid">

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head">
                            <h6>قیف پذیرش Deal Flow</h6>
                            <span class="card-hint">نرخ ریزش مرحله‌ای</span>
                        </div>
                        <div class="chart-box">
                            <canvas id="dealFunnelChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head">
                            <h6>توزیع Strategic Fit</h6>
                            <span class="card-hint">کیفیت ورودی‌ها</span>
                        </div>
                        <div class="chart-box">
                            <canvas id="strategicFitChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head">
                            <h6>توزیع سرمایه بر اساس حوزه</h6>
                            <span class="card-hint">ترکیب پورتفو</span>
                        </div>
                        <div class="chart-box">
                            <canvas id="sectorAllocationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head">
                            <h6>سرمایه‌گذاری بر اساس مرحله</h6>
                            <span class="card-hint">تمرکز استیج</span>
                        </div>
                        <div class="chart-box">
                            <canvas id="stageAllocationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head">
                            <h6>روند KPIهای پورتفو</h6>
                            <span class="card-hint">MRR / Burn / Runway</span>
                        </div>
                        <div class="chart-box tall">
                            <canvas id="kpiTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head">
                            <h6>سلامت پورتفو</h6>
                            <span class="card-hint">ریسک و آمادگی خروج</span>
                        </div>
                        <div class="chart-box">
                            <canvas id="portfolioHealthChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head">
                            <h6>تایم‌لاین خروج‌ها</h6>
                            <span class="card-hint">تعداد + ارزش خروج</span>
                        </div>
                        <div class="chart-box">
                            <canvas id="exitTimelineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head">
                            <h6>عملکرد شرکت‌ها</h6>
                            <span class="card-hint">IRR و رشد ماهانه</span>
                        </div>
                        <div class="chart-box">
                            <canvas id="companyPerformanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="report-col" style="flex:1 1 100%;">
                <div class="card report-card hoverable">
                    <div class="card-content">
                        <div class="card-head">
                            <h6>بازدهی سرمایه (TVPI / DPI / RVPI)</h6>
                            <span class="card-hint">روند تجمیعی صندوق</span>
                        </div>
                        <div class="chart-box full">
                            <canvas id="fundMetricsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/vendor/libs/chartjs/chartjs.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (!window.Chart) return;

            // ---------- Global minimal defaults ----------
            Chart.defaults.font.family = 'Vazirmatn, IRANSans, system-ui, -apple-system, Segoe UI, Roboto';
            Chart.defaults.color = '#374151';
            Chart.defaults.plugins.legend.labels.usePointStyle = true;
            Chart.defaults.plugins.legend.labels.boxWidth = 8;
            Chart.defaults.plugins.legend.labels.boxHeight = 8;

            const gridColor = 'rgba(17,24,39,.06)';
            const borderColor = 'rgba(17,24,39,.12)';

            const baseOptions = {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        backgroundColor: 'rgba(17,24,39,.92)',
                        padding: 10,
                        cornerRadius: 10,
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                }
            };

            // 1) Deal Funnel
            new Chart(document.getElementById('dealFunnelChart'), {
                type: 'bar',
                data: {
                    labels: @json($dealFunnel['labels']),
                    datasets: [{
                        label: 'تعداد',
                        data: @json($dealFunnel['data']),
                        borderRadius: 10,
                        backgroundColor: 'rgba(14,165,233,.85)'
                    }]
                },
                options: {
                    ...baseOptions,
                    indexAxis: 'y',
                    scales: {
                        x: { grid: { color: gridColor }, border: { color: borderColor } },
                        y: { grid: { display: false }, border: { display: false } }
                    },
                    plugins: { ...baseOptions.plugins, legend: { display: false } }
                }
            });

            // 2) Strategic Fit
            new Chart(document.getElementById('strategicFitChart'), {
                type: 'bar',
                data: {
                    labels: @json($strategicFit['labels']),
                    datasets: [{
                        label: 'تعداد',
                        data: @json($strategicFit['data']),
                        borderRadius: 10,
                        backgroundColor: 'rgba(99,102,241,.85)'
                    }]
                },
                options: {
                    ...baseOptions,
                    scales: {
                        x: { grid: { display: false }, border: { display: false } },
                        y: { grid: { color: gridColor }, border: { color: borderColor } }
                    },
                    plugins: { ...baseOptions.plugins, legend: { display: false } }
                }
            });

            // 3) Sector Allocation
            new Chart(document.getElementById('sectorAllocationChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($sectorAllocation['labels']),
                    datasets: [{
                        data: @json($sectorAllocation['data']),
                        backgroundColor: [
                            'rgba(14,165,233,.85)',
                            'rgba(16,185,129,.85)',
                            'rgba(249,115,22,.85)',
                            'rgba(99,102,241,.85)',
                            'rgba(244,63,94,.75)',
                            'rgba(148,163,184,.85)'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    ...baseOptions,
                    cutout: '70%',
                    plugins: { ...baseOptions.plugins, legend: { position: 'bottom' } }
                }
            });

            // 4) Stage Allocation
            new Chart(document.getElementById('stageAllocationChart'), {
                type: 'bar',
                data: {
                    labels: @json($stageAllocation['labels']),
                    datasets: [{
                        label: 'تعداد',
                        data: @json($stageAllocation['data']),
                        borderRadius: 10,
                        backgroundColor: 'rgba(16,185,129,.85)'
                    }]
                },
                options: {
                    ...baseOptions,
                    plugins: { ...baseOptions.plugins, legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, border: { display: false } },
                        y: { grid: { color: gridColor }, border: { color: borderColor } }
                    }
                }
            });

            // 5) KPI Trend
            new Chart(document.getElementById('kpiTrendChart'), {
                type: 'line',
                data: {
                    labels: @json($portfolioKpi['months']),
                    datasets: [
                        {
                            label: 'MRR',
                            data: @json($portfolioKpi['mrr']),
                            borderColor: 'rgba(14,165,233,1)',
                            backgroundColor: 'rgba(14,165,233,.12)',
                            fill: true,
                            tension: .35,
                            pointRadius: 2
                        },
                        {
                            label: 'Burn',
                            data: @json($portfolioKpi['burn']),
                            borderColor: 'rgba(244,63,94,1)',
                            backgroundColor: 'rgba(244,63,94,.10)',
                            fill: true,
                            tension: .35,
                            pointRadius: 2
                        },
                        {
                            label: 'Runway (ماه)',
                            data: @json($portfolioKpi['runway']),
                            borderColor: 'rgba(249,115,22,1)',
                            backgroundColor: 'rgba(249,115,22,.10)',
                            fill: true,
                            tension: .35,
                            pointRadius: 2
                        }
                    ]
                },
                options: {
                    ...baseOptions,
                    scales: {
                        x: { grid: { display: false }, border: { display: false } },
                        y: { grid: { color: gridColor }, border: { color: borderColor } }
                    }
                }
            });

            // 6) Portfolio Health
            new Chart(document.getElementById('portfolioHealthChart'), {
                type: 'polarArea',
                data: {
                    labels: @json($portfolioHealth['labels']),
                    datasets: [{
                        data: @json($portfolioHealth['data']),
                        backgroundColor: [
                            'rgba(16,185,129,.70)',
                            'rgba(14,165,233,.70)',
                            'rgba(249,115,22,.65)',
                            'rgba(244,63,94,.60)',
                            'rgba(99,102,241,.70)'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    ...baseOptions,
                    scales: { r: { grid: { color: gridColor }, ticks: { display: false } } }
                }
            });

            // 7) Exit Timeline (bar + line)
            new Chart(document.getElementById('exitTimelineChart'), {
                data: {
                    labels: @json($exitTimeline['labels']),
                    datasets: [
                        {
                            type: 'bar',
                            label: 'تعداد خروج',
                            data: @json($exitTimeline['count']),
                            backgroundColor: 'rgba(99,102,241,.80)',
                            borderRadius: 10
                        },
                        {
                            type: 'line',
                            label: 'ارزش خروج',
                            data: @json($exitTimeline['value']),
                            borderColor: 'rgba(249,115,22,1)',
                            backgroundColor: 'rgba(249,115,22,.12)',
                            fill: true,
                            tension: .35,
                            pointRadius: 2,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    ...baseOptions,
                    scales: {
                        x: { grid: { display: false }, border: { display: false } },
                        y: { grid: { color: gridColor }, border: { color: borderColor }, beginAtZero: true },
                        y1: { position: 'right', grid: { display: false }, beginAtZero: true }
                    }
                }
            });

            // 8) Company Performance (IRR + MoM)
            new Chart(document.getElementById('companyPerformanceChart'), {
                data: {
                    labels: @json($companyPerformance['labels']),
                    datasets: [
                        {
                            type: 'bar',
                            label: 'IRR (%)',
                            data: @json($companyPerformance['irr']),
                            backgroundColor: 'rgba(16,185,129,.80)',
                            borderRadius: 10
                        },
                        {
                            type: 'line',
                            label: 'رشد ماهانه (%)',
                            data: @json($companyPerformance['mom']),
                            borderColor: 'rgba(14,165,233,1)',
                            backgroundColor: 'rgba(14,165,233,.10)',
                            fill: true,
                            tension: .35,
                            pointRadius: 2,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    ...baseOptions,
                    scales: {
                        x: { grid: { display: false }, border: { display: false } },
                        y: { grid: { color: gridColor }, border: { color: borderColor }, beginAtZero: true },
                        y1: { position: 'right', grid: { display: false }, beginAtZero: true }
                    }
                }
            });

            // 9) Fund Metrics
            new Chart(document.getElementById('fundMetricsChart'), {
                type: 'line',
                data: {
                    labels: @json($fundMetrics['labels']),
                    datasets: [
                        { label: 'TVPI', data: @json($fundMetrics['tvpi']), borderColor: 'rgba(14,165,233,1)', backgroundColor:'rgba(14,165,233,.10)', fill:true, tension:.35, pointRadius:2 },
                        { label: 'DPI',  data: @json($fundMetrics['dpi']),  borderColor: 'rgba(16,185,129,1)', backgroundColor:'rgba(16,185,129,.10)', fill:true, tension:.35, pointRadius:2 },
                        { label: 'RVPI', data: @json($fundMetrics['rvpi']), borderColor: 'rgba(244,63,94,1)',  backgroundColor:'rgba(244,63,94,.10)',  fill:true, tension:.35, pointRadius:2 }
                    ]
                },
                options: {
                    ...baseOptions,
                    scales: {
                        x: { grid: { display: false }, border: { display: false } },
                        y: { grid: { color: gridColor }, border: { color: borderColor }, beginAtZero: false }
                    }
                }
            });
        });
    </script>
    <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/node-waves/node-waves.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/hammer/hammer.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/i18n/i18n.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/vendor/js/menu.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
    <script src="{{asset('assets/js/main.js')}}"></script>
    <script src="{{asset('assets/js/app-email.js')}}"></script>



@endpush
