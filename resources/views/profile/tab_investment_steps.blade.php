<div class="tab-pane fade justify-content-center" id="navs-invest-card" role="tabpanel">
    <div class="mb-4">
        <label class="form-label fw-bold">درصد پیشرفت فرآیند:</label>
        <div class="progress" style="height: 20px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ round(($projects->invest_step) - 1 / count($investsteps) * 100) }}%;" aria-valuenow="{{ count($investsteps) + 1 }}" aria-valuemin="0" aria-valuemax="{{ count($investsteps) }}">
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
                            @elseif($step->id == 3)
                            @elseif($step->id == 4)
                            @elseif($step->id == 5)
                            @elseif($step->id == 6)
                            @elseif($step->id == 7)
                            @elseif($step->id == 8)
                            @elseif($step->id == 9)
                            @elseif($step->id == 10)
                            @elseif($step->id == 11)
                            @elseif($step->id == 12)
                            @elseif($step->id == 13)
                            @elseif($step->id == 14)
                            @elseif($step->id == 15)
                            @elseif($step->id == 16)
                            @elseif($step->id == 17)
                            @elseif($step->id == 18)
                            @elseif($step->id == 19)
                            @elseif($step->id == 20)
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
