@extends('layouts.base')
@section('title', 'مکاتبات')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/page-correspondence.css') }}">
@endsection
@section('content')
    <div id="correspondence-skeleton" class="card conversation-card mb-3">
        <div class="card-body">
            <div class="skeleton line" style="width: 30%; height: 20px;"></div>
            <div class="skeleton line" style="width: 100%; height: 48px;"></div>
            <div class="skeleton line" style="width: 70%; height: 20px;"></div>
            <div class="skeleton line" style="width: 100%; height: 320px;"></div>
        </div>
    </div>

    <div id="correspondence-body" class="correspondence-page d-none">
        <div class="card conversation-card">
            <div class="card-body">
                <div class="conversation-search mb-3">
                    <div class="position-relative">
                        <span class="mdi mdi-magnify prefix"></span>
                        <input id="searchConversation" type="text" class="form-control ps-5" placeholder="جستجو مکالمه">
                    </div>
                </div>
                <div class="filter-chips" id="filterChips">
                    <span class="chip active" data-filter="all">همه</span>
                    <span class="chip" data-filter="unread">خوانده‌نشده</span>
                    <span class="chip" data-filter="archived">آرشیو</span>
                    <span class="chip" data-filter="muted">بی‌صدا</span>
                    <span class="chip" data-filter="sent">ارسالی</span>
                    <span class="chip" data-filter="type-internal">نوع: داخلی</span>
                    <span class="chip" data-filter="type-external">نوع: برون‌سازمانی</span>
                </div>
                <div class="conversation-list mt-3" id="conversationList"></div>
            </div>
        </div>
    </div>

    <!-- دکمه شناور نوشتن پیام جدید -->
    <button id="btnOpenCompose" type="button" class="btn btn-primary fab-compose" data-bs-toggle="modal" data-bs-target="#composeModal" title="نوشتن پیام جدید">
        <span class="mdi mdi-plus"></span>
    </button>

    <!-- Modal نوشتن پیام (استاندارد Bootstrap پروژه) -->
    <div class="modal fade" id="composeModal" tabindex="-1" aria-labelledby="composeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="composeModalLabel">پیام جدید</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <form id="composeForm">
                        <div class="mb-3">
                            <label for="composeSubject" class="form-label">موضوع</label>
                            <input id="composeSubject" type="text" class="form-control" placeholder="موضوع پیام">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">گیرندگان</label>
                            <select id="composeRecipients" class="form-control select2" multiple="multiple" style="width: 100%;"></select>
                            <div class="form-text">چند نفر را می‌توانید انتخاب کنید.</div>
                        </div>
                        <div class="mb-3">
                            <label for="composeBody" class="form-label">متن پیام</label>
                            <textarea id="composeBody" class="form-control" rows="4" placeholder="متن پیام"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">ضمیمه</label>
                            <input type="file" id="composeAttachment" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer text-center d-flex justify-content-center">
                    <button id="btnComposeSend" class="btn btn-primary">
                        ارسال
                        <span class="mdi mdi-send ms-1"></span>
                    </button>
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">انصراف</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal مشاهده مکالمه -->
    <div class="modal fade" id="viewConversationModal" tabindex="-1" aria-labelledby="viewConversationLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="viewSubject">...</h5>
                        <div id="viewParticipants"></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex gap-2 mb-3">
                        <button class="btn btn-outline-secondary btn-sm" id="actionArchiveModal">
                            <span class="mdi mdi-archive-outline me-1"></span> آرشیو
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" id="actionMuteModal">
                            <span class="mdi mdi-volume-off me-1"></span> بی‌صدا
                        </button>
                    </div>
                    <div id="viewMessages" class="message-scroll" style="max-height:65vh;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/correspondence-mock.js') }}"></script>
@endsection
