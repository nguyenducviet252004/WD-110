@extends('Layout.Layout')

@section('content_admin')
<div class="container">
    <h2 style="margin-bottom:14px;">Trò chuyện</h2>
    <style>
        .chat-layout { display:flex; gap:16px; }
        .chat-sidebar { width:320px; }
        .chat-card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; box-shadow:0 14px 32px rgba(2,6,23,0.08); overflow:hidden; backdrop-filter:saturate(180%) blur(4px); }
        .chat-card-header { padding:14px 16px; border-bottom:1px solid #eef2f7; font-weight:700; display:flex; align-items:center; gap:10px; }
        .chat-card-header .title { display:flex; align-items:center; gap:10px; }
        .avatar { width:36px; height:36px; border-radius:999px; display:flex; align-items:center; justify-content:center; font-weight:700; color:#2563eb; background:#eff6ff; border:1px solid #dbeafe; box-shadow:inset 0 0 0 2px #eff6ff; }
        .chat-search { width:100%; border:1px solid #e5e7eb; border-radius:8px; padding:8px 10px; }
        .conv-item-el { padding:12px 14px; border-bottom:1px solid #f1f5f9; cursor:pointer; display:flex; align-items:center; justify-content:space-between; transition:background .15s ease, transform .1s ease; }
        .conv-item-el:hover { background:#f8fafc; transform:translateY(-1px); }
        .conv-item-el.active { background:#eef2ff; }
        .conv-item-el .meta { display:flex; flex-direction:column; }
        .conv-item-el .name { font-weight:600; }
        .conv-item-el .preview { font-size:12px; color:#64748b; }
        .chat-pane { flex:1; }
        .chat-header { padding:14px 18px; border-bottom:1px solid #eef2f7; display:flex; align-items:center; justify-content:space-between; background:linear-gradient(90deg,#f8fafc 0%, #ffffff 100%); }
        .chat-body { height:560px; overflow:auto; padding:20px 18px; background:radial-gradient(1200px 600px at 80% -200px, #e0f2fe 0%, rgba(224,242,254,0) 60%), linear-gradient(180deg,#ffffff 0%, #fbfcfe 100%); }
        .bubble { max-width:72%; padding:12px 14px; border-radius:16px; margin:6px 0; display:inline-block; position:relative; word-break:break-word; box-shadow:0 6px 16px rgba(2,6,23,0.05); }
        .bubble-user { background:#f1f5f9; color:#0f172a; border:1px solid #e2e8f0; }
        .bubble-admin { background:linear-gradient(135deg,#2563eb 0%, #1d4ed8 100%); color:#fff; }
        .msg { display:flex; }
        .msg.user { justify-content:flex-start; }
        .msg.admin { justify-content:flex-end; }
        .time { font-size:11px; color:#94a3b8; margin-top:2px; padding:0 6px; }
        .chat-input-wrap { display:flex; gap:10px; padding:12px; border-top:1px solid #eef2f7; background:#ffffff; }
        .chat-input { flex:1; border:1px solid #e5e7eb; border-radius:12px; padding:12px 14px; outline: none; transition:border .15s ease, box-shadow .15s ease; }
        .chat-input:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,.15); }
        .send-btn { background:linear-gradient(135deg,#2563eb 0%, #1d4ed8 100%); color:#fff; border:none; padding:12px 16px; border-radius:12px; box-shadow:0 8px 18px rgba(37,99,235,.25); }
        .send-btn:hover { filter:brightness(1.05); }
        .badge-dot { background:#ef4444; color:#fff; border-radius:999px; font-size:12px; padding:2px 8px; min-width:24px; text-align:center; }
        .date-sep { text-align:center; color:#64748b; font-size:12px; margin:10px 0; position:relative; }
        .date-sep::before, .date-sep::after { content:''; position:absolute; top:50%; width:38%; height:1px; background:#e5e7eb; }
        .date-sep::before { left:0; }
        .date-sep::after { right:0; }
    </style>
    <div class="chat-layout">
        <div class="chat-sidebar">
            <div class="chat-card">
                <div class="chat-card-header">Cuộc trò chuyện gần đây</div>
                <div style="padding:10px 12px; border-bottom:1px solid #f1f5f9;">
                    <input type="text" id="conv-search" class="chat-search" placeholder="Tìm theo tên người dùng...">
                </div>
                <ul id="conv-list" class="list-group" style="border:none; max-height:540px; overflow:auto;"></ul>
            </div>
        </div>
        <div class="chat-pane">
            <div class="chat-card">
                <div class="chat-header">
                    <div class="title">
                        <div class="avatar" id="chat-avatar">U</div>
                        <div id="chat-title" style="font-weight:700;">Chưa chọn hội thoại</div>
                    </div>
                    <div id="empty-state" class="text-muted">Chưa có tin nhắn mới.</div>
                </div>
                <div id="chat-area" style="display:none;">
                    <div id="chat-box" class="chat-body"></div>
                    <div class="chat-input-wrap">
                        <input id="chat-input" type="text" class="chat-input" placeholder="Nhập tin nhắn... (Enter để gửi)">
                        <button id="chat-send" class="send-btn">Gửi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const token = '{{ $token }}';
    let currentUserId = null;

    // Use same-origin to avoid CORS issues in admin
    const API_BASE = '/api';

    function fetchMessages() {
        if (!currentUserId) return;
        fetch(`${API_BASE}/admin/chat/${currentUserId}/messages`, {
            headers: { 'Authorization': `Bearer ${token}` }
        })
        .then(r => r.json())
        .then(data => renderMessages(data.messages || []));
    }

    function fetchConversations() {
        fetch(`${API_BASE}/admin/chat/conversations/unread`, {
            headers: { 'Authorization': `Bearer ${token}` }
        })
        .then(r => r.json())
        .then(data => renderConversations(data.conversations || []));
    }

    function renderConversations(conversations) {
        const ul = document.getElementById('conv-list');
        ul.innerHTML = '';
        if (!conversations.length) {
            // Không ẩn khung chat nếu đang mở một cuộc trò chuyện
            document.getElementById('empty-state').style.display = 'block';
            if (!currentUserId) {
                document.getElementById('chat-area').style.display = 'none';
            }
            return;
        }
        document.getElementById('empty-state').style.display = 'none';
        const q = (document.getElementById('conv-search').value || '').toLowerCase();
        conversations
            .filter(c => c.user_name.toLowerCase().includes(q))
            .forEach(c => {
                const li = document.createElement('li');
                li.className = 'conv-item conv-item-el';
                li.setAttribute('data-user-id', c.user_id);
                li.innerHTML = `
                    <div class="meta">
                        <div class="name">${escapeHtml(c.user_name)}</div>
                        <div class="preview">${escapeHtml(c.last_message || '')}</div>
                    </div>
                    <span class="badge-dot">${c.unread}</span>
                `;
                ul.appendChild(li);
            });
    }

    function renderMessages(messages) {
        const box = document.getElementById('chat-box');
        box.innerHTML = '';
        messages.forEach(m => {
            const row = document.createElement('div');
            row.className = `msg ${m.sender_type}`;
            const bubble = document.createElement('div');
            bubble.className = `bubble ${m.sender_type === 'admin' ? 'bubble-admin' : 'bubble-user'}`;
            bubble.textContent = m.content;
            row.appendChild(bubble);
            box.appendChild(row);
            const time = document.createElement('div');
            time.className = 'time';
            time.textContent = new Date(m.created_at).toLocaleString();
            box.appendChild(time);
        });
        box.scrollTop = box.scrollHeight;
    }

    function sendMessage() {
        const input = document.getElementById('chat-input');
        const content = input.value.trim();
        if (!currentUserId || !content) return;
        fetch(`${API_BASE}/admin/chat/${currentUserId}/messages`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ content })
        }).then(() => {
            input.value = '';
            fetchMessages();
        });
    }

    function escapeHtml(text) {
        var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    document.getElementById('chat-send').addEventListener('click', sendMessage);
    document.getElementById('chat-input').addEventListener('keydown', function(e){ if (e.key === 'Enter') { e.preventDefault(); sendMessage(); }});
    document.getElementById('conv-search').addEventListener('input', fetchConversations);
    document.getElementById('conv-list').addEventListener('click', function(e){
        const li = e.target.closest('.conv-item');
        if (!li) return;
        currentUserId = li.getAttribute('data-user-id');
        const name = li.querySelector('.name')?.textContent || 'Chat';
        document.getElementById('chat-title').textContent = name;
        const initials = name.split(' ').map(s=>s[0]).join('').slice(0,2).toUpperCase();
        document.getElementById('chat-avatar').textContent = initials || 'U';
        document.getElementById('chat-area').style.display = 'block';
        // Tải tin nhắn trước, rồi mới đánh dấu đã đọc để tránh biến mất khỏi danh sách quá sớm
        fetchMessages();
        fetch(`${API_BASE}/admin/chat/${currentUserId}/mark-read`, { method: 'POST', headers: { 'Authorization': `Bearer ${token}` }})
            .then(() => fetchConversations());
    });

    setInterval(() => {
        fetchConversations();
        fetchMessages();
    }, 2000);

    // init
    fetchConversations();
</script>
@endsection


