(function () {

    /* =====================================================
     * DATA
     * ===================================================== */

    const { users, conversations, authUserId } = window.CORRESPONDENCE_DATA;

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
    };

    /* =====================================================
     * PUSHER
     * ===================================================== */

    const pusher = new Pusher(window.PUSHER_CONFIG.key, {
        cluster: window.PUSHER_CONFIG.cluster,
        forceTLS: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]').content
            }
        }
    });

    const subscribedChannels = new Set();

    function subscribeConversation(conversationId) {
        if (subscribedChannels.has(conversationId)) return;

        const channel = pusher.subscribe(
            `private-conversation.${conversationId}`
        );

        channel.bind('message.sent', handleIncomingMessage);
        subscribedChannels.add(conversationId);
    }

    /* =====================================================
     * INIT
     * ===================================================== */

    function init() {
        setTimeout(() => {
            els.skeleton?.classList.add('d-none');
            els.body?.classList.remove('d-none');

            conversations.forEach(c => subscribeConversation(c.id));
            state.activeConversationId = conversations[0]?.id || null;

            renderMessageList();
        }, 300);

        bindEvents();
        initComposeModal();
    }

    /* =====================================================
     * REALTIME HANDLER
     * ===================================================== */

    function handleIncomingMessage(data) {
        const conv = conversations.find(c => c.id === data.conversation_id);
        if (!conv) return;

        const msg = {
            id: 'm' + data.message.id,
            senderId: data.message.senderId,
            body: data.message.body,
            time: data.message.time,
            direction:
                data.message.senderId === authUserId ? 'outgoing' : 'incoming',
            attachments: data.message.attachments || []
        };

        if (data.parent_id) {
            conv.messages.replies.push(msg);
        } else {
            conv.messages.root = msg;
        }

        if (msg.senderId !== authUserId) {
            conv.unread = (conv.unread || 0) + 1;
        }

        if (state.activeConversationId === conv.id) {
            conv.unread = 0;
            openConversation(conv.id);
        }

        renderMessageList();
    }

    /* =====================================================
     * UI
     * ===================================================== */

    function bindEvents() {

        els.searchInput?.addEventListener('input', e => {
            state.search = e.target.value.trim();
            renderMessageList();
        });

        els.filterChips?.addEventListener('click', e => {
            const chip = e.target.closest('.chip');
            if (!chip) return;

            state.chip = chip.dataset.filter;
            [...els.filterChips.children].forEach(c =>
                c.classList.toggle('active', c === chip)
            );
            renderMessageList();
        });

        els.btnComposeSend?.addEventListener('click', sendMessage);
    }

    function renderMessageList() {
        if (!els.list) return;

        const items = conversations
            .filter(filterConversation)
            .sort((a, b) =>
                new Date(b.lastActivity || 0) - new Date(a.lastActivity || 0)
            );

        if (!items.length) {
            els.list.innerHTML =
                '<div class="text-center text-muted py-3">موردی یافت نشد</div>';
            return;
        }

        els.list.innerHTML = items.map(renderConversationItem).join('');

        els.list.querySelectorAll('.conversation-item').forEach(el => {
            el.addEventListener('click', () => {
                state.activeConversationId = el.dataset.id;
                openConversation(el.dataset.id);
            });
        });
    }

    function renderConversationItem(conv) {
        const unread = conv.unread
            ? `<span class="badge unread">${conv.unread}</span>`
            : '';

        return `
            <div class="conversation-item" data-id="${conv.id}">
                <div class="p-3">
                    <div class="fw-600 truncate">موضوع: ${conv.subject || '-'}</div>
                    <div class="text-muted truncate mt-1">
                        ${conv.messages.root?.body || ''}
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">
                            ${formatRelative(conv.messages.root?.time)}
                        </small>
                        ${unread}
                    </div>
                </div>
            </div>
        `;
    }

    function openConversation(conversationId) {
        const conv = conversations.find(c => c.id === conversationId);
        if (!conv) return;

        els.viewSubject.textContent = conv.subject || '';
        els.viewParticipants.innerHTML = conv.participants
            ?.map(id => renderAvatar(users[id]))
            .join('') || '';

        els.viewMessages.innerHTML = renderThread(conv);
        conv.unread = 0;

        bootstrap.Modal.getOrCreateInstance(els.viewModal).show();
    }

    function renderThread(conv) {
        let html = renderMessage(conv.messages.root, false);
        conv.messages.replies.forEach(r => {
            html += renderMessage(r, true);
        });
        return html;
    }

    function renderMessage(msg, isReply) {
        if (!msg) return '';

        const user = users[msg.senderId];

        return `
            <div class="message-card ${isReply ? 'ms-4 mt-2' : ''}">
                <div class="fw-600">${user?.name || ''}</div>
                <small class="text-muted">${formatDate(msg.time)}</small>
                <div class="mt-2">${msg.body}</div>
                ${renderAttachments(msg.attachments)}
            </div>
        `;
    }

    function renderAttachments(list = []) {
        if (!list.length) return '';
        return `
            <div class="mt-2">
                ${list.map(a => `
                    <a href="${a.url}" target="_blank">📎 ${a.name}</a>
                `).join('<br>')}
            </div>
        `;
    }

    /* =====================================================
     * SEND MESSAGE
     * ===================================================== */

    function sendMessage() {
        const body = els.composeBody.value.trim();
        const recipients = $(els.composeRecipients).val() || [];

        if (!body || !recipients.length) {
            showToast('متن و گیرندگان الزامی است');
            return;
        }

        const fd = new FormData(els.composeForm);

        fetch(window.CORRESPONDENCE_POST_URL, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN':
                document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: fd
        })
            .then(r => r.json())
            .then(() => {
                bootstrap.Modal.getInstance(els.composeModal)?.hide();
                resetCompose();
                showToast('پیام ارسال شد');
            })
            .catch(() => showToast('خطا در ارسال'));
    }

    function resetCompose() {
        els.composeBody.value = '';
        els.composeAttachment.value = '';
        $(els.composeRecipients).val(null).trigger('change');
    }

    /* =====================================================
     * HELPERS
     * ===================================================== */

    function filterConversation(c) {
        if (state.chip === 'unread') return c.unread > 0;
        return true;
    }

    function renderAvatar(user) {
        if (!user) return '';
        const initials = user.name.split(' ').map(p => p[0]).join('');
        return `<span class="avatar">${initials}</span>`;
    }

    function formatRelative(date) {
        if (!date) return '';
        const d = new Date(date);
        return d.toLocaleDateString('fa-IR');
    }

    function formatDate(date) {
        return new Date(date).toLocaleString('fa-IR');
    }

    function showToast(msg) {
        alert(msg);
    }

    function initComposeModal() {
        $(els.composeRecipients).select2({
            width: '100%',
            dir: 'rtl',
            dropdownParent: $('#composeModal')
        });
    }

    document.addEventListener('DOMContentLoaded', init);

})();
