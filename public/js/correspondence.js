(function () {
    if (window.__CORRESPONDENCE_BOOTED__) return;
    window.__CORRESPONDENCE_BOOTED__ = true;
    const {users, conversations, authUserId} = window.CORRESPONDENCE_DATA;

    const state = {
        activeConversationId: null,
        activeMessageId: null,
        search: '',
        chip: 'all'
    };
    const PAGINATION = {
        page: 1,
        perPage: 10
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

    function normalizeId(id) {
        return id == null ? '' : String(id);
    }

    function renderAvatar(user) {
        if (!user) return '';
        const initials = (user.name || '').split(' ').map(p => p[0]).join('').toUpperCase();
        const color = user.color || '#6c757d';

        return `
        <span
            class="avatar"
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="${user.name}"
            style="
                background-color:${color};
                width:28px;
                height:28px;
                display:inline-flex;
                align-items:center;
                justify-content:center;
                border-radius:50%;
                font-size:12px;
                color:#fff;
                margin-left:4px;
                cursor:default
            ">
            ${initials}
        </span>
    `;
    }

    const channelSubscriptions = window.__CORRESPONDENCE_CHANNELS__ || (window.__CORRESPONDENCE_CHANNELS__ = new Set());
    const channelBindings = window.__CORRESPONDENCE_CHANNEL_BINDINGS__ || (window.__CORRESPONDENCE_CHANNEL_BINDINGS__ = new Set());
    const pusher = window.__CORRESPONDENCE_PUSHER__ || (window.__CORRESPONDENCE_PUSHER__ = new Pusher(window.PUSHER_CONFIG.key, {
        cluster: window.PUSHER_CONFIG.cluster,
        forceTLS: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }
    }));

    let refreshTimer = null;
    let pusherConnected = false;
    let offlinePoller = null;

    pusher.connection.bind('connected', () => {
        pusherConnected = true;
        stopOfflinePolling();
    });

    pusher.connection.bind('disconnected', () => {
        pusherConnected = false;
        startOfflinePolling();
    });

    pusher.connection.bind('error', () => {
        pusherConnected = false;
        startOfflinePolling();
    });

    window.addEventListener('offline', () => {
        pusherConnected = false;
        startOfflinePolling();
    });

    window.addEventListener('online', () => {
        if (!pusherConnected) {
            try { pusher.connect(); } catch (e) {}
        }
        stopOfflinePolling();
    });
    function init() {
        setTimeout(() => {
            if (els.skeleton) els.skeleton.classList.add('d-none');
            if (els.body) els.body.classList.remove('d-none');
            initializeUi();
            state.activeConversationId = conversations[0]?.id || null;
            if (state.activeConversationId) markAsRead(state.activeConversationId);
            renderMessageList();
        }, 450);

        bindEvents();
        initRealtime();
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

        els.searchInput?.addEventListener('input', resetPagination);
        els.filterChips?.addEventListener('click', resetPagination);

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

        const totalPages = Math.ceil(flattened.length / PAGINATION.perPage);
        PAGINATION.page = Math.min(PAGINATION.page, totalPages);

        const start = (PAGINATION.page - 1) * PAGINATION.perPage;
        const items = flattened.slice(start, start + PAGINATION.perPage);

        els.list.innerHTML = `
        ${items.map(renderConversationItem).join('')}
        ${renderPagination(totalPages)}
    `;

        bindConversationClicks();
    }
    function renderConversationItem(msg) {
        const activeClass = state.activeMessageId === msg.id ? 'active' : '';
        const unreadBadge = msg.unread ? `<span class="badge unread text-white">${msg.unread}</span>` : '';

        return `
    <div class="conversation-item ${activeClass}" data-id="${msg.id}" data-conv="${msg.conversationId}">
        <div class="p-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-600 truncate">موضوع: ${msg.subject}</span>
                ${unreadBadge}
            </div>
            <div class="text-muted truncate mt-1">متن پیام: ${msg.preview}</div>
            <hr>
            <div class="d-flex justify-content-between align-items-center mt-1">
                <small class="text-muted" style="font-size:11px">${formatRelative(msg.time)}</small>
            </div>
        </div>
    </div>`;
    }
    function renderPagination(totalPages) {
        if (totalPages <= 1) return '';

        let html = `<div class="d-flex justify-content-center gap-1 py-2">`;

        for (let i = 1; i <= totalPages; i++) {
            html += `
            <button
                class="btn btn-sm ${i === PAGINATION.page ? 'btn-primary' : 'btn-outline-secondary'}"
                data-page="${i}">
                ${i}
            </button>
        `;
        }

        html += `</div>`;
        return html;
    }
    els.list.addEventListener('click', (e) => {
        const pageBtn = e.target.closest('[data-page]');
        if (!pageBtn) return;

        PAGINATION.page = Number(pageBtn.dataset.page);
        renderMessageList();
    });
    function resetPagination() {
        PAGINATION.page = 1;
    }
    function bindConversationClicks() {
        Array.from(els.list.querySelectorAll('.conversation-item')).forEach(item => {
            item.addEventListener('click', () => {
                state.activeConversationId = item.dataset.conv;
                subscribeActiveConversationChannel();
                state.activeMessageId = item.dataset.id;
                markAsRead(item.dataset.conv);
                renderMessageList();
                openMessageModal(item.dataset.conv);
            });
        });
    }
    function flattenMessages() {
        const list = [];
        conversations.forEach(conv => {
            const root = conv.messages.root;
            if (!root) return;
            const lastMsg = getLastMessage(conv) || root;

            const recipientNames = (conv.participants || [])
                .filter(id => Number(id) !== Number(lastMsg.senderId))
                .map(id => users[id]?.name)
                .filter(Boolean)
                .join('، ');

            list.push({
                id: root.id,
                conversationId: conv.id,
                subject: conv.subject,
                senderId: lastMsg.senderId,
                senderName: users[lastMsg.senderId]?.name || 'نامشخص',
                recipients: recipientNames, // ⬅️ اضافه شد
                body: lastMsg.body || '',
                time: lastMsg.time,
                archived: conv.archived,
                muted: conv.muted,
                unread: conv.unread,
                type: conv.type,
                deleted: false,
                direction: root.direction,
                attachments: root.attachments || [],
                preview: lastMsg.body || ''
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

    function openMessageModal(conversationId) {
        const convId = normalizeId(conversationId);
        const conv = conversations.find(c => normalizeId(c.id) === convId);
        if (!conv) return;

        if (els.viewSubject) els.viewSubject.textContent = conv.subject;

        // ⬇️ این قسمت مهم است
        if (els.viewParticipants) {
            els.viewParticipants.innerHTML = conv.participants
                .map(id => users[id])
                .filter(Boolean)
                .map(user => renderAvatar(user))
                .join('');

            // tooltip ها دوباره init شوند
            setTimeout(() => reInitTooltips(), 0);
        }

        els.viewModal.dataset.conversationId = conv.id;

        if (els.viewMessages) {
            els.viewMessages.innerHTML = renderThread(conv);
        }

        toggleHeaderActions(conv.archived, conv.muted);

        const modal = bootstrap.Modal.getInstance(els.viewModal) || new bootstrap.Modal(els.viewModal);
        modal.show();
    }

    function renderThread(conv) {
        let html = '';
        const rootMsg = conv.messages.root;
        if (rootMsg) html += renderMessage(rootMsg, false);
        (conv.messages.replies || []).forEach(r => html += renderMessage(r, true));
        return html;
    }

    function getLastMessage(conv) {
        const all = [];
        if (conv?.messages?.root) all.push(conv.messages.root);
        if (Array.isArray(conv?.messages?.replies)) all.push(...conv.messages.replies);
        if (!all.length) return null;
        return all.reduce((latest, msg) => {
            if (!latest) return msg;
            const latestTime = new Date(latest.time).getTime();
            const msgTime = new Date(msg.time).getTime();
            return msgTime >= latestTime ? msg : latest;
        }, null);
    }

    function renderMessage(msg, isReply) {
        const sender = users[msg.senderId];
        let attachmentsHtml = '';
        if (msg.attachments?.length) {
            attachmentsHtml = `<div class="message-attachments mt-2">
                ${msg.attachments.map(a => `<a href="${a.url}" target="_blank" class="d-block text-decoration-none">📎 ${a.name}</a>`).join('')}
            </div>`;
        }
        const isSelf = Number(msg.senderId) === Number(authUserId);
        const alignmentClass = isSelf ? 'message-self' : 'message-other';
        const replyClass = isReply ? 'message-reply' : '';
        const canReply = !isSelf;
        return `<div class="message-card ${alignmentClass} ${replyClass}">
            <div class="fw-600">${sender?.name || ''}</div>
            <small class="text-muted">${formatDateTime(msg.time)}</small>
            <div class="message-body mt-2">${msg.body}</div>
            ${attachmentsHtml}
            ${canReply ? `<button class="btn btn-sm btn-outline-primary mt-2" data-action="reply" data-message-id="${msg.id}">پاسخ</button>` : ''}
        </div>`;
    }

    function formatDateTime(dateStr) {
        const d = new Date(dateStr);
        return d.toLocaleString('fa-IR', {hour: '2-digit', minute: '2-digit', day: '2-digit', month: 'short'});
    }

    function formatRelative(dateStr) {
        const d = new Date(dateStr);
        const now = new Date();
        const diff = (now - d) / (1000 * 60);
        if (diff < 60) return `${Math.max(1, Math.round(diff))} دقیقه قبل`;
        if (diff < 1440) return `${Math.round(diff / 60)} ساعت قبل`;
        return d.toLocaleDateString('fa-IR');
    }

    function markAsRead(conversationId) {
        const convId = normalizeId(conversationId);
        const conv = conversations.find(c => normalizeId(c.id) === convId);
        if (!conv) return;
        conv.unread = 0;
        conv.lastActivity = conv.messages.root.time || conv.lastActivity;
    }

    function handleMessageAction(action, messageId) {
        const convId = normalizeId(state.activeConversationId);
        const conv = conversations.find(c => normalizeId(c.id) === convId);
        if (!conv) return;
        const allMsgs = [conv.messages.root].concat(conv.messages.replies || []).filter(Boolean);
        const msg = allMsgs.find(m => m.id === messageId);
        if (!msg) return;
        if (action === 'delete') return;
        if (action === 'reply') handleReply(messageId);
        openMessageModal(conv.id);
    }

    function handleReply(messageId) {
        els.composeForm.dataset.parentId = messageId.replace('m','');
        const viewModalInstance = bootstrap.Modal.getInstance(els.viewModal);
        viewModalInstance?.hide();

        const convId = normalizeId(els.viewModal.dataset.conversationId);
        const conv = conversations.find(c => normalizeId(c.id) === convId);
        if (conv) {
            els.composeForm.dataset.conversationId = conv.id;
        }
        els.composeSubject.value = conv?.subject || '';
        els.composeBody.value = '';
        setRecipientsForReply(conv);

        setTimeout(() => {
            bootstrap.Modal.getOrCreateInstance(els.composeModal).show();
        }, 300);
    }

    function setRecipientsForReply(conv) {
        if (!els.composeRecipients) return;
        if (!conv) {
            clearReplyRecipients();
            return;
        }
        const allIds = conv?.participants || [];
        const replyRecipients = allIds.filter(id => Number(id) !== Number(authUserId));

        $(els.composeRecipients).val(replyRecipients.map(String)).trigger('change');
        $(els.composeRecipients).prop('disabled', true);

        const group = els.composeRecipients.closest('.mb-3');
        if (group) group.classList.add('d-none');

        els.composeForm.dataset.isReply = '1';
    }

    function clearReplyRecipients() {
        if (!els.composeRecipients) return;
        $(els.composeRecipients).prop('disabled', false);

        const group = els.composeRecipients.closest('.mb-3');
        if (group) group.classList.remove('d-none');

        delete els.composeForm.dataset.isReply;
    }

    function setComposeSending(isSending) {
        if (!els.btnComposeSend) return;
        if (!els.btnComposeSend.dataset.defaultHtml) {
            els.btnComposeSend.dataset.defaultHtml = els.btnComposeSend.innerHTML;
        }

        els.btnComposeSend.disabled = isSending;
        els.btnComposeSend.dataset.loading = isSending ? '1' : '0';

        if (isSending) {
            els.btnComposeSend.innerHTML = '<span class="spinner-border spinner-border-sm ms-1" role="status" aria-hidden="true"></span>در حال ارسال...';
            return;
        }

        els.btnComposeSend.innerHTML = els.btnComposeSend.dataset.defaultHtml;
    }

    function handleComposeSend() {
        if (els.btnComposeSend?.dataset.loading === '1') return;

        const subject = (els.composeSubject?.value || '').trim();
        const body = (els.composeBody?.value || '').trim();
        const recipientIds = $(els.composeRecipients || []).val() || [];
        const conversationId = els.composeForm.dataset.conversationId || '';
        const parentId = els.composeForm.dataset.parentId || '';

        if (!body || (!recipientIds.length && !conversationId)) return showToast('متن و گیرندگان الزامی است.');

        const formData = new FormData();
        formData.append('subject', subject);
        formData.append('body', body);
        recipientIds.forEach(id => formData.append('recipients[]', id));
        if (conversationId) formData.append('conversation_id', conversationId);
        if (parentId) formData.append('parent_id', parentId);

        if (els.composeAttachment?.files?.length) {
            Array.from(els.composeAttachment.files).forEach(file => formData.append('attachments[]', file));
        }

        setComposeSending(true);
        fetch(window.CORRESPONDENCE_POST_URL, {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},
            body: formData
        })
            .then(res => res.ok ? res.json() : Promise.reject())
            .then(data => {
                const modal = bootstrap.Modal.getInstance(els.composeModal) || new bootstrap.Modal(els.composeModal);
                modal.hide();

                const attachments = els.composeAttachment?.files
                    ? Array.from(els.composeAttachment.files).map(f => ({id: f.name,name: f.name,url: URL.createObjectURL(f),size: f.size,mime: f.type}))
                    : [];

                const recipientNames = recipientIds
                    .map(id => users[id]?.name)
                    .filter(Boolean)
                    .join('، ');

                const newMessage = {
                    id: 'm' + data.message_id,
                    senderId: authUserId,
                    body,
                    time: new Date().toISOString(),
                    direction: 'outgoing',
                    attachments,
                    recipients: recipientNames // ⬅️ اضافه شد
                };

                const targetConversationId = data.conversation_id || conversationId || state.activeConversationId;
                const targetConvId = normalizeId(targetConversationId);
                let conv = conversations.find(c => normalizeId(c.id) === targetConvId);

                if (conv) {
                    if (parentId) conv.messages.replies.push(newMessage);
                    else conv.messages.root = newMessage;
                    conv.lastActivity = newMessage.time;
                } else {
                    const participants = Array.from(new Set([authUserId].concat(recipientIds.map(id => Number(id)))));
                    conv = {
                        id: targetConversationId,
                        subject: subject || '(بدون موضوع)',
                        participants,
                        unread: 0,
                        lastActivity: newMessage.time,
                        messages: {root: newMessage, replies: []}
                    };
                    conversations.unshift(conv);
                }

                state.activeConversationId = conv.id;
                state.activeMessageId = newMessage.id;
                renderMessageList();

                resetComposeForm();
                showToast('پیام با موفقیت ارسال شد.');
            })
            .catch(() => showToast('خطا در ارسال پیام.'))
            .finally(() => setComposeSending(false));
    }

    function resetComposeForm() {
        els.composeSubject.value = '';
        els.composeBody.value = '';
        els.composeAttachment.value = '';
        $(els.composeRecipients).val(null).trigger('change');
        delete els.composeForm.dataset.parentId;
        delete els.composeForm.dataset.conversationId;
        clearReplyRecipients();
    }

    function toggleArchive() {
        const convId = normalizeId(state.activeConversationId);
        const conv = conversations.find(c => normalizeId(c.id) === convId);
        if (!conv) return;
        conv.archived = !conv.archived;
        toggleHeaderActions(conv.archived, conv.muted);
        renderMessageList();
        showToast(conv.archived ? 'مکالمه آرشیو شد.' : 'از آرشیو خارج شد.');
    }

    function toggleMute() {
        const convId = normalizeId(state.activeConversationId);
        const conv = conversations.find(c => normalizeId(c.id) === convId);
        if (!conv) return;
        conv.muted = !conv.muted;
        toggleHeaderActions(conv.archived, conv.muted);
        renderMessageList();
        showToast(conv.muted ? 'مکالمه بی‌صدا شد.' : 'صدا فعال شد.');
    }

    function toggleHeaderActions(isArchived,isMuted){
        if(els.actionArchiveModal) els.actionArchiveModal.classList.toggle('active',isArchived);
        if(els.actionMuteModal) els.actionMuteModal.classList.toggle('active',isMuted);
    }

    function showToast(message){
        const toastEl=document.createElement('div');
        toastEl.className='toast align-items-center text-bg-primary border-0 position-fixed';
        toastEl.style.zIndex=9999; toastEl.style.left='20px'; toastEl.style.bottom='20px'; toastEl.role='alert';
        toastEl.innerHTML=`<div class="d-flex"><div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div>`;
        document.body.appendChild(toastEl);
        const toast=new bootstrap.Toast(toastEl,{delay:2200}); toast.show();
        toastEl.addEventListener('hidden.bs.toast',()=>toastEl.remove());
    }

    function scheduleRefresh() {
        if (!window.CORRESPONDENCE_REFRESH_URL) return;
        if (refreshTimer) return;
        refreshTimer = setTimeout(() => {
            refreshTimer = null;
            refreshConversations();
        }, 300);
    }

    function refreshConversations() {
        fetch(window.CORRESPONDENCE_REFRESH_URL, {
            headers: {'Accept': 'application/json'}
        })
            .then(res => res.ok ? res.json() : Promise.reject())
            .then(data => {
                if (!data || !Array.isArray(data.conversations)) return;
                if (data.users) Object.assign(users, data.users);
                conversations.splice(0, conversations.length, ...data.conversations);
                renderMessageList();
                subscribeActiveConversationChannel();

                const isModalOpen = els.viewModal?.classList.contains('show');
                if (isModalOpen && state.activeConversationId) {
                    openMessageModal(state.activeConversationId);
                }
            })
            .catch(() => {});
    }

    function initRealtime(){
        if (navigator.onLine && pusherConnected) {
            subscribeRefreshChannel();
            subscribeActiveConversationChannel();
        } else {
            startOfflinePolling();
        }
    }


    function ensureChannel(name) {
        if (!pusherConnected) return null;

        if (!channelSubscriptions.has(name)) {
            try {
                pusher.subscribe(name);
                channelSubscriptions.add(name);
            } catch (e) {
                startOfflinePolling();
                return null;
            }
        }
        return pusher.channel(name);
    }

    function bindOnce(channel, event, handler) {
        if (!channel) return;
        const key = `${channel.name}:${event}`;
        if (channelBindings.has(key)) return;
        channel.bind(event, handler);
        channelBindings.add(key);
    }

    function subscribeRefreshChannel() {
        const refreshChannel = ensureChannel('correspondence.refresh');
        bindOnce(refreshChannel, 'correspondence.refresh', function(){
            scheduleRefresh();
        });
    }

    function subscribeActiveConversationChannel() {
        if (!state.activeConversationId) return;
        const channel = ensureChannel(`private-conversation.${state.activeConversationId}`);
        bindOnce(channel, 'message.sent', function(data){
            const target = conversations.find(c=>c.id==data.conversation_id);
            if(!target) return;

            if(data.parent_id) target.messages.replies.push(data.message);
            else target.messages.root = data.message;

            target.lastActivity = data.message.time || target.lastActivity;
            if (Number(data.message.senderId) !== Number(authUserId)) {
                if (state.activeConversationId === target.id) {
                    markAsRead(target.id);
                } else {
                    target.unread = (Number(target.unread) || 0) + 1;
                }
            }

            if(state.activeConversationId===target.id) openMessageModal(target.id);
            renderMessageList();
        });
    }
    function startOfflinePolling() {
        if (offlinePoller) return;
        offlinePoller = setInterval(() => {
            refreshConversations();
        }, 5000);
    }

    function stopOfflinePolling() {
        if (!offlinePoller) return;
        clearInterval(offlinePoller);
        offlinePoller = null;
    }
    document.addEventListener('DOMContentLoaded', init);
})();
