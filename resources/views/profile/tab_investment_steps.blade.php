<div class="tab-pane fade justify-content-center" id="navs-invest-card" role="tabpanel">
    <div class="mb-4">
        <label class="form-label fw-bold">درصد پیشرفت فرآیند:</label>
        <div class="progress" style="height: 20px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ round(($projects->invest_step) / count($investsteps) * 100) }}%;" aria-valuenow="{{ count($investsteps) + 1 }}" aria-valuemin="0" aria-valuemax="{{ count($investsteps) }}">
                {{ round(($projects->invest_step ) / count($investsteps) * 100) }}%
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="list-group shadow-sm rounded" style="overflow-y:auto; max-height:620px;">
                @foreach($investsteps as $step)
                    <div class="list-group-item d-flex align-items-center py-2 {{ $step->id === ($projects->invest_step + 1) ? 'active' : '' }}"
                         style="cursor: default; border-right: 5px solid {{ $step->id < $projects->invest_step ? '#4caf50' : ($step->id === $projects->invest_step ? '#7367f0' : '#ddd') }};">
                        <span class="me-2 d-inline-flex justify-content-center align-items-center rounded-circle"
                              style="width: 28px; height: 28px; background: {{ $step->id < $projects->invest_step ? '#c8e6c9' : ($step->id === $projects->invest_step ? '#ede7f6' : '#f1f1f1') }};
                                     color: {{ $step->id < $projects->invest_step ? '#2e7d32' : ($step->id === $projects->invest_step ? '#5e35b1' : '#aaa') }};
                                     font-weight: bold;">
                            {{ $step->id + 1 }}
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
                            <span class="badge bg-primary me-2" style="width:26px;">{{ $projects->invest_step + 1 }}</span>
                            <h6 class="mb-0 fw-bold">{{ $step->title }}</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">{{ $step->description }}</p>
                            {!! $step->content !!}
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
