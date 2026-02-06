(function () {

    const { users, conversations, authUserId } = window.CORRESPONDENCE_DATA;

    let state = {
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
        viewMessages: document.getElementById('viewMessages')
    };

    // ------------------- Init -------------------
    function init() {
        setTimeout(() => {
            if (els.skeleton) els.skeleton.classList.add('d-none');
            if (els.body) els.body.classList.remove('d-none');

            state.activeConversationId = conversations[0]?.id || null;
            renderMessageList();
        }, 300);

        initCompose();
        initRealtime();
    }

    // ------------------- UI -------------------
    function renderMessageList() {
        if (!els.list) return;

        const list = conversations.filter(conv => {
            if (state.chip === 'unread') return conv.unread > 0;
            return true;
        }).filter(conv => {
            if (!state.search) return true;
            const q = state.search.toLowerCase();
            return conv.subject.toLowerCase().includes(q);
        });

        if (!list.length) {
            els.list.innerHTML = '<div class="text-center py-3 text-muted">موردی یافت نشد.</div>';
            return;
        }

        els.list.innerHTML = list.map(conv => {
            const root = conv.messages.root;
            return `
            <div class="conversation-item ${state.activeConversationId === conv.id ? 'active' : ''}" data-id="${conv.id}">
                <div>
                    <div class="fw-bold">${conv.subject}</div>
                    <div class="text-muted">${root?.body || ''}</div>
                </div>
            </div>`;
        }).join('');

        Array.from(els.list.querySelectorAll('.conversation-item')).forEach(item => {
            item.addEventListener('click', () => {
                state.activeConversationId = item.dataset.id;
                openConversationModal(item.dataset.id);
            });
        });
    }

    function openConversationModal(convId) {
        const conv = conversations.find(c => c.id == convId);
        if (!conv) return;

        state.activeConversationId = conv.id;

        if (els.viewSubject) els.viewSubject.textContent = conv.subject;
        if (els.viewParticipants)
            els.viewParticipants.innerHTML = conv.participants.map(id => renderAvatar(users[id])).join('');

        if (els.viewMessages)
            els.viewMessages.innerHTML = renderThread(conv);

        bootstrap.Modal.getOrCreateInstance(els.viewModal).show();
    }

    function renderThread(conv) {
        let html = '';
        if (conv.messages.root) html += renderMessage(conv.messages.root, false);
        conv.messages.replies?.forEach(r => html += renderMessage(r, true));
        return html;
    }

    function renderMessage(msg, isReply) {
        const sender = users[msg.senderId];
        const attachments = (msg.attachments || []).map(a =>
            `<a href="${a.url}" target="_blank" class="d-block">📎 ${a.name}</a>`).join('');

        return `
        <div class="message-card ${isReply ? 'ms-4 border-start ps-3 mt-2' : ''}">
            <div class="fw-bold">${sender?.name || 'نامشخص'}</div>
            <div class="text-muted">${new Date(msg.time).toLocaleString('fa-IR')}</div>
            <div>${msg.body}</div>
            <div>${attachments}</div>
        </div>`;
    }

    function renderAvatar(user) {
        if (!user) return '';
        const initials = user.name.split(' ').map(p => p[0]).join('').toUpperCase();
        return `<span class="avatar">${initials}</span>`;
    }

    // ------------------- Compose -------------------
    function initCompose() {
        if (els.composeRecipients) {
            $(els.composeRecipients).select2({
                width: '100%',
                dir: 'rtl',
                placeholder: 'گیرندگان را انتخاب کنید',
                dropdownParent: $('#composeModal')
            });
        }

        els.btnComposeSend?.addEventListener('click', handleComposeSend);
    }

    function handleComposeSend() {
        const formData = new FormData();
        formData.append('subject', els.composeSubject.value || '');
        formData.append('body', els.composeBody.value || '');

        const recipients = $(els.composeRecipients).val() || [];
        if (!recipients.length || !els.composeBody.value.trim()) {
            alert('گیرندگان و متن پیام الزامی است.');
            return;
        }
        recipients.forEach(r => formData.append('recipients[]', r));

        Array.from(els.composeAttachment.files || []).forEach(f => formData.append('attachments[]', f));

        fetch(window.CORRESPONDENCE_POST_URL, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        }).then(r => r.json())
            .then(data => {
                resetComposeForm();
                bootstrap.Modal.getOrCreateInstance(els.composeModal).hide();
            });
    }

    function resetComposeForm() {
        els.composeSubject.value = '';
        els.composeBody.value = '';
        els.composeAttachment.value = '';
        $(els.composeRecipients).val(null).trigger('change');
    }

    // ------------------- Realtime -------------------
    function initRealtime() {
        const pusher = new Pusher(window.PUSHER_CONFIG.key, {
            cluster: window.PUSHER_CONFIG.cluster,
            authEndpoint: '/broadcasting/auth',
            auth: { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } },
            forceTLS: true
        });

        conversations.forEach(conv => {
            const channel = pusher.subscribe(`private-conversation.${conv.id}`);
            channel.bind('message.sent', (data) => {
                const convIndex = conversations.findIndex(c => c.id == data.conversation_id);
                if (convIndex === -1) return;

                const convObj = conversations[convIndex];
                if (data.parent_id) convObj.messages.replies.push(data.message);
                else convObj.messages.root = data.message;

                if (state.activeConversationId == convObj.id) openConversationModal(convObj.id);
                renderMessageList();
            });
        });
    }

    // ------------------- Events -------------------
    els.searchInput?.addEventListener('input', e => {
        state.search = e.target.value;
        renderMessageList();
    });

    init();

})();
