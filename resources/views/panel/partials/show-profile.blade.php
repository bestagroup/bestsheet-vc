<!-- Nav tabs -->
<ul class="nav nav-tabs" id="companyTabs{{ $project->id }}" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="profilecompany-tab{{ $project->id }}" data-bs-toggle="tab" data-bs-target="#tab-profilecompany{{ $project->id }}"
                type="button" role="tab" aria-controls="tab-profilecompany{{ $project->id }}" aria-selected="true">
            اطلاعات شرکت
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="profileproject-tab{{ $project->id }}" data-bs-toggle="tab" data-bs-target="#tab-profileproject{{ $project->id }}"
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
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="workflow-tab{{ $project->id }}" data-bs-toggle="tab" data-bs-target="#tab-workflow{{ $project->id }}"
                type="button" role="tab" aria-controls="tab-workflow{{ $project->id }}" aria-selected="false">
            گردش کار
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="message-tab{{ $project->id }}" data-bs-toggle="tab" data-bs-target="#tab-message{{ $project->id }}"
                type="button" role="tab" aria-controls="tab-message{{ $project->id }}" aria-selected="false">
            پیام ها
        </button>
    </li>
</ul>
<!-- Tab Content -->
<div class="tab-content mt-3" id="companyTabsContent{{ $project->id }}">
    <!-- Profile Tab -->
    <div class="tab-pane fade show active" id="tab-profilecompany{{ $project->id }}" role="tabpanel" aria-labelledby="profilecompany-tab{{ $project->id }}">

        @if($project->logo)
            <div class="text-center mb-3">
                <img src="{{ asset('storage/'.$project->logo) }}"
                     class="lazy rounded-circle" width="80" height="80" alt="لوگو">
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table class="table table-bordered table-striped"
                   style="table-layout: fixed; width: 100%; word-wrap: break-word; white-space: normal;">
                <tbody>
                <tr>
                    <th style="width: 30%;">نام شرکت</th>
                    <td>{{ $project->company_name }}</td>
                </tr>
                <tr>
                    <th>معرفی شرکت</th>
                    <td>{{ $project->description }}</td>
                </tr>
                <tr>
                    <th>مدیرعامل</th>
                    <td>{{ $project->CEO }}</td>
                </tr>
                <tr>
                    <th>شماره موبایل</th>
                    <td>{{ $project->ceo_phone }}</td>
                </tr>
                <tr>
                    <th>وضعیت پروژه</th>
                    <td>{{ $project->activity_status }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Profile Tab -->
    <div class="tab-pane fade" id="tab-profileproject{{ $project->id }}" role="tabpanel" aria-labelledby="profileproject-tab{{ $project->id }}">
        @if($project->logo)
            <div class="text-center mb-3">
                <img src="{{ asset('storage/'.$project->logo) }}"
                     class="lazy rounded-circle" width="80" height="80" alt="لوگو">
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table class="table table-bordered table-striped"
                   style="table-layout: fixed; width: 100%; word-wrap: break-word; white-space: normal;">
                <tbody>
                <tr>
                    <th style="width: 30%;">نام شرکت</th>
                    <td>{{ $project->title }}</td>
                </tr>
                <tr>
                    <th>معرفی شرکت</th>
                    <td>{{ $project->description }}</td>
                </tr>
                <tr>
                    <th>مدیرعامل</th>
                    <td>{{ $project->CEO }}</td>
                </tr>
                <tr>
                    <th>شماره موبایل</th>
                    <td>{{ $project->ceo_phone }}</td>
                </tr>
                <tr>
                    <th>وضعیت پروژه</th>
                    <td>{{ $project->activity_status }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- investment Tab -->
    <div class="tab-pane fade" id="tab-investment{{ $project->id }}" role="tabpanel" aria-labelledby="investment-tab{{ $project->id }}">
        <div class="accordion" id="projectStepsAccordion{{ $project->id }}">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="list-group shadow-sm rounded" style="overflow-y:auto; max-height:620px;">
                        @foreach($investsteps as $step)
                            <div class="list-group-item d-flex align-items-center py-2 {{ $step->id === ($project->invest_step) ? 'active' : '' }}"
                                 style="cursor: default; border-right: 5px solid {{ $step->id < $project->invest_step ? '#4caf50' : ($step->id === $project->invest_step ? '#7367f0' : '#ddd') }};">
                                <span class="me-2 d-inline-flex justify-content-center align-items-center rounded-circle" style="width: 28px; height: 28px; background: {{ $step->id < $project->invest_step ? '#c8e6c9' : ($step->id === $project->invest_step ? '#ede7f6' : '#f1f1f1') }}; color: {{ $step->id < $project->invest_step ? '#2e7d32' : ($step->id === $project->invest_step ? '#5e35b1' : '#aaa') }}; font-weight: bold;">
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
                                            @if(in_array($file->subject_id, [4]) && $file->project_id == $project->id)
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

                                                <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                    تایید مرحله
                                                </button>

                                                <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                    رد مرحله
                                                </button>

                                                <button type="submit" class="d-none real-submit"></button>
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

                                            <button type="submit" class="d-none real-submit"></button>
                                        </form>
                                    @elseif($step->id == 3)
                                    @foreach($files as $file)
                                            @if(in_array($file->subject_id, [1]) && $file->project_id == $project->id)
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

                                                <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                    تایید مرحله
                                                </button>

                                                <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                    رد مرحله
                                                </button>

                                                <button type="submit" class="d-none real-submit"></button>
                                            </form>
                                    @elseif($step->id == 4)
                                        @foreach($files as $file)
                                            @if(in_array($file->subject_id, [2]) && $file->project_id == $project->id)
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

                                                <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                    تایید مرحله
                                                </button>

                                                <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                    رد مرحله
                                                </button>

                                                <button type="submit" class="d-none real-submit"></button>
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

                                            <button type="submit" class="d-none real-submit"></button>
                                        </form>
                                    @elseif($step->id == 6)
                                        <div class="alert alert-warning"> <a href="{{asset('storage/uploads/sinavc/Screening.docx')}}" target="_blank"}}"> قالب فایل طرح کسب و کار جهت بارگزاری </a> </div>
                                    @foreach($files as $file)
                                            @if(in_array($file->subject_id, [3,6,7,8,9,10,11,12,13,14,15,16]) && $file->project_id == $project->id)
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

                                                <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                    تایید مرحله
                                                </button>

                                                <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                    رد مرحله
                                                </button>

                                                <button type="submit" class="d-none real-submit"></button>
                                            </form>
                                    @elseif($step->id == 7)
                                        @foreach($files as $file)
                                            @if(in_array($file->subject_id, [26]) && $file->project_id == $project->id)
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
                                        <button class="btn btn-md btn-image mx-1 upload-btn" style="min-width: 170px;margin: 30px auto;" data-id="{{$project->id}}" data-subject="26" data-title="فایل صورتجلسه هیئت مدیره"><i class="mdi mdi-file-document-multiple-outline"></i>فایل صورتجلسه هیئت مدیره</button>

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

                                                <button type="submit" class="d-none real-submit"></button>
                                            </form>
                                    @elseif($step->id == 8)
                                        @foreach($files as $file)
                                            @if(in_array($file->subject_id, [25]) && $file->project_id == $project->id)
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
                                        <button class="btn btn-md btn-image mx-1 upload-btn" style="min-width: 170px;margin: 30px auto;" data-id="{{$project->id}}" data-subject="25" data-title="کاربرگ تایید سرمایه پذیر"><i class="mdi mdi-file-document-multiple-outline"></i>کاربرگ تایید سرمایه پذیر</button>
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

                                            <button type="submit" class="d-none real-submit"></button>
                                        </form>
                                    @elseif($step->id == 9)
                                        @foreach($files as $file)
                                            @if(in_array($file->subject_id, [19]) && $file->project_id == $project->id)
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

                                                <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                    تایید مرحله
                                                </button>

                                                <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                    رد مرحله
                                                </button>

                                                <button type="submit" class="d-none real-submit"></button>
                                            </form>
                                    @elseif($step->id == 10)
                                        @foreach($files as $file)
                                            @if(in_array($file->subject_id, [27]) && $file->project_id == $project->id)
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

                                            <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                تایید مرحله
                                            </button>

                                            <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                رد مرحله
                                            </button>

                                            <button type="submit" class="d-none real-submit"></button>
                                        </form>
                                    @elseif($step->id == 11)
                                        @foreach($files as $file)
                                            @if(in_array($file->subject_id, [20]) && $file->project_id == $project->id)
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

                                                <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                    تایید مرحله
                                                </button>

                                                <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                    رد مرحله
                                                </button>

                                                <button type="submit" class="d-none real-submit"></button>
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

                                            <button type="submit" class="d-none real-submit"></button>
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

                                            <button type="submit" class="d-none real-submit"></button>
                                        </form>
                                    @elseif($step->id == 14)
                                        @foreach($files as $file)
                                            @if(in_array($file->subject_id, [18,21]) && $file->project_id == $project->id)
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

                                                <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                    تایید مرحله
                                                </button>

                                                <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                    رد مرحله
                                                </button>

                                                <button type="submit" class="d-none real-submit"></button>
                                            </form>
                                    @elseif($step->id == 15)
                                        @foreach($files as $file)
                                            @if(in_array($file->subject_id, [18,22]) && $file->project_id == $project->id)
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

                                                <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                    تایید مرحله
                                                </button>

                                                <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                    رد مرحله
                                                </button>

                                                <button type="submit" class="d-none real-submit"></button>
                                            </form>
                                    @elseif($step->id == 16)
                                        @foreach($files as $file)
                                            @if(in_array($file->subject_id, [18,23]) && $file->project_id == $project->id)
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

                                                <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                    تایید مرحله
                                                </button>

                                                <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                    رد مرحله
                                                </button>

                                                <button type="submit" class="d-none real-submit"></button>
                                            </form>
                                    @elseif($step->id == 17)
                                        @foreach($files as $file)
                                            @if(in_array($file->subject_id, [18,24]) && $file->project_id == $project->id)
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

                                                <button type="button" class="btn btn-success approve-btn" style="min-width:150px; margin:5px auto;">
                                                    تایید مرحله
                                                </button>

                                                <button type="button" class="btn btn-danger reject-btn" style="min-width:150px; margin:5px auto;">
                                                    رد مرحله
                                                </button>

                                                <button type="submit" class="d-none real-submit"></button>
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

                                            <button type="submit" class="d-none real-submit"></button>
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

                                            <button type="submit" class="d-none real-submit"></button>
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

                                            <button type="submit" class="d-none real-submit"></button>
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

        </ul>
    </div>
    <!-- Commitment Tab -->
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
    <!-- Guaranty Tab -->
    <div class="tab-pane fade" id="tab-guaranty{{ $project->id }}" role="tabpanel" aria-labelledby="guaranty-tab{{ $project->id }}">
        <table class="table table-bordered mt-2">

        </table>
    </div>
    <!-- Workflow Tab -->
    <div class="tab-pane fade" id="tab-workflow{{ $project->id }}" role="tabpanel" aria-labelledby="workflow-tab{{ $project->id }}">
        <div class="container">
            <div class="row g-3">
                @foreach ($project_steps as $step)
                    @php
                        $bg = $step->status === 'approved' ? '#e8f5e9' : ($step->status === 'rejected' ? '#ffebee' : '#f8f9fa');
                        $border = $step->status === 'approved' ? '#4caf50' : ($step->status === 'rejected' ? '#f44336' : '#9e9e9e');
                        $statusLabel = $step->status === 'approved' ? 'تایید شده' : ($step->status === 'rejected' ? 'رد شده' : 'در انتظار');
                        $statusBadgeClass = $step->status === 'approved' ? 'bg-success' : ($step->status === 'rejected' ? 'bg-danger' : 'bg-secondary');
                        $time = isset($step->created_at) ? (function($d){ try { return jdate($d)->format('Y/m/d H:i'); } catch(\Throwable $e) { return $d->format('Y/m/d H:i'); } })($step->created_at) : '—';
                    @endphp

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm" role="article" aria-label="مرحله {{ $step->step_number }}"
                             style="background-color: {{ $bg }}; border-left: 6px solid {{ $border }};">
                            <div class="card-header d-flex align-items-center justify-content-between py-2">
                                <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill text-dark" style="background: rgba(0,0,0,0.05); min-width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center; font-weight:600;">
                                {{ $step->step_number }}
                            </span>
                                    <h6 class="mb-0 text-truncate" style="max-width: 160px;">{{ $step->title }}</h6>
                                </div>
                            </div>
                            <small class="text-nowrap">
                                <span class="badge {{ $statusBadgeClass }} text-white">{{ $statusLabel }}</span>
                            </small>
                            <div class="card-body d-flex flex-column">
                                @if(!empty($step->description))
                                    <p class="card-text mb-2 text-muted small" style="flex:0 0 auto; max-height:72px; overflow:hidden;">
                                        {{ Str::limit($step->description, 180) }}
                                    </p>
                                @else
                                    <p class="card-text mb-2 text-muted small" style="flex:0 0 auto;">— توضیحی ثبت نشده —</p>
                                @endif

                                <div class="mt-auto">
                                    <ul class="list-unstyled mb-0 small text-secondary">
                                        <li class="d-flex align-items-center gap-2">
                                            <i class="bi bi-person-fill" aria-hidden="true"></i>
                                            <span class="text-truncate" style="max-width: 130px;">{{ $step->username ?? ($step->user->name ?? 'کارشناس') }}</span>
                                        </li>

                                        <li class="d-flex align-items-center gap-2 mt-1">
                                            <i class="bi bi-clock-fill" aria-hidden="true"></i>
                                            <span>{{ $time }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- اختیاری: فعال‌سازی tooltip های بوت‌استرپ اگر از آن استفاده می‌کنید -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.forEach(function (el) {
                    new bootstrap.Tooltip(el);
                });
            });
        </script>

        <style>
            /* ظاهرسازی کمکی برای کارت‌ها */
            .card .text-truncate { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            @media (max-width: 575.98px) {
                .card-header h6 { max-width: 110px; }
            }
        </style>


    </div>
    <!-- Message Tab -->
    <div class="tab-pane fade" id="tab-message{{ $project->id }}" role="tabpanel" aria-labelledby="message-tab{{ $project->id }}">
        <table class="table table-bordered mt-2">

        </table>
    </div>
</div>
