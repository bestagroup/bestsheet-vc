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
