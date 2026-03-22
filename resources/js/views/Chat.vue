<template>
    <div class="chat-wrapper">

        <!-- Header -->
        <div class="chat-header">
            <div class="header-info">
                <div class="avatar">💬</div>
                <div>
                    <h2>Group Chat</h2>
                    <span class="status">● Live</span>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div class="chat-body" ref="chatBody">
            <div v-if="messages.length === 0" class="empty-state">
                No messages yet. Say hello! 👋
            </div>

            <div
                v-for="msg in messages"
                :key="msg.id"
                class="message-row"
                :class="msg.user_id === currentUserId ? 'me' : 'them'"
            >
                <div class="bubble-wrapper">
                    <span class="msg-name">{{ msg.user_name }}</span>
                    <div class="bubble">{{ msg.message }}</div>
                    <span class="msg-time">{{ msg.created_at }}</span>
                </div>
            </div>
        </div>

        <!-- Input -->
        <div class="chat-footer">
            <input
                v-model="newMessage"
                type="text"
                placeholder="Type a message..."
                class="msg-input"
                @keyup.enter="sendMessage"
            />
            <button
                class="send-btn"
                @click="sendMessage"
                :disabled="!newMessage.trim()"
            >
                Send
            </button>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue';
import api from '../services/api';  // ✅ correct path for your project

const messages = ref([]);
const newMessage = ref('');
const chatBody = ref(null);
const currentUserId = ref(null);

// scroll to bottom when new message arrives
const scrollToBottom = async () => {
    await nextTick();
    if (chatBody.value) {
        chatBody.value.scrollTop = chatBody.value.scrollHeight;
    }
};

// runs when chat page loads
onMounted(async () => {
    // get current logged in user
    const user = JSON.parse(localStorage.getItem('user'));
    currentUserId.value = user?.id;

    // load previous messages from DB
    const res = await api.get('/chat');
    messages.value = res.data;
    scrollToBottom();

    // 👂 listen for new messages via WebSocket
    window.Echo.channel('chat')
        .listen('MessageSent', (data) => {
            messages.value.push(data);
            scrollToBottom();
        });
});

// stop listening when user leaves the page
onUnmounted(() => {
    window.Echo.leaveChannel('chat');
});

// send a message
const sendMessage = async () => {
    if (!newMessage.value.trim()) return;

    const text = newMessage.value;
    newMessage.value = ''; // clear input immediately

    const res = await api.post('/chat', { message: text });

    // add own message to screen immediately
    messages.value.push(res.data);
    scrollToBottom();
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&display=swap');

* { box-sizing: border-box; margin: 0; padding: 0; }

.chat-wrapper {
    display: flex;
    flex-direction: column;
    height: 100vh;
    max-width: 700px;
    margin: 0 auto;
    font-family: 'DM Sans', sans-serif;
    background: #f0f4f8;
}

.chat-header {
    background: #fff;
    border-bottom: 1px solid #e2e8f0;
    padding: 16px 20px;
    flex-shrink: 0;
}

.header-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #185FA5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.header-info h2 {
    font-size: 16px;
    font-weight: 500;
    color: #1a202c;
    margin: 0;
}

.status {
    font-size: 12px;
    color: #38a169;
}

.chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.empty-state {
    text-align: center;
    color: #a0aec0;
    margin-top: 40px;
    font-size: 14px;
}

.message-row {
    display: flex;
}

/* other user — align left */
.message-row.them {
    justify-content: flex-start;
}

/* current user — align right */
.message-row.me {
    justify-content: flex-end;
}

.bubble-wrapper {
    display: flex;
    flex-direction: column;
    max-width: 65%;
    gap: 3px;
}

.message-row.me .bubble-wrapper {
    align-items: flex-end;
}

.msg-name {
    font-size: 11px;
    color: #718096;
    padding: 0 4px;
}

.bubble {
    padding: 10px 14px;
    border-radius: 16px;
    font-size: 14px;
    line-height: 1.5;
    word-break: break-word;
}

/* other user bubble — white */
.them .bubble {
    background: #fff;
    color: #1a202c;
    border-bottom-left-radius: 4px;
    border: 1px solid #e2e8f0;
}

/* current user bubble — blue */
.me .bubble {
    background: #185FA5;
    color: #fff;
    border-bottom-right-radius: 4px;
}

.msg-time {
    font-size: 10px;
    color: #a0aec0;
    padding: 0 4px;
}

.chat-footer {
    background: #fff;
    border-top: 1px solid #e2e8f0;
    padding: 16px 20px;
    display: flex;
    gap: 10px;
    flex-shrink: 0;
}

.msg-input {
    flex: 1;
    padding: 11px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 24px;
    font-size: 14px;
    font-family: 'DM Sans', sans-serif;
    outline: none;
    transition: border-color 0.2s;
}

.msg-input:focus {
    border-color: #185FA5;
}

.send-btn {
    padding: 11px 22px;
    background: #185FA5;
    color: #fff;
    border: none;
    border-radius: 24px;
    font-size: 14px;
    font-weight: 500;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    transition: background 0.2s;
}

.send-btn:hover:not(:disabled) { background: #0C447C; }
.send-btn:disabled { opacity: 0.5; cursor: not-allowed; }
</style>
