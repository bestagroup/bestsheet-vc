<div class="tab-pane fade justify-content-center" id="navs-invest-card" role="tabpanel">
    <div class="mb-4">
        <label class="form-label fw-bold">درصد پیشرفت فرآیند:</label>
        <div class="progress" style="height: 20px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ round(($projects->invest_step) / count($investsteps) * 100) }}%;" aria-valuenow="{{ count($investsteps) - 1 }}" aria-valuemin="0" aria-valuemax="{{ count($investsteps) }}">
                {{ round(($projects->invest_step - 1 ) / count($investsteps) * 100) }}%
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="list-group shadow-sm rounded" style="overflow-y:auto; max-height:620px;">
                @foreach($investsteps as $step)
                    <div class="list-group-item d-flex align-items-center py-2 {{ $step->id === ($projects->invest_step) ? 'active' : '' }}"
                         style="cursor: default; border-right: 5px solid {{ $step->id < $projects->invest_step ? '#4caf50' : ($step->id === $projects->invest_step ? '#7367f0' : '#ddd') }};">
                        <span class="me-2 d-inline-flex justify-content-center align-items-center rounded-circle"
                              style="width: 28px; height: 28px; background: {{ $step->id < $projects->invest_step ? '#c8e6c9' : ($step->id === $projects->invest_step ? '#ede7f6' : '#f1f1f1') }};
                                     color: {{ $step->id < $projects->invest_step ? '#2e7d32' : ($step->id === $projects->invest_step ? '#5e35b1' : '#aaa') }};
                                     font-weight: bold;">
                            {{ $step->id }}
                        </span>
                        <div class="flex-grow-1">
                            <div class="fw-bold {{ $step->id === $projects->invest_step ? 'text-dark' : 'text-muted' }}">{{ $step->title }}</div>
                            <small class="text-muted">{{ $step->description }}</small>
                        </div>
                        @if($step->id === $projects->invest_step)
                            <span class="badge bg-primary ms-auto">اکنون</span>
                        @elseif($step->id < $projects->invest_step)
                            <i class="mdi mdi-check-circle-outline text-success ms-auto"></i>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @foreach($investsteps as $step)
            @if($projects->invest_step === $step->id)
                <div class="col-md-8">
                    <div class="card border shadow-sm">
                        <div class="card-header bg-light d-flex align-items-center">
                            <span class="badge bg-primary me-2" style="width:26px;">{{ $projects->invest_step }}</span>
                            <h6 class="mb-0 fw-bold">{{ $step->title }}</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">{{ $step->description }}</p>
                            @if($step->id == 1)
                                <button class="btn btn-sm btn-icon btn-image mx-1 upload-btn" data-id="{{Auth::user()->company->id}}"><i class="mdi mdi-file-document-multiple-outline"></i></button>
                                <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"> بارگزاری فایل </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('storemedia') }}" enctype="multipart/form-data" class="dropzone" id="fileUploadZone" style="min-height: 200px; border-style: dashed; border: 2px dashed #ccc; padding: 20px; margin-bottom: 30px;">

                                                    <input type="hidden" name="record_id" id="recordIdInput">
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
                            @elseif($step->id == 2)
                                <div class="alert alert-info">در حال بررسی ، لطفا منتظر بمانید...</div>
                            @elseif($step->id == 3)
                                <div class="alert alert-info">در حال بررسی ، لطفا منتظر بمانید...</div>
                            @elseif($step->id == 4)
                                <button class="btn btn-sm btn-icon btn-image mx-1 upload-btn" data-id="{{Auth::user()->company->id}}"><i class="mdi mdi-file-document-multiple-outline"></i></button>
                                <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"> بارگزاری فایل </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('storemedia') }}" enctype="multipart/form-data" class="dropzone" id="fileUploadZone" style="min-height: 200px; border-style: dashed; border: 2px dashed #ccc; padding: 20px; margin-bottom: 30px;">

                                                    <input type="hidden" name="record_id" id="recordIdInput">
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
                            @elseif($step->id == 5)
                                <div class="alert alert-info">در حال بررسی، لطفا منتظر بمانید...</div>
                            @elseif($step->id == 6)
                                <div class="alert alert-info">1- مجوز ها 2- لیست بیمه تمامی اعضای شرکت 3- مدارک ثبتی 4- مستندات فروش 5- رزومه اعضاء 6- قرارداد فروش 7- قراداد کارکنان 8- نتایج رتبه بندی اعتباری سهامداران 9- اظهارنامه مالیاتی 10- صورت مالی حسابرسی شده شرکت</div>
                                <button class="btn btn-sm btn-icon btn-image mx-1 upload-btn" data-id="{{Auth::user()->company->id}}"><i class="mdi mdi-file-document-multiple-outline"></i></button>
                                <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"> بارگزاری فایل </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('storemedia') }}" enctype="multipart/form-data" class="dropzone" id="fileUploadZone" style="min-height: 200px; border-style: dashed; border: 2px dashed #ccc; padding: 20px; margin-bottom: 30px;">

                                                    <input type="hidden" name="record_id" id="recordIdInput">
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
                            @elseif($step->id == 7)
                                <div class="alert alert-info">در حال بررسی، لطفا منتظر بمانید...</div>
                            @elseif($step->id == 8)
                                <div class="alert alert-info">در حال بررسی، لطفا منتظر بمانید...</div>
                            @elseif($step->id == 9)
                                <button class="btn btn-sm btn-icon btn-image mx-1 upload-btn" data-id="{{Auth::user()->company->id}}"><i class="mdi mdi-file-document-multiple-outline"></i></button>
                                <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"> بارگزاری فایل </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('storemedia') }}" enctype="multipart/form-data" class="dropzone" id="fileUploadZone" style="min-height: 200px; border-style: dashed; border: 2px dashed #ccc; padding: 20px; margin-bottom: 30px;">

                                                    <input type="hidden" name="record_id" id="recordIdInput">
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
                            @elseif($step->id == 10)
                                <div class="alert alert-info">در حال بررسی، لطفا منتظر بمانید...</div>
                            @elseif($step->id == 11)
                                <button class="btn btn-sm btn-icon btn-image mx-1 upload-btn" data-id="{{Auth::user()->company->id}}"><i class="mdi mdi-file-document-multiple-outline"></i></button>
                                <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"> بارگزاری فایل </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('storemedia') }}" enctype="multipart/form-data" class="dropzone" id="fileUploadZone" style="min-height: 200px; border-style: dashed; border: 2px dashed #ccc; padding: 20px; margin-bottom: 30px;">

                                                    <input type="hidden" name="record_id" id="recordIdInput">
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
                            @elseif($step->id == 12)
                                <div class="alert alert-info">در حال بررسی، لطفا منتظر بمانید...</div>
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
                                                <td>قرارداد سرمایه گذاری {{$company->company_name}} </td>
                                                <td>33556644</td>
                                                <td>1404/03/01</td>
                                                <td><a href="{{ asset('#') }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="mdi mdi-eye"></i></a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @elseif($step->id == 14)
                                <button class="btn btn-sm btn-icon btn-image mx-1 upload-btn" data-id="{{Auth::user()->company->id}}"><i class="mdi mdi-file-document-multiple-outline"></i></button>
                                <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"> بارگزاری فایل </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('storemedia') }}" enctype="multipart/form-data" class="dropzone" id="fileUploadZone" style="min-height: 200px; border-style: dashed; border: 2px dashed #ccc; padding: 20px; margin-bottom: 30px;">

                                                    <input type="hidden" name="record_id" id="recordIdInput">
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
                            @elseif($step->id == 15)
                                <button class="btn btn-sm btn-icon btn-image mx-1 upload-btn" data-id="{{Auth::user()->company->id}}"><i class="mdi mdi-file-document-multiple-outline"></i></button>
                                <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"> بارگزاری فایل </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('storemedia') }}" enctype="multipart/form-data" class="dropzone" id="fileUploadZone" style="min-height: 200px; border-style: dashed; border: 2px dashed #ccc; padding: 20px; margin-bottom: 30px;">

                                                    <input type="hidden" name="record_id" id="recordIdInput">
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
                            @elseif($step->id == 16)
                                <button class="btn btn-sm btn-icon btn-image mx-1 upload-btn" data-id="{{Auth::user()->company->id}}"><i class="mdi mdi-file-document-multiple-outline"></i></button>
                                <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"> بارگزاری فایل </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('storemedia') }}" enctype="multipart/form-data" class="dropzone" id="fileUploadZone" style="min-height: 200px; border-style: dashed; border: 2px dashed #ccc; padding: 20px; margin-bottom: 30px;">

                                                    <input type="hidden" name="record_id" id="recordIdInput">
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
                            @elseif($step->id == 17)
                                <button class="btn btn-sm btn-icon btn-image mx-1 upload-btn" data-id="{{Auth::user()->company->id}}"><i class="mdi mdi-file-document-multiple-outline"></i></button>
                                <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"> بارگزاری فایل </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('storemedia') }}" enctype="multipart/form-data" class="dropzone" id="fileUploadZone" style="min-height: 200px; border-style: dashed; border: 2px dashed #ccc; padding: 20px; margin-bottom: 30px;">

                                                    <input type="hidden" name="record_id" id="recordIdInput">
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
                            @elseif($step->id == 18)
                                <div class="alert alert-info">در حال بررسی، لطفا منتظر بمانید...</div>
                            @elseif($step->id == 19)
                                <div class="alert alert-info">در حال بررسی، لطفا منتظر بمانید...</div>
                            @elseif($step->id == 20)
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
