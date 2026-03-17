<template>
    <div class="page">

        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div class="banner-left">
                <div class="banner-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div>
                    <h1 class="welcome-title">Welcome back, {{ currentUser?.name || 'Admin' }}</h1>
                    <p class="welcome-sub">Here's what's happening in your app today.</p>
                </div>
            </div>
            <div class="banner-date">{{ todayDate }}</div>
        </div>

        <!-- Stat Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Total Users</span>
                    <div class="stat-icon blue">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                </div>
                <div class="stat-value">{{ loadingUsers ? '—' : users.length }}</div>
                <div class="stat-footer">Registered accounts</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Newest User</span>
                    <div class="stat-icon teal">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                </div>
                <div class="stat-value stat-sm">{{ newestUser?.name || '—' }}</div>
                <div class="stat-footer">{{ newestUser ? formatDate(newestUser.created_at) : 'No users yet' }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Logged in as</span>
                    <div class="stat-icon purple">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                </div>
                <div class="stat-value stat-sm">{{ currentUser?.name || '—' }}</div>
                <div class="stat-footer">{{ currentUser?.email || '' }}</div>
            </div>
        </div>

        <!-- Two column layout -->
        <div class="content-grid">

            <!-- Recent Users -->
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Recent users</h2>
                    <router-link to="/users" class="panel-link">View all</router-link>
                </div>

                <div v-if="loadingUsers" class="panel-body">
                    <div v-for="i in 4" :key="i" class="skeleton-row">
                        <div class="skel-avatar"></div>
                        <div style="flex:1; display:flex; flex-direction:column; gap:6px;">
                            <div class="skel" style="width:120px; height:11px;"></div>
                            <div class="skel" style="width:160px; height:10px;"></div>
                        </div>
                        <div class="skel" style="width:70px; height:10px;"></div>
                    </div>
                </div>

                <div v-else-if="!users.length" class="empty-mini">No users found.</div>

                <div v-else class="panel-body">
                    <div v-for="u in recentUsers" :key="u.id" class="user-row">
                        <div class="avatar">{{ initials(u.name) }}</div>
                        <div class="user-info">
                            <span class="user-name">{{ u.name }}</span>
                            <span class="user-email">{{ u.email }}</span>
                        </div>
                        <span class="user-date">{{ formatDate(u.created_at) }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Quick actions</h2>
                </div>
                <div class="panel-body actions-list">
                    <router-link to="/users/create" class="quick-action">
                        <div class="qa-icon blue">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </div>
                        <div class="qa-text">
                            <span class="qa-title">Create user</span>
                            <span class="qa-sub">Add a new account</span>
                        </div>
                        <svg class="qa-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </router-link>

                    <router-link to="/users" class="quick-action">
                        <div class="qa-icon teal">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg>
                        </div>
                        <div class="qa-text">
                            <span class="qa-title">Manage users</span>
                            <span class="qa-sub">View, edit or delete</span>
                        </div>
                        <svg class="qa-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </router-link>

                    <router-link to="/about" class="quick-action">
                        <div class="qa-icon purple">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                        <div class="qa-text">
                            <span class="qa-title">About me</span>
                            <span class="qa-sub">View your profile info</span>
                        </div>
                        <svg class="qa-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </router-link>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import api from "../services/api";

const users = ref([]);
const loadingUsers = ref(true);
const currentUser = ref(null);

const todayDate = new Date().toLocaleDateString("en-US", {
    weekday: "long", month: "long", day: "numeric", year: "numeric"
});

function initials(name) {
    if (!name) return "?";
    return name.split(" ").map(w => w[0]).join("").toUpperCase().slice(0, 2);
}

function formatDate(value) {
    if (!value) return "-";
    return new Date(value).toLocaleDateString("en-US", {
        month: "short", day: "numeric", year: "numeric"
    });
}

const recentUsers = computed(() =>
    [...users.value].sort((a, b) => new Date(b.created_at) - new Date(a.created_at)).slice(0, 5)
);

const newestUser = computed(() => recentUsers.value[0] || null);

async function fetchUsers() {
    try {
        const res = await api.get("/users");
        users.value = res.data;
    } catch (e) {
        // silently fail on dashboard
    } finally {
        loadingUsers.value = false;
    }
}

async function fetchMe() {
    try {
        const res = await api.get("/me");
        currentUser.value = res.data;
    } catch (e) {}
}

onMounted(() => {
    fetchUsers();
    fetchMe();
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap');

* { box-sizing: border-box; }

.page {
    max-width: 960px;
    margin: 0 auto;
    padding: 36px 24px;
    font-family: 'DM Sans', sans-serif;
}

/* Welcome Banner */
.welcome-banner {
    background: #0C447C;
    border-radius: 14px;
    padding: 28px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    gap: 16px;
    flex-wrap: wrap;
    position: relative;
    overflow: hidden;
}
.welcome-banner::after {
    content: '';
    position: absolute;
    right: -40px; top: -40px;
    width: 180px; height: 180px;
    border-radius: 50%;
    border: 36px solid rgba(255,255,255,0.06);
}
.banner-left {
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
    z-index: 1;
}
.banner-icon {
    width: 48px; height: 48px;
    border-radius: 12px;
    background: rgba(255,255,255,0.12);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.welcome-title {
    font-family: 'DM Serif Display', serif;
    color: #fff;
    font-size: 22px;
    font-weight: 400;
    margin: 0 0 4px;
}
.welcome-sub {
    color: rgba(255,255,255,0.55);
    font-size: 13px;
    margin: 0;
}
.banner-date {
    color: rgba(255,255,255,0.45);
    font-size: 13px;
    position: relative;
    z-index: 1;
}

/* Stats */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 24px;
}
.stat-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px 20px;
}
.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.stat-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #a0aec0;
    font-weight: 500;
}
.stat-icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
}
.stat-icon.blue { background: #EBF4FF; color: #185FA5; }
.stat-icon.teal { background: #E6FFFA; color: #0F6E56; }
.stat-icon.purple { background: #F0EFFE; color: #534AB7; }
.stat-value {
    font-size: 28px;
    font-weight: 500;
    color: #1a202c;
    margin-bottom: 4px;
}
.stat-sm { font-size: 17px; }
.stat-footer {
    font-size: 12px;
    color: #a0aec0;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

/* Panel */
.panel {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}
.panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f4f8;
}
.panel-title {
    font-size: 14px;
    font-weight: 500;
    color: #1a202c;
    margin: 0;
}
.panel-link {
    font-size: 12px;
    color: #185FA5;
    text-decoration: none;
    font-weight: 500;
}
.panel-link:hover { text-decoration: underline; }
.panel-body { padding: 8px 0; }
.empty-mini {
    padding: 32px 20px;
    text-align: center;
    font-size: 13px;
    color: #a0aec0;
}

/* User Row */
.user-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 20px;
    transition: background 0.12s;
}
.user-row:hover { background: #f7fafc; }
.avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: #EBF4FF;
    color: #185FA5;
    font-size: 12px;
    font-weight: 500;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.user-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
}
.user-name {
    font-size: 13px;
    font-weight: 500;
    color: #1a202c;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.user-email {
    font-size: 12px;
    color: #a0aec0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.user-date {
    font-size: 11px;
    color: #cbd5e0;
    flex-shrink: 0;
}

/* Skeleton */
.skeleton-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 20px;
}
.skel-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: #edf2f7;
    flex-shrink: 0;
    animation: pulse 1.4s ease-in-out infinite;
}
.skel {
    background: #edf2f7;
    border-radius: 6px;
    animation: pulse 1.4s ease-in-out infinite;
}
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }

/* Quick Actions */
.actions-list { padding: 8px 0; }
.quick-action {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    text-decoration: none;
    transition: background 0.12s;
    cursor: pointer;
}
.quick-action:hover { background: #f7fafc; }
.qa-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.qa-icon.blue { background: #EBF4FF; color: #185FA5; }
.qa-icon.teal { background: #E6FFFA; color: #0F6E56; }
.qa-icon.purple { background: #F0EFFE; color: #534AB7; }
.qa-text {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.qa-title {
    font-size: 13px;
    font-weight: 500;
    color: #1a202c;
}
.qa-sub {
    font-size: 12px;
    color: #a0aec0;
}
.qa-arrow { color: #cbd5e0; }

@media (max-width: 680px) {
    .stats-grid { grid-template-columns: 1fr; }
    .content-grid { grid-template-columns: 1fr; }
    .welcome-banner { padding: 20px; }
}
</style>
