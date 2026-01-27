(function () {
    // Mock users
    const users = {
        1: {id: 1, name: 'امیر رضایی', initials: 'ار', color: '#3d5afe'},
        2: {id: 2, name: 'نگار محمدی', initials: 'نم', color: '#ff7043'},
        3: {id: 3, name: 'حسین امینی', initials: 'حا', color: '#26a69a'},
        4: {id: 4, name: 'مهسا قنبری', initials: 'مق', color: '#7e57c2'},
        5: {id: 5, name: 'کریم صادقی', initials: 'کس', color: '#ef6c00'}
    };

    // Mock conversations & messages
    const conversations = [
        {
            id: 'c1',
            subject: 'پیگیری قرارداد همکاری ۱۴۰۵',
            type: 'internal',
            participants: [1, 2, 3],
            archived: false,
            muted: false,
            unread: 2,
            lastActivity: '2026-01-26T13:45:00Z',
            messages: [
                {id: 'm1', senderId: 2, body: 'سلام؛ نسخه نهایی قرارداد را ارسال کردم، لطفاً تا عصر بازبینی کنید.', time: '2026-01-26T11:05:00Z', direction: 'incoming', edited: true, deleted: false},
                {id: 'm2', senderId: 1, body: 'دریافت شد، بندهای مالی را بررسی می‌کنم.', time: '2026-01-26T11:40:00Z', direction: 'outgoing', edited: false, deleted: false},
                {id: 'm3', senderId: 3, body: 'من هم از منظر حقوقی چک می‌کنم و اطلاع می‌دهم.', time: '2026-01-26T12:35:00Z', direction: 'incoming', edited: false, deleted: false},
                {id: 'm4', senderId: 2, body: 'مهلت امضا تا دوشنبه است؛ حتماً امروز جمع‌بندی کنیم.', time: '2026-01-26T13:45:00Z', direction: 'incoming', edited: false, deleted: false}
            ]
        },
        {
            id: 'c2',
            subject: 'گزارش ماهانه واحد پشتیبانی',
            type: 'internal',
            participants: [1, 4],
            archived: false,
            muted: true,
            unread: 0,
            lastActivity: '2026-01-25T09:10:00Z',
            messages: [
                {id: 'm1', senderId: 4, body: 'آمار تیکت‌ها و SLA در فایل پیوست است. نظر دهید.', time: '2026-01-25T08:15:00Z', direction: 'incoming', edited: false, deleted: false},
                {id: 'm2', senderId: 1, body: 'ممنون، نرخ رفع در ۴۸ ساعت باید بالاتر برود؛ جلسه می‌گذاریم.', time: '2026-01-25T09:10:00Z', direction: 'outgoing', edited: false, deleted: false}
            ]
        },
        {
            id: 'c3',
            subject: 'هماهنگی جلسه سرمایه‌گذاران',
            type: 'external',
            participants: [1, 2, 5],
            archived: true,
            muted: false,
            unread: 0,
            lastActivity: '2026-01-20T15:30:00Z',
            messages: [
                {id: 'm1', senderId: 5, body: 'جلسه با VC ها برای سه‌شنبه ۲ بهمن ساعت ۱۰ نهایی شد.', time: '2026-01-20T14:00:00Z', direction: 'incoming', edited: false, deleted: false},
                {id: 'm2', senderId: 1, body: 'عالیه. دک خلاصه مالی را تا فردا صبح می‌فرستم.', time: '2026-01-20T15:30:00Z', direction: 'outgoing', edited: false, deleted: false}
            ]
        },
        {
            id: 'c4',
            subject: 'درخواست پشتیبانی فوری دیتاسنتر',
            type: 'external',
            participants: [1, 3, 5],
            archived: false,
            muted: false,
            unread: 5,
            lastActivity: '2026-01-27T07:55:00Z',
            messages: [
                {id: 'm1', senderId: 5, body: 'CPU نود dtx-04 به ۹۵٪ رسیده، نیاز به بررسی فوری.', time: '2026-01-27T07:10:00Z', direction: 'incoming', edited: false, deleted: false},
                {id: 'm2', senderId: 3, body: 'سرویس لاگ را چک می‌کنم، احتمالاً یکی از کران‌ها قفل کرده.', time: '2026-01-27T07:22:00Z', direction: 'incoming', edited: false, deleted: false},
                {id: 'm3', senderId: 1, body: 'تا ۱۰ دقیقه آینده در دسترس هستم، لاگ را بفرستید.', time: '2026-01-27T07:40:00Z', direction: 'outgoing', edited: false, deleted: false},
                {id: 'm4', senderId: 5, body: 'لاگ پیوست شد. ظاهراً پردازش ایمیل‌های معوق است.', time: '2026-01-27T07:55:00Z', direction: 'incoming', edited: false, deleted: false}
            ]
        }
    ];

    const state = {
        activeConversationId: null,
        activeMessageId: null,
        search: '',
        chip: 'all'
    };

    const els = {
        skeleton: document.getElementById('correspondence-skeleton'),
        body: document.getElementById('correspondence-body'),
        list: document.getElementById('conversationList'),
        searchInput: document.getElementById('searchConversation'),
        filterChips: document.getElementById('filterChips'),
        composeModal: document.getElementById('composeModal'),
        btnOpenCompose: document.getElementById('btnOpenCompose'),
        composeForm: document.getElementById('composeForm'),
        composeSubject: document.getElementById('composeSubject'),
        composeBody: document.getElementById('composeBody'),
        composeRecipients: document.getElementById('composeRecipients'),
        composeAttachment: document.getElementById('composeAttachment'),
        btnComposeSend: document.getElementById('btnComposeSend'),
        viewModal: document.getElementById('viewConversationModal'),
        viewSubject: document.getElementById('viewSubject'),
        viewParticipants: document.getElementById('viewParticipants'),
        viewMessages: document.getElementById('viewMessages'),
        actionArchiveModal: document.getElementById('actionArchiveModal'),
        actionMuteModal: document.getElementById('actionMuteModal'),
        actionAddModal: document.getElementById('actionAddModal')
    };

    function init() {
        setTimeout(() => {
            if (els.skeleton) els.skeleton.classList.add('d-none');
            if (els.body) els.body.classList.remove('d-none');
            initializeUi();
            state.activeConversationId = conversations[0]?.id || null;
            if (state.activeConversationId) {
                markAsRead(state.activeConversationId);
            }
            renderMessageList();
        }, 450);

        bindEvents();
        initComposeModal();
    }

    function initializeUi() {
        reInitTooltips();
    }

    function initComposeModal() {
        if (els.composeRecipients) {
            els.composeRecipients.innerHTML = Object.values(users)
                .map(u => `<option value="${u.id}">${u.name}</option>`).join('');
            $(els.composeRecipients).select2({
                width: '100%',
                dir: 'rtl',
                placeholder: 'انتخاب گیرندگان',
                closeOnSelect: false,
                dropdownParent: $('#composeModal')
            });
        }
    }

    function reInitTooltips() {
        const tooltipEls = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [data-tooltip]'));
        tooltipEls.forEach(el => {
            const existing = bootstrap.Tooltip.getInstance(el);
            if (existing) existing.dispose();
            new bootstrap.Tooltip(el);
        });
    }

    function bindEvents() {
        els.searchInput?.addEventListener('input', (e) => {
            state.search = e.target.value.trim();
            renderMessageList();
        });

        els.filterChips?.addEventListener('click', (e) => {
            const chip = e.target.closest('.chip');
            if (!chip) return;
            state.chip = chip.dataset.filter;
            Array.from(els.filterChips.querySelectorAll('.chip')).forEach(c => c.classList.toggle('active', c === chip));
            renderMessageList();
        });

        els.btnComposeSend?.addEventListener('click', (e) => {
            e.preventDefault();
            handleComposeSend();
        });

        els.viewMessages?.addEventListener('click', (e) => {
            const actionBtn = e.target.closest('[data-action]');
            if (!actionBtn) return;
            const {action, messageId} = actionBtn.dataset;
            handleMessageAction(action, messageId);
        });

        els.actionArchiveModal?.addEventListener('click', toggleArchive);
        els.actionMuteModal?.addEventListener('click', toggleMute);
        els.actionAddModal?.addEventListener('click', () => showToast('UI فقط: انتخاب عضو جدید پیاده‌سازی نشده است.'));
    }

    function renderMessageList() {
        if (!els.list) return;
        const flattened = flattenMessages()
            .filter(filterByChipMessage)
            .filter(filterBySearchMessage)
            .sort((a, b) => new Date(b.time) - new Date(a.time));

        if (!flattened.length) {
            els.list.innerHTML = '<div class="text-muted text-center py-3">موردی یافت نشد.</div>';
            return;
        }

        els.list.innerHTML = flattened.map(msg => {
            const activeClass = state.activeMessageId === msg.id ? 'active' : '';
            const unreadBadge = msg.unread ? `<span class="badge unread text-white">${msg.unread}</span>` : '';
            const flags = `
                ${msg.archived ? '<span class="badge bg-info text-dark">آرشیو</span>' : ''}
                ${msg.muted ? '<span class="badge bg-secondary">بی‌صدا</span>' : ''}
            `;
            return `<div class="conversation-item ${activeClass}" data-id="${msg.id}" data-conv="${msg.conversationId}">
                        <div class="p-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-600 truncate" title="${msg.subject}">${msg.subject}</span>
                                ${unreadBadge}
                            </div>
                            <div class="text-muted truncate" title="${msg.senderName}">${msg.senderName}</div>
                            <div class="text-muted truncate mt-1">${msg.preview}</div>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <small class="text-muted">${formatRelative(msg.time)}</small>
                                <div class="badges-inline">${flags}</div>
                            </div>
                        </div>
                    </div>`;
        }).join('');

        Array.from(els.list.querySelectorAll('.conversation-item')).forEach(item => {
            item.addEventListener('click', () => {
                state.activeConversationId = item.dataset.conv;
                state.activeMessageId = item.dataset.id;
                markAsRead(item.dataset.conv);
                renderMessageList();
                openMessageModal();
            });
        });
    }

    function flattenMessages() {
        const list = [];
        conversations.forEach(conv => {
            conv.messages.forEach(m => {
                list.push({
                    id: m.id,
                    conversationId: conv.id,
                    subject: conv.subject,
                    senderId: m.senderId,
                    senderName: users[m.senderId]?.name || 'نامشخص',
                    body: m.body || '',
                    time: m.time,
                    archived: conv.archived,
                    muted: conv.muted,
                    unread: conv.unread,
                    type: conv.type,
                    deleted: m.deleted,
                    direction: m.direction,
                    preview: m.deleted ? 'پیام حذف شده' : (m.body || '')
                });
            });
        });
        return list;
    }

    function filterByChipMessage(msg) {
        switch (state.chip) {
            case 'unread': return msg.unread > 0;
            case 'archived': return msg.archived;
            case 'muted': return msg.muted;
            case 'type-internal': return msg.type === 'internal';
            case 'type-external': return msg.type === 'external';
            case 'sent': return msg.direction === 'outgoing';
            default: return true;
        }
    }

    function filterBySearchMessage(msg) {
        if (!state.search) return true;
        const q = state.search.toLowerCase();
        return msg.subject.toLowerCase().includes(q) || msg.senderName.toLowerCase().includes(q) || msg.body.toLowerCase().includes(q);
    }

    function openMessageModal() {
        const msg = flattenMessages().find(m => m.id === state.activeMessageId);
        if (!msg) return;
        const conv = conversations.find(c => c.id === msg.conversationId);
        if (!conv) return;

        if (els.viewSubject) els.viewSubject.textContent = msg.subject;
        if (els.viewParticipants) els.viewParticipants.innerHTML = conv.participants.map(id => renderAvatar(users[id])).join('');
        toggleHeaderActions(conv.archived, conv.muted);

        if (els.viewMessages) {
            const tags = [];
            if (msg.archived) tags.push('<span class="badge bg-info text-dark">آرشیو</span>');
            if (msg.muted) tags.push('<span class="badge bg-secondary">بی‌صدا</span>');
            const body = msg.body || '';
            els.viewMessages.innerHTML = `
                <div class="message-card">
                    <div class="message-card-header">
                        <div class="fw-600">${msg.senderName}</div>
                        <div class="message-card-meta">
                            <span>${formatDateTime(msg.time)}</span>
                            <div class="message-card-tags">${tags.join('')}</div>
                        </div>
                    </div>
                    <div class="message-body">${msg.deleted ? 'پیام حذف شده است.' : body.replace(/\\n/g, '<br>')}</div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <button class="btn btn-sm btn-outline-primary" data-action="reply" data-message-id="${msg.id}">
                            <span class="mdi mdi-reply"></span> پاسخ
                        </button>
                        <div class="message-actions"></div>
                    </div>
                </div>`;
            reInitTooltips();
        }

        const modal = bootstrap.Modal.getInstance(els.viewModal) || new bootstrap.Modal(els.viewModal);
        modal.show();
    }

    function renderAvatar(user) {
        if (!user) return '';
        return `<span class="participant-avatar" style="background:${user.color};" title="${user.name}">${user.initials}</span>`;
    }

    function renderMessage(msg, conv) {
        const sender = users[msg.senderId];
        const cardClasses = ['message-card'];
        if (msg.deleted) cardClasses.push('deleted');

        const tags = [];
        tags.push(msg.direction === 'incoming'
            ? '<span class="badge bg-info text-dark">دریافتی</span>'
            : '<span class="badge bg-success">ارسالی</span>');
        const actions = `<div class="message-actions">
                <a href="#!" data-action="reply" data-message-id="${msg.id}" data-bs-toggle="tooltip" title="پاسخ"><span class="mdi mdi-reply"></span></a>
           </div>`;

        const content = msg.deleted ? 'پیام حذف شده است.' : msg.body;

        return `
            <div class="${cardClasses.join(' ')}">
                <div class="message-card-header">
                    <div class="fw-600 truncate" title="${sender?.name || 'نامشخص'}">${sender?.name || 'نامشخص'}</div>
                    <div class="message-card-meta">
                        <span>${formatDateTime(msg.time)}</span>
                        <div class="message-card-tags">${tags.join('')}</div>
                    </div>
                </div>
                <div class="message-body">${content}</div>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="text-muted">فرستنده: ${sender?.name || ''}</span>
                    ${actions}
                </div>
            </div>
        `;
    }

    function groupByDate(messages) {
        return messages.reduce((acc, msg) => {
            const dateKey = msg.time.split('T')[0];
            if (!acc[dateKey]) acc[dateKey] = [];
            acc[dateKey].push(msg);
            return acc;
        }, {});
    }

    function formatDate(dateStr) {
        const d = new Date(dateStr);
        return d.toLocaleDateString('fa-IR', {weekday: 'long', day: '2-digit', month: 'long'});
    }

    function formatDateTime(dateStr) {
        const d = new Date(dateStr);
        return d.toLocaleString('fa-IR', {hour: '2-digit', minute: '2-digit', day: '2-digit', month: 'short'});
    }

    function formatRelative(dateStr) {
        const d = new Date(dateStr);
        const now = new Date();
        const diff = (now - d) / (1000 * 60); // minutes
        if (diff < 60) return `${Math.max(1, Math.round(diff))} دقیقه قبل`;
        if (diff < 1440) return `${Math.round(diff / 60)} ساعت قبل`;
        return d.toLocaleDateString('fa-IR');
    }

    function markAsRead(conversationId) {
        const conv = conversations.find(c => c.id === conversationId);
        if (!conv) return;
        conv.unread = 0;
        conv.lastActivity = conv.messages[conv.messages.length - 1]?.time || conv.lastActivity;
    }

    function handleMessageAction(action, messageId) {
        const conv = conversations.find(c => c.id === state.activeConversationId);
        if (!conv) return;
        const msg = conv.messages.find(m => m.id === messageId);
        if (!msg) return;

        if (action === 'delete') {
            return; // حذف غیرفعال شد
        } else if (action === 'reply') {
            if (els.composeBody) {
                els.composeBody.value = `↪ ${msg.body}\n\n`;
            }
            if (els.composeSubject) {
                els.composeSubject.value = `Re: ${conv.subject}`;
            }
            // ابتدا مودال مشاهده را می‌بندیم تا Compose روی آن قرار گیرد
            const viewModalInstance = bootstrap.Modal.getInstance(els.viewModal);
            viewModalInstance?.hide();
            const composeModalInstance = bootstrap.Modal.getInstance(els.composeModal) || new bootstrap.Modal(els.composeModal);
            composeModalInstance.show();
        }
        openMessageModal();
    }

    function handleComposeSend() {
        const subject = (els.composeSubject?.value || '').trim();
        const body = (els.composeBody?.value || '').trim();
        const recipientIds = $(els.composeRecipients || []).val() || [];
        const attachmentName = els.composeAttachment?.files?.[0]?.name || '';

        if (!subject || !body || !recipientIds.length) {
            showToast('موضوع، متن و گیرندگان الزامی است.');
            return;
        }

        const now = new Date().toISOString();
        const newId = `c-${Date.now()}`;
        const participants = Array.from(new Set([1, ...recipientIds.map(Number)]));

        const newConv = {
            id: newId,
            subject,
            type: 'internal',
            participants,
            archived: false,
            muted: false,
            unread: 0,
            lastActivity: now,
            messages: [
                {
                    id: `m-${Date.now()}`,
                    senderId: 1,
                    body: body + (attachmentName ? `\n\n[ضمیمه: ${attachmentName}]` : ''),
                    time: now,
                    direction: 'outgoing',
                    edited: false,
                    deleted: false
                }
            ]
        };

        conversations.unshift(newConv);
        state.activeConversationId = newId;
        renderMessageList();
        const modal = bootstrap.Modal.getInstance(els.composeModal) || new bootstrap.Modal(els.composeModal);
        modal.hide();
        resetComposeForm();
        showToast('پیام ارسال شد (شبیه‌سازی).');
    }

    function resetComposeForm() {
        if (els.composeSubject) els.composeSubject.value = '';
        if (els.composeBody) els.composeBody.value = '';
        if (els.composeAttachment) els.composeAttachment.value = '';
        $(els.composeRecipients || []).val(null).trigger('change');
        // nothing
    }

    function toggleArchive() {
        const conv = conversations.find(c => c.id === state.activeConversationId);
        if (!conv) return;
        conv.archived = !conv.archived;
        toggleHeaderActions(conv.archived, conv.muted);
        renderMessageList();
        showToast(conv.archived ? 'مکالمه آرشیو شد.' : 'از آرشیو خارج شد.');
    }

    function toggleMute() {
        const conv = conversations.find(c => c.id === state.activeConversationId);
        if (!conv) return;
        conv.muted = !conv.muted;
        toggleHeaderActions(conv.archived, conv.muted);
        renderMessageList();
        showToast(conv.muted ? 'مکالمه بی‌صدا شد.' : 'صدا فعال شد.');
    }

    function toggleHeaderActions(isArchived, isMuted) {
        if (els.actionArchiveModal) {
            els.actionArchiveModal.classList.toggle('active', isArchived);
        }
        if (els.actionMuteModal) {
            els.actionMuteModal.classList.toggle('active', isMuted);
        }
    }

    function showToast(message) {
        const toastEl = document.createElement('div');
        toastEl.className = 'toast align-items-center text-bg-primary border-0 position-fixed';
        toastEl.style.zIndex = 9999;
        toastEl.style.left = '20px';
        toastEl.style.bottom = '20px';
        toastEl.role = 'alert';
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>`;
        document.body.appendChild(toastEl);
        const toast = new bootstrap.Toast(toastEl, {delay: 2200});
        toast.show();
        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    }

    document.addEventListener('DOMContentLoaded', init);
})();
