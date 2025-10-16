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

    @foreach($projects as $project)
        <div class="modal fade" id="showModal{{$project->id}}" tabindex="-1" aria-labelledby="editModalLabel{{$project->id}}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{$project->id}}">{{$thispage['edit']}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- Profile Modal -->
    @foreach($projects as $project)
        <div class="modal fade" id="profileModal{{ $project->id }}" tabindex="-1" aria-labelledby="profileModalLabel{{$project->id}}" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <!-- Header -->
                    <div class="modal-header">
                        <h5 class="modal-title">پروفایل شرکت: {{ $project->title ?? '---' }} </h5>
                        <button  type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="companyTabs{{ $project->id }}" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="profilecompany-tab{{ $project->id }}" data-bs-toggle="tab" data-bs-target="#tab-profilecompany{{ $project->id }}"
                                        type="button" role="tab" aria-controls="tab-profilecompany{{ $project->id }}" aria-selected="true">
                                    اطلاعات شرکت
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profileproject-tab{{ $project->id }}" data-bs-toggle="tab" data-bs-target="#tab-profilepriject{{ $project->id }}"
                                        type="button" role="tab" aria-controls="tab-profileproject{{ $project->id }}" aria-selected="true">
                                    اطلاعات طرح
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="investment-tab{{ $project->id }}" data-bs-toggle="tab" data-bs-target="#tab-investment{{ $project->id }}"
                                        type="button" role="tab" aria-controls="tab-investment{{ $project->id }}" aria-selected="false">
                                    سرمایه‌گذاری
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="payments-tab{{ $project->id }}" data-bs-toggle="tab" data-bs-target="#tab-payments{{ $project->id }}"
                                        type="button" role="tab" aria-controls="tab-payments{{ $project->id }}" aria-selected="false">
                                    پرداخت‌ها
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="kpi-tab{{ $project->id }}" data-bs-toggle="tab" data-bs-target="#tab-kpi{{ $project->id }}"
                                        type="button" role="tab" aria-controls="tab-kpi{{ $project->id }}" aria-selected="false">
                                    KPI
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="commitment-tab{{ $project->id }}" data-bs-toggle="tab" data-bs-target="#tab-commitment{{ $project->id }}"
                                        type="button" role="tab" aria-controls="tab-commitment{{ $project->id }}" aria-selected="false">
                                    تعهدات
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="guaranty-tab{{ $project->id }}" data-bs-toggle="tab" data-bs-target="#tab-guaranty{{ $project->id }}"
                                        type="button" role="tab" aria-controls="tab-guaranty{{ $project->id }}" aria-selected="false">
                                    تضامین
                                </button>
                            </li>

                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content mt-3" id="companyTabsContent{{ $project->id }}">
                            <!-- Profile Tab -->
                            <div class="tab-pane fade show active" id="tab-profilecompany{{ $project->id }}" role="tabpanel" aria-labelledby="profilecompany-tab{{ $project->id }}">
                                @if($project->logo)
                                    <img src="{{ asset('storage/'.$project->logo) }}" class="lazy rounded-circle mb-3" width="80" height="80" alt="لوگو">
                                @endif
                                <p><strong>نام شرکت:</strong>    {{ $project->title }}  </p>
                                <p><strong>معرفی شرکت:</strong>    {{ $project->description }}   </p>
                                <p><strong>مدیرعامل:</strong>     {{ $project->CEO }}           </p>
                                <p><strong>شماره موبایل:</strong> {{ $project->ceo_phone }}     </p>
                                <p><strong>وضعیت پروژه:</strong>  {{ $project->activity_status }}</p>
                            </div>

                            <!-- Investment Tab -->
                            <style>
                                input[type="checkbox"].status-green:checked {
                                    accent-color: #28a745;
                                }
                                input[type="checkbox"].status-red:checked {
                                    accent-color: #dc3545;
                                }
                            </style>
                            <div class="tab-pane fade show" id="tab-profileproject{{ $project->id }}" role="tabpanel" aria-labelledby="profileproject-tab{{ $project->id }}">
                                @if($project->logo)
                                    <img src="{{ asset('storage/'.$project->logo) }}" class="lazy rounded-circle mb-3" width="80" height="80" alt="لوگو">
                                @endif
                                <p><strong>نام طرح:</strong>    {{ $project->title }}  </p>
                                <p><strong>معرفی طرح:</strong>    {{ $project->description }}   </p>
                                <p><strong>مدیرعامل:</strong>     {{ $project->CEO }}           </p>
                                <p><strong>شماره موبایل:</strong> {{ $project->ceo_phone }}     </p>
                                <p><strong>وضعیت پروژه:</strong>  {{ $project->activity_status }}</p>
                            </div>
                            <div class="tab-pane fade" id="tab-investment{{ $project->id }}" role="tabpanel" aria-labelledby="investment-tab{{ $project->id }}">
                                <div class="accordion" id="projectStepsAccordion{{ $project->id }}">
                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <div class="list-group shadow-sm rounded" style="overflow-y:auto; max-height:620px;">
                                                @foreach($investsteps as $step)
                                                    <div class="list-group-item d-flex align-items-center py-2 {{ $step->id === ($project->invest_step) ? 'active' : '' }}"
                                                         style="cursor: default; border-right: 5px solid {{ $step->id < $project->invest_step ? '#4caf50' : ($step->id === $project->invest_step ? '#7367f0' : '#ddd') }};">
                                                        <span class="me-2 d-inline-flex justify-content-center align-items-center rounded-circle"
                                                              style="width: 28px; height: 28px; background: {{ $step->id < $project->invest_step ? '#c8e6c9' : ($step->id === $project->invest_step ? '#ede7f6' : '#f1f1f1') }};
                                                                  color: {{ $step->id < $project->invest_step ? '#2e7d32' : ($step->id === $project->invest_step ? '#5e35b1' : '#aaa') }};
                                                                  font-weight: bold;">
                                                            {{ $step->id }}
                                                        </span>
                                                        <div class="flex-grow-1">
                                                            <div class="fw-bold {{ $step->id === $project->invest_step ? 'text-dark' : 'text-muted' }}">{{ $step->title }}</div>
                                                            <small class="text-muted">{{ $step->description }}</small>
                                                        </div>
                                                        @if($step->id === $project->invest_step)
                                                            <span class="badge bg-primary ms-auto">اکنون</span>
                                                        @elseif($step->id < $project->invest_step)
                                                            <i class="mdi mdi-check-circle-outline text-success ms-auto"></i>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @foreach($investsteps as $step)
                                            @if($project->invest_step === $step->id)
                                                <div class="col-md-8">
                                                    <div class="card border shadow-sm">
                                                        <div class="card-header bg-light d-flex align-items-center">
                                                            <span class="badge bg-primary me-2" style="width:26px;">{{ $project->invest_step }}</span>
                                                            <h6 class="mb-0 fw-bold">{{ $step->title }}</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <p class="text-muted">{{ $step->description }}</p>
                                                            @if($step->id == 1)
                                                                @foreach($files as $file)
                                                                    @if($file->subject_id == 4 && $file->project_id == $project->id)
                                                                        @if($file->status  == 4)
                                                                            <div class="alert alert-info record-box" id="record-{{ $file->id }}"> فایل <a href="{{asset('storage/' . $file->file_path)}}" target="_blank"> {{$file->original_name}} </a> در تاریخ {{jdate($file->created_at)->format('d-m-Y')}} بارگزاری شده -
                                                                                <span style="color: green; font-weight: bold;">✔ تایید شد</span>
                                                                            </div>
                                                                       @elseif($file->status <> 5)
                                                                            <div class="alert alert-info record-box" id="record-{{ $file->id }}"> فایل <a href="{{asset('storage/' . $file->file_path)}}" target="_blank"> {{$file->original_name}} </a> در تاریخ {{jdate($file->created_at)->format('d-m-Y')}} بارگزاری شده -
                                                                                <button class="send-btn btn btn-primary" data-id="{{ $file->id }}" data-status="4">تایید</button>
                                                                                <button class="send-btn btn btn-delete" data-id="{{ $file->id }}" data-status="5">رد</button>
                                                                            </div>
                                                                       @endif
                                                                    @endif
                                                                @endforeach
                                                                    <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                        @csrf
                                                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                        <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                        <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                        <input type="hidden" name="status" class="status-input">

                                                                        <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                        <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">تایید مرحله</button>

                                                                        <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">رد مرحله</button>

                                                                        <button type="submit" class="btn-submit d-none"></button>
                                                                    </form>

                                                            @elseif($step->id == 2)
                                                                <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                    @csrf
                                                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                    <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                    <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                    <input type="hidden" name="status" class="status-input">

                                                                    <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                    <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                        تایید مرحله
                                                                    </button>

                                                                    <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                        رد مرحله
                                                                    </button>

                                                                    <button type="submit" class="btn-submit d-none"></button>
                                                                </form>
                                                            @elseif($step->id == 3)
                                                                @foreach($files as $file)
                                                                    @if($file->subject_id == 1 && $file->project_id == $project->id)
                                                                            <div class="alert alert-info record-box" id="record-{{ $file->id }}"> فایل <a href="{{asset('storage/' . $file->file_path)}}" target="_blank"> {{$file->original_name}} </a> در تاریخ {{jdate($file->created_at)->format('d-m-Y')}} بارگزاری شده -
                                                                                <span style="color: green; font-weight: bold;">✔ تایید شد</span>
                                                                            </div>
                                                                    @endif
                                                                @endforeach
                                                                <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                    @csrf
                                                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                    <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                    <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                    <input type="hidden" name="status" class="status-input">

                                                                    <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                    <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                        تایید مرحله
                                                                    </button>

                                                                    <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                        رد مرحله
                                                                    </button>

                                                                    <button type="submit" class="btn-submit d-none"></button>
                                                                </form>
                                                            @elseif($step->id == 4)
                                                                @foreach($files as $file)
                                                                    @if($file->subject_id == 2 && $file->project_id == $project->id)
                                                                        <div class="alert alert-info record-box" id="record-{{ $file->id }}"> فایل <a href="{{asset('storage/' . $file->file_path)}}" target="_blank"> {{$file->original_name}} </a> در تاریخ {{jdate($file->created_at)->format('d-m-Y')}} بارگزاری شده -</div>
                                                                    @endif
                                                                @endforeach
                                                                    <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                        @csrf
                                                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                        <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                        <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                        <input type="hidden" name="status" class="status-input">

                                                                        <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                        <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                            تایید مرحله
                                                                        </button>

                                                                        <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                            رد مرحله
                                                                        </button>

                                                                        <button type="submit" class="btn-submit d-none"></button>
                                                                    </form>
                                                            @elseif($step->id == 5)
                                                                <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                    @csrf
                                                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                    <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                    <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                    <input type="hidden" name="status" class="status-input">

                                                                    <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                    <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                        تایید مرحله
                                                                    </button>

                                                                    <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                        رد مرحله
                                                                    </button>

                                                                    <button type="submit" class="btn-submit d-none"></button>
                                                                </form>
                                                            @elseif($step->id == 6)
                                                                @foreach($files as $file)
                                                                    @if(in_array($file->subject_id, [3,6,7,8,9,10,11,12,13,14,15,16]) && $file->project_id == $project->id)
                                                                        <div class="alert alert-info record-box" id="record-{{ $file->id }}"> فایل <a href="{{asset('storage/' . $file->file_path)}}" target="_blank"> {{$file->original_name}} </a> در تاریخ {{jdate($file->created_at)->format('d-m-Y')}} بارگزاری شده -</div>
                                                                    @endif
                                                                @endforeach
                                                                    <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                        @csrf
                                                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                        <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                        <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                        <input type="hidden" name="status" class="status-input">

                                                                        <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                        <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                            تایید مرحله
                                                                        </button>

                                                                        <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                            رد مرحله
                                                                        </button>

                                                                        <button type="submit" class="btn-submit d-none"></button>
                                                                    </form>
                                                            @elseif($step->id == 7)
                                                                <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                    @csrf
                                                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                    <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                    <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                    <input type="hidden" name="status" class="status-input">

                                                                    <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                    <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                        تایید مرحله
                                                                    </button>

                                                                    <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                        رد مرحله
                                                                    </button>

                                                                    <button type="submit" class="btn-submit d-none"></button>
                                                                </form>
                                                            @elseif($step->id == 8)
                                                                <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                    @csrf
                                                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                    <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                    <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                    <input type="hidden" name="status" class="status-input">

                                                                    <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                    <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                        تایید مرحله
                                                                    </button>

                                                                    <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                        رد مرحله
                                                                    </button>

                                                                    <button type="submit" class="btn-submit d-none"></button>
                                                                </form>
                                                            @elseif($step->id == 9)
                                                                @foreach($files as $file)
                                                                    @if($file->subject_id == 19)
                                                                    <div class="alert alert-info record-box" id="record-{{ $file->id }}"> فایل <a href="{{asset('storage/' . $file->file_path)}}" target="_blank"> {{$file->original_name}} </a> در تاریخ {{jdate($file->created_at)->format('d-m-Y')}} بارگزاری شده -</div>
                                                                    @endif
                                                                @endforeach
                                                                    <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                        @csrf
                                                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                        <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                        <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                        <input type="hidden" name="status" class="status-input">

                                                                        <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                        <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                            تایید مرحله
                                                                        </button>

                                                                        <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                            رد مرحله
                                                                        </button>

                                                                        <button type="submit" class="btn-submit d-none"></button>
                                                                    </form>
                                                            @elseif($step->id == 10)
                                                                <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                    @csrf
                                                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                    <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                    <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                    <input type="hidden" name="status" class="status-input">

                                                                    <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                    <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                        تایید مرحله
                                                                    </button>

                                                                    <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                        رد مرحله
                                                                    </button>

                                                                    <button type="submit" class="btn-submit d-none"></button>
                                                                </form>
                                                            @elseif($step->id == 11)
                                                                @foreach($files as $file)
                                                                    @if($file->subject_id == 20)
                                                                        <div class="alert alert-info record-box" id="record-{{ $file->id }}"> فایل <a href="{{asset('storage/' . $file->file_path)}}" target="_blank"> {{$file->original_name}} </a> در تاریخ {{jdate($file->created_at)->format('d-m-Y')}} بارگزاری شده -</div>
                                                                    @endif
                                                                @endforeach
                                                                    <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                        @csrf
                                                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                        <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                        <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                        <input type="hidden" name="status" class="status-input">

                                                                        <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                        <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                            تایید مرحله
                                                                        </button>

                                                                        <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                            رد مرحله
                                                                        </button>

                                                                        <button type="submit" class="btn-submit d-none"></button>
                                                                    </form>
                                                            @elseif($step->id == 12)
                                                                <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                    @csrf
                                                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                    <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                    <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                    <input type="hidden" name="status" class="status-input">

                                                                    <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                    <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                        تایید مرحله
                                                                    </button>

                                                                    <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                        رد مرحله
                                                                    </button>

                                                                    <button type="submit" class="btn-submit d-none"></button>
                                                                </form>
                                                            @elseif($step->id == 13)
                                                                <h6 class="fw-bold mb-3">قرارداد نهایی</h6>
                                                                <div class="table-responsive">
                                                                    <table class="table align-middle mb-0">
                                                                        <thead class="table-light">
                                                                        <tr>
                                                                            <th>عنوان قرارداد</th>
                                                                            <th>شماره قرارداد</th>
                                                                            <th>تاریخ عقد</th>
                                                                            <th class="text-center" style="width:90px">فایل</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <tr>
                                                                            <td>قرارداد سرمایه گذاری {{$project->company_name}} </td>
                                                                            <td>33556644</td>
                                                                            <td>1404/03/01</td>
                                                                            <td><a href="{{ asset('#') }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="mdi mdi-eye"></i></a></td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                    @csrf
                                                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                    <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                    <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                    <input type="hidden" name="status" class="status-input">

                                                                    <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                    <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                        تایید مرحله
                                                                    </button>

                                                                    <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                        رد مرحله
                                                                    </button>

                                                                    <button type="submit" class="btn-submit d-none"></button>
                                                                </form>
                                                            @elseif($step->id == 14)
                                                                @foreach($files as $file)
                                                                    @if(in_array($file->subject_id, [18,21]))
                                                                        <div class="alert alert-info record-box" id="record-{{ $file->id }}"> فایل <a href="{{asset('storage/' . $file->file_path)}}" target="_blank"> {{$file->original_name}} </a> در تاریخ {{jdate($file->created_at)->format('d-m-Y')}} بارگزاری شده -</div>
                                                                    @endif
                                                                @endforeach
                                                                    <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                        @csrf
                                                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                        <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                        <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                        <input type="hidden" name="status" class="status-input">

                                                                        <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                        <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                            تایید مرحله
                                                                        </button>

                                                                        <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                            رد مرحله
                                                                        </button>

                                                                        <button type="submit" class="btn-submit d-none"></button>
                                                                    </form>
                                                            @elseif($step->id == 15)
                                                                @foreach($files as $file)
                                                                    @if(in_array($file->subject_id, [18,22]))
                                                                        <div class="alert alert-info record-box" id="record-{{ $file->id }}"> فایل <a href="{{asset('storage/' . $file->file_path)}}" target="_blank"> {{$file->original_name}} </a> در تاریخ {{jdate($file->created_at)->format('d-m-Y')}} بارگزاری شده -</div>
                                                                    @endif
                                                                @endforeach
                                                                    <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                        @csrf
                                                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                        <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                        <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                        <input type="hidden" name="status" class="status-input">

                                                                        <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                        <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                            تایید مرحله
                                                                        </button>

                                                                        <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                            رد مرحله
                                                                        </button>

                                                                        <button type="submit" class="btn-submit d-none"></button>
                                                                    </form>
                                                            @elseif($step->id == 16)
                                                                @foreach($files as $file)
                                                                    @if(in_array($file->subject_id, [18,23]))
                                                                        <div class="alert alert-info record-box" id="record-{{ $file->id }}"> فایل <a href="{{asset('storage/' . $file->file_path)}}" target="_blank"> {{$file->original_name}} </a> در تاریخ {{jdate($file->created_at)->format('d-m-Y')}} بارگزاری شده -</div>
                                                                    @endif
                                                                @endforeach
                                                                    <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                        @csrf
                                                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                        <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                        <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                        <input type="hidden" name="status" class="status-input">

                                                                        <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                        <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                            تایید مرحله
                                                                        </button>

                                                                        <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                            رد مرحله
                                                                        </button>

                                                                        <button type="submit" class="btn-submit d-none"></button>
                                                                    </form>
                                                            @elseif($step->id == 17)
                                                                @foreach($files as $file)
                                                                    @if(in_array($file->subject_id, [18,24]))
                                                                        <div class="alert alert-info record-box" id="record-{{ $file->id }}"> فایل <a href="{{asset('storage/' . $file->file_path)}}" target="_blank"> {{$file->original_name}} </a> در تاریخ {{jdate($file->created_at)->format('d-m-Y')}} بارگزاری شده -</div>
                                                                    @endif
                                                                @endforeach
                                                                    <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                        @csrf
                                                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                        <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                        <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                        <input type="hidden" name="status" class="status-input">

                                                                        <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                        <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                            تایید مرحله
                                                                        </button>

                                                                        <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                            رد مرحله
                                                                        </button>

                                                                        <button type="submit" class="btn-submit d-none"></button>
                                                                    </form>
                                                            @elseif($step->id == 18)
                                                                <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                    @csrf
                                                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                    <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                    <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                    <input type="hidden" name="status" class="status-input">

                                                                    <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                    <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                        تایید مرحله
                                                                    </button>

                                                                    <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                        رد مرحله
                                                                    </button>

                                                                    <button type="submit" class="btn-submit d-none"></button>
                                                                </form>
                                                            @elseif($step->id == 19)
                                                                <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                    @csrf
                                                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                    <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                    <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                    <input type="hidden" name="status" class="status-input">

                                                                    <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                    <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                        تایید مرحله
                                                                    </button>

                                                                    <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                        رد مرحله
                                                                    </button>

                                                                    <button type="submit" class="btn-submit d-none"></button>
                                                                </form>
                                                            @elseif($step->id == 20)
                                                                <form action="{{ route('flow.store') }}" method="POST" class="flow-form d-inline">
                                                                    @csrf
                                                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                                    <input type="hidden" name="step_id" value="{{ $step->id }}">
                                                                    <input type="hidden" name="step_title" value="{{ $step->title }}">
                                                                    <input type="hidden" name="status" class="status-input">

                                                                    <textarea name="description" class="form-control mb-2" rows="4"></textarea>

                                                                    <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                                        تایید مرحله
                                                                    </button>

                                                                    <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                                        رد مرحله
                                                                    </button>

                                                                    <button type="submit" class="btn-submit d-none"></button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                            </div>
                            </div>
                            <!-- Payments Tab  -->
                            <div class="tab-pane fade" id="tab-payments{{ $project->id }}" role="tabpanel" aria-labelledby="payments-tab{{ $project->id }}">
                                <table class="table table-bordered mt-2">
                                    <thead>
                                    <tr>
                                        <th>مبلغ</th>
                                        <th>شماره قسط</th>
                                        <th>تاریخ پرداخت</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($finances as $payment)
                                        @if($payment->project_id == $project->id)
                                        <tr>
                                            <td>{{ number_format($payment->amount) }} تومان</td>
                                            <td>{{ $payment->serial }}</td>
                                            <td>{{ $payment->date }}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- KPI Tab -->
                            <div class="tab-pane fade" id="tab-kpi{{ $project->id }}" role="tabpanel" aria-labelledby="kpi-tab{{ $project->id }}">
                                <ul class="list-group">
                                    @foreach($finances as $payment)
                                        @if($payment->project_id == $project->id)
                                            <tr>
                                                <td>{{ number_format($payment->amount) }} تومان</td>
                                                <td>{{ $payment->serial }}</td>
                                                <td>{{ $payment->date }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="tab-commitment{{ $project->id }}" role="tabpanel" aria-labelledby="commitment-tab{{ $project->id }}">
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

                            <div class="tab-pane fade" id="tab-guaranty{{ $project->id }}" role="tabpanel" aria-labelledby="guaranty-tab{{ $project->id }}">
                                <table class="table table-bordered mt-2">
                                    <thead>
                                    <tr>
                                        <th>مبلغ</th>
                                        <th>شماره قسط</th>
                                        <th>تاریخ پرداخت</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($finances as $payment)
                                        @if($payment->project_id == $project->id)
                                            <tr>
                                                <td>{{ number_format($payment->amount) }} تومان</td>
                                                <td>{{ $payment->serial }}</td>
                                                <td>{{ $payment->date }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>


                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
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

                        <div class="dz-message text-center text-muted">
                            <div class="mb-3">
                                <i class="bi bi-cloud-arrow-up" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="fw-bold mb-2">برای آپلود فایل، کلیک کنید یا فایل را بکشید اینجا</h5>
                            <p class="small text-secondary mb-0">فرمت‌های مجاز: JPG, PNG, PDF, MP4, DOCX (حداکثر 10 مگابایت)</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- مودال پیش نمایش عمومی -->
    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
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
{{--    <script>--}}
{{--        function disableBtnWithSpinner($btn) {--}}
{{--            $btn.prop('disabled', true)--}}
{{--                .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...');--}}
{{--        }--}}
{{--    </script>--}}
{{--    <script>--}}
{{--        jQuery(function($){--}}
{{--            function showToast(message, type = 'success') {--}}
{{--                toastr.options = {--}}
{{--                    closeButton: true,--}}
{{--                    progressBar: true,--}}
{{--                    positionClass: "toast-top-right",--}}
{{--                    timeOut: 3000,--}}
{{--                    rtl: true--}}
{{--                };--}}

{{--                if (toastr[type]) {--}}
{{--                    toastr[type](message);--}}
{{--                } else {--}}
{{--                    toastr.success(message);--}}
{{--                }--}}
{{--            }--}}
{{--            $('#submit').on('click', function(e){--}}
{{--                e.preventDefault();--}}
{{--                var $btn  = $(this);--}}
{{--                var $form = $('#addform');--}}
{{--                var originalHtml = $btn.html();--}}

{{--                $btn.prop('disabled', true)--}}
{{--                    .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...');--}}

{{--                $.ajaxSetup({--}}
{{--                    headers: {--}}
{{--                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')--}}
{{--                    }--}}
{{--                });--}}

{{--                $.ajax({--}}
{{--                    url: "{{ route('project.store') }}",--}}
{{--                    method: 'POST',--}}
{{--                    data: $form.serialize(),--}}
{{--                    success: function (data) {--}}
{{--                        if (data.success) {--}}
{{--                            const modalEl = document.getElementById('addModal');--}}
{{--                            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);--}}

{{--                            modalEl.addEventListener('hidden.bs.modal', function handler(){--}}
{{--                                modalEl.removeEventListener('hidden.bs.modal', handler);--}}
{{--                                $('.yajra-datatable').DataTable().ajax.reload(null, false);--}}
{{--                            }, { once: true });--}}

{{--                            modal.hide();--}}
{{--                            $('.modal-backdrop').remove();--}}
{{--                            $('body').removeClass('modal-open');--}}
{{--                            $('body').css('padding-right', '');--}}
{{--                            showToast('آیتم با موفقیت افزوده شد!', 'success');--}}
{{--                        } else {--}}
{{--                            swal(data.subject, data.message, data.flag);--}}
{{--                        }--}}
{{--                    },--}}
{{--                    error: function () {--}}
{{--                        swal('خطا', 'مشکلی پیش آمد. لطفاً دوباره تلاش کنید.', 'error');--}}
{{--                    },--}}
{{--                    complete: function () {--}}
{{--                        $btn.prop('disabled', false).html(originalHtml);--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--    <script>--}}
{{--        jQuery(function($){--}}
{{--            function showToast(message, type = 'success') {--}}
{{--                toastr.options = {--}}
{{--                    closeButton: true,--}}
{{--                    progressBar: true,--}}
{{--                    positionClass: "toast-top-right",--}}
{{--                    timeOut: 3000,--}}
{{--                    rtl: true--}}
{{--                };--}}
{{--                toastr[type] ? toastr[type](message) : toastr.success(message);--}}
{{--            }--}}

{{--            $(document).on('click', '.approve-btn', function(){--}}
{{--                const $form = $(this).closest('form');--}}
{{--                $form.find('.status-input').val('approved');--}}
{{--                $form.find('.btn-submit').trigger('click');--}}
{{--            });--}}

{{--            $(document).on('click', '.reject-btn', function(){--}}
{{--                const $form = $(this).closest('form');--}}
{{--                $form.find('.status-input').val('rejected');--}}
{{--                $form.find('.btn-submit').trigger('click');--}}
{{--            });--}}

{{--            // هندل ارسال فرم--}}
{{--            $(document).on('submit', '.flow-form', function(e){--}}
{{--                e.preventDefault();--}}
{{--                const $form = $(this);--}}
{{--                const url   = $form.attr('action');--}}
{{--                const $btn  = $form.find('.btn-submit');--}}
{{--                const originalHtml = $btn.html();--}}

{{--                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> در حال ارسال...');--}}

{{--                $.ajaxSetup({--}}
{{--                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }--}}
{{--                });--}}

{{--                $.post(url, $form.serialize())--}}
{{--                    .done(function(data){--}}
{{--                        if (data.success) {--}}
{{--                            const modalEl = document.getElementById('addModal');--}}
{{--                            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);--}}

{{--                            modalEl.addEventListener('hidden.bs.modal', function handler(){--}}
{{--                                modalEl.removeEventListener('hidden.bs.modal', handler);--}}
{{--                                $('.yajra-datatable').DataTable().ajax.reload(null, false);--}}
{{--                            }, { once: true });--}}

{{--                            modal.hide();--}}
{{--                            $('.modal-backdrop').remove();--}}
{{--                            $('body').removeClass('modal-open').css('padding-right', '');--}}
{{--                            showToast('آیتم با موفقیت افزوده شد!', 'success');--}}
{{--                        } else {--}}
{{--                            swal(data.subject, data.message, data.flag);--}}
{{--                        }--}}
{{--                    })--}}
{{--                    .fail(function(){--}}
{{--                        swal('خطا', 'مشکلی پیش آمد. لطفاً دوباره تلاش کنید.', 'error');--}}
{{--                    })--}}
{{--                    .always(function(){--}}
{{--                        $btn.prop('disabled', false).html(originalHtml);--}}
{{--                    });--}}
{{--            });--}}
{{--        });--}}

{{--    </script>--}}

{{--    <script>--}}
{{--        jQuery(function($){--}}
{{--            function showToast(message, type = 'success') {--}}
{{--                toastr.options = {--}}
{{--                    closeButton: true,--}}
{{--                    progressBar: true,--}}
{{--                    positionClass: "toast-top-center",--}}
{{--                    timeOut: 3000,--}}
{{--                    rtl: true--}}
{{--                };--}}

{{--                if (toastr[type]) {--}}
{{--                    toastr[type](message);--}}
{{--                } else {--}}
{{--                    toastr.success(message);--}}
{{--                }--}}
{{--            }--}}

{{--            $(document).on('click', '[id^=editsubmit_]', function(e){--}}
{{--                e.preventDefault();--}}
{{--                const $btn = $(this);--}}
{{--                const id = this.id.split('_')[1];--}}
{{--                const $form = $('#editform_' + id);--}}

{{--                if (!$form.length) {--}}
{{--                    console.error('فرم editform_' + id + ' پیدا نشد!');--}}
{{--                    return;--}}
{{--                }--}}

{{--                const url = $form.attr('action');--}}
{{--                const originalHtml = $btn.html();--}}
{{--                disableBtnWithSpinner($btn);--}}

{{--                $.ajax({--}}
{{--                    url: url,--}}
{{--                    method: 'PATCH',--}}
{{--                    data: $form.serialize(),--}}
{{--                    beforeSend: function () {--}}
{{--                        // دکمه رو غیر فعال و اسپینر رو فعال می‌کنیم--}}
{{--                        disableBtnWithSpinner($btn);--}}
{{--                    },--}}
{{--                    success: function (data) {--}}
{{--                        if (data.success) {--}}
{{--                            const modalEl = document.getElementById('editModal' + id);--}}
{{--                            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);--}}

{{--                            modalEl.addEventListener('hidden.bs.modal', function handler(){--}}
{{--                                modalEl.removeEventListener('hidden.bs.modal', handler);--}}
{{--                                $('.yajra-datatable').DataTable().ajax.reload(null, false);--}}
{{--                                showToast('آیتم با موفقیت ویرایش شد!', 'success');--}}
{{--                            }, { once: true });--}}

{{--                            modal.hide();--}}
{{--                            $('.modal-backdrop').remove();--}}
{{--                            $('body').removeClass('modal-open').css('padding-right', '');--}}
{{--                        } else {--}}
{{--                            swal(data.subject || 'خطا', data.message || 'در ویرایش مشکلی پیش آمد', data.flag || 'error');--}}
{{--                        }--}}
{{--                    },--}}
{{--                    error: function (xhr, status, error) {--}}
{{--                        let message = 'مشکلی پیش آمد. لطفاً دوباره تلاش کنید.';--}}
{{--                        if (xhr.responseJSON && xhr.responseJSON.message) {--}}
{{--                            message = xhr.responseJSON.message;--}}
{{--                        } else if (error) {--}}
{{--                            message = error;--}}
{{--                        }--}}
{{--                        swal('خطا', message, 'error');--}}
{{--                        console.error('AJAX Error:', xhr);--}}
{{--                    },--}}
{{--                    complete: function () {--}}
{{--                        restoreBtn($btn, originalHtml);--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}

{{--            function disableBtnWithSpinner($btn){--}}
{{--                $btn.prop('disabled', true).html(--}}
{{--                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...'--}}
{{--                );--}}
{{--            }--}}

{{--            function restoreBtn($btn, html){--}}
{{--                $btn.prop('disabled', false).html(html);--}}
{{--            }--}}
{{--        });--}}
{{--    </script>--}}

{{--    <script>--}}
{{--        jQuery(function ($) {--}}
{{--            let deleteId = null;--}}

{{--            function showToast(message, type = 'success') {--}}
{{--                toastr.options = {--}}
{{--                    closeButton: true,--}}
{{--                    progressBar: true,--}}
{{--                    positionClass: "toast-top-right",--}}
{{--                    timeOut: 3000,--}}
{{--                    rtl: true--}}
{{--                };--}}
{{--                toastr[type] ? toastr[type](message) : toastr.success(message);--}}
{{--            }--}}

{{--            // وقتی روی دکمه حذف کلیک شد--}}
{{--            $(document).on('click', '.delete-btn', function () {--}}
{{--                deleteId = $(this).data('id');--}}
{{--                $('#deleteModal').modal('show');--}}
{{--            });--}}

{{--            // وقتی تایید حذف کلیک شد--}}
{{--            $('#confirmDelete').on('click', function (e) {--}}
{{--                const $btn = $(this);--}}
{{--                const originalHtml = $btn.html();--}}

{{--                $btn.prop('disabled', true).html(--}}
{{--                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال حذف...'--}}
{{--                );--}}

{{--                $.ajax({--}}
{{--                    url: "{{ route(request()->segment(2).'.destroy', 0) }}",--}}
{{--                    method: 'DELETE',--}}
{{--                    data: { "_token": "{{ csrf_token() }}", id: deleteId },--}}
{{--                    success: function () {--}}
{{--                        $('#deleteModal').modal('hide');--}}
{{--                        $('.yajra-datatable').DataTable().ajax.reload(null, false);--}}
{{--                        showToast('آیتم با موفقیت حذف شد!', 'success');--}}
{{--                    },--}}
{{--                    error: function () {--}}
{{--                        showToast('مشکلی پیش آمد. لطفاً دوباره تلاش کنید.', 'error');--}}
{{--                    },--}}
{{--                    complete: function () {--}}
{{--                        $btn.prop('disabled', false).html(originalHtml);--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}

{{--    </script>--}}

    <script>
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
        function previewFile(fileUrl, ext) {
            const url = `/${fileUrl}`;
            const recordId = $(this).data('id');
            console.log(recordId);
            const map = {
                img: ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'],
                vid: ['mp4', 'webm', 'ogg'],
                pdf: ['pdf'],
                office: ['doc','docx','ppt','pptx','xls','xlsx']
            };

            let html =
                map.img.includes(ext) ? `<img src="${url}" class="img-fluid">` :
                    map.vid.includes(ext) ? `<video controls style="width:100%;max-height:500px"><source src="${url}" type="video/${ext}"></video>` :
                        map.pdf.includes(ext) ? `<iframe src="${url}" style="width:100%;height:500px;border:none;"></iframe>` :
                            map.office.includes(ext) ? `<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=${location.origin}/${fileUrl}" style="width:100%;height:500px;border:none;"></iframe>` :
                                `<p class="text-center">پیش‌نمایش برای این نوع فایل در دسترس نیست.</p>`;

            document.getElementById('previewContent').innerHTML = html;
            new bootstrap.Modal('#previewModal').show();
        }

        document.addEventListener("DOMContentLoaded", () => {
            if (Dropzone.instances.length) return;

            const dz = new Dropzone("#fileUploadZone", {
                url: "{{ route('storemedia') }}",
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                maxFilesize: 20,
                parallelUploads: 5,
                acceptedFiles: 'image/*,video/*,application/pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx',
                dictDefaultMessage: "فایل را اینجا رها کنید یا کلیک کنید برای انتخاب",
                init: function () {
                    let uploaded = 0, total = 0;
                    this.on("addedfile", () => total++);
                    this.on("sending", (file, xhr, formData) => {
                        formData.append('recordId', document.getElementById('recordIdInput').value);

                    });
                    this.on("uploadprogress", (f, p) => {
                        const bar = f.previewElement.querySelector("[data-dz-uploadprogress]");
                        const badge = f.previewElement.querySelector(".upload-percent");
                        if (bar && badge) {
                            bar.style.width = p + "%";
                            badge.textContent = `${Math.round(p)}%`;
                        }
                    });
                    this.on("success", (f, r) => {
                        uploaded++;
                        previewFile(r.file_path.replace(/^\/+/, ''), f.name.split('.').pop().toLowerCase());
                        if (uploaded === total) {
                            setTimeout(() => {
                                bootstrap.Modal.getInstance('#uploadModal').hide();
                                window.sample1Table ? window.sample1Table.ajax.reload(null, false) : location.reload();
                                showToast('✅ فایل‌ها با موفقیت آپلود شدند.');
                            }, 800);
                        }
                    });
                    this.on("error", () => showToast("❌ خطا در آپلود فایل", "danger"));
                },
                previewTemplate: `
<div class="dz-preview dz-file-preview text-center me-2 mb-3" style="width:160px">
  <div class="card border rounded shadow-sm p-2 position-relative">
    <div class="dz-image mb-2 rounded overflow-hidden" style="height:100px;display:flex;align-items:center;justify-content:center;background:#f8f9fa;">
      <img data-dz-thumbnail class="img-fluid" style="max-height:100%;object-fit:cover;">
    </div>
    <div data-dz-name class="text-truncate small fw-bold"></div>
    <div data-dz-size class="small text-muted mb-1"></div>
    <div class="progress mb-1" style="height:4px;"><div class="progress-bar bg-primary" data-dz-uploadprogress></div></div>
    <span class="upload-percent badge bg-primary position-absolute top-0 end-0 m-1" style="font-size:.7rem;">0%</span>
  </div>
</div>`
            });

            window.showToast = (msg, type="success") => {
                const el = document.createElement("div");
                el.className = `toast text-bg-${type} border-0 show position-fixed bottom-0 end-0 m-4`;
                el.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${msg}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>`;
                document.body.appendChild(el);
                setTimeout(() => el.remove(), 4000);
            };
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let activeInputId = null;
            document.querySelectorAll('.file-selector').forEach(input => {
                input.addEventListener('click', function () {
                    const recordId = this.dataset.recordId;
                    activeInputId = this.dataset.inputId;

                    window.open(`{{ route('selectfile') }}?record_id=${recordId}`, 'FileManager', 'width=800,height=600');
                });
            });
            window.setFileUrl = function (url) {
                if (activeInputId) {
                    document.getElementById(activeInputId).value = url;
                }
            };
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
