<template>
    <div class="page">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Users</h1>
                <p class="page-sub">Manage all registered accounts</p>
            </div>
            <div class="header-actions">
                <button class="btn-refresh" @click="fetchUsers" :disabled="loading">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                    {{ loading ? 'Refreshing...' : 'Refresh' }}
                </button>
                <button class="btn-create" @click="goCreate">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Create User
                </button>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="stats-row" v-if="!loading && users.length">
            <div class="stat-card">
                <span class="stat-label">Total Users</span>
                <span class="stat-value">{{ users.length }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Latest Signup</span>
                <span class="stat-value stat-sm">{{ latestSignup }}</span>
            </div>
        </div>

        <!-- Error -->
        <div v-if="error" class="error-banner">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ error }}
        </div>

        <!-- Loading Skeleton -->
        <div v-if="loading" class="table-card">
            <div v-for="i in 4" :key="i" class="skeleton-row">
                <div class="skel skel-sm"></div>
                <div class="skel skel-md"></div>
                <div class="skel skel-lg"></div>
                <div class="skel skel-md"></div>
                <div class="skel skel-sm"></div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else-if="!loading && users.length === 0 && !error" class="empty-state">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <p>No users found</p>
            <button class="btn-create" @click="goCreate">Create the first user</button>
        </div>

        <!-- Table -->
        <div v-else-if="!loading && users.length" class="table-card">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:56px">ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created</th>
                        <th style="width:160px; text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="u in users" :key="u.id" class="table-row">
                        <td class="id-cell">#{{ u.id }}</td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ initials(u.name) }}</div>
                                <span class="user-name">{{ u.name }}</span>
                            </div>
                        </td>
                        <td class="email-cell">{{ u.email }}</td>
                        <td class="date-cell">{{ formatDate(u.created_at) }}</td>
                        <td>
                            <div class="action-btns">
                                <button class="action-btn edit-btn" @click="editUser(u.id)" title="Edit">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </button>
                                <button class="action-btn details-btn" @click="viewUserDetails(u.id)" title="Details">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    Details
                                </button>
                                <button class="action-btn delete-btn" @click="confirmDelete(u)" title="Delete">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Delete Confirm Modal -->
        <div v-if="deleteTarget" class="modal-backdrop" @click.self="deleteTarget = null">
            <div class="modal">
                <div class="modal-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                </div>
                <h3 class="modal-title">Delete user?</h3>
                <p class="modal-sub">You are about to delete <strong>{{ deleteTarget.name }}</strong>. This action cannot be undone.</p>
                <div class="modal-actions">
                    <button class="modal-cancel" @click="deleteTarget = null">Cancel</button>
                    <button class="modal-confirm" @click="deleteUser" :disabled="deleting">
                        {{ deleting ? 'Deleting...' : 'Yes, delete' }}
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import api from "../services/api";
import { useRouter } from "vue-router";
import {toastState} from "../stores/toast";

const router = useRouter();
const users = ref([]);
const loading = ref(true);
const error = ref("");
const deleteTarget = ref(null);
const deleting = ref(false);

const emit = defineEmits(["go-create"]);

function goCreate() {
    router.push("/users/create");
}

function editUser(id) {
    router.push(`/users/${id}/edit`);
}

function viewUserDetails(id) {
    router.push(`/users/${id}/details`);
}

function confirmDelete(user) {
    deleteTarget.value = user;
}

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

const latestSignup = computed(() => {
    if (!users.value.length) return "-";
    const sorted = [...users.value].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    return formatDate(sorted[0].created_at);
});

async function fetchUsers() {
    loading.value = true;
    error.value = "";
    try {
        const res = await api.get("/users");
        users.value = res.data;
    } catch (e) {
        const status = e?.response?.status;
        error.value = status === 401 ? "Unauthorized. Please login again." : "Failed to load users.";
    } finally {
        loading.value = false;
    }
}

async function deleteUser() {
    try {
        await api.delete(`/users/${deleteTarget.value.id}`);
        toastState.success('User deleted successfully!');  // ✅
        deleteTarget.value = null;
        fetchUsers();
    } catch (e) {
        toastState.error('Failed to delete user.');        // ✅
    }
}
onMounted(fetchUsers);
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&display=swap');

* { box-sizing: border-box; }

.page {
    max-width: 900px;
    margin: 0 auto;
    padding: 36px 24px;
    font-family: 'DM Sans', sans-serif;
}

/* Header */
.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 24px;
    gap: 16px;
    flex-wrap: wrap;
}
.page-title {
    font-size: 22px;
    font-weight: 500;
    color: #1a202c;
    margin: 0 0 4px;
}
.page-sub {
    font-size: 13px;
    color: #718096;
    margin: 0;
}
.header-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

/* Buttons */
.btn-refresh {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    font-family: 'DM Sans', sans-serif;
    color: #4a5568;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
}
.btn-refresh:hover:not(:disabled) { background: #f7fafc; border-color: #cbd5e0; }
.btn-refresh:disabled { opacity: 0.6; cursor: not-allowed; }

.btn-create {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: #185FA5;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    transition: background 0.15s;
}
.btn-create:hover { background: #0C447C; }

/* Stats */
.stats-row {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
}
.stat-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px 20px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 140px;
}
.stat-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #a0aec0;
    font-weight: 500;
}
.stat-value {
    font-size: 22px;
    font-weight: 500;
    color: #1a202c;
}
.stat-sm { font-size: 15px; }

/* Error */
.error-banner {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: #fff5f5;
    border: 1px solid #fed7d7;
    border-radius: 8px;
    color: #c53030;
    font-size: 13px;
    margin-bottom: 16px;
}

/* Table Card */
.table-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}

/* Skeleton */
.skeleton-row {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    border-bottom: 1px solid #f7fafc;
}
.skel {
    height: 12px;
    background: #edf2f7;
    border-radius: 6px;
    animation: pulse 1.4s ease-in-out infinite;
}
.skel-sm { width: 40px; }
.skel-md { width: 100px; }
.skel-lg { width: 160px; }
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

/* Empty State */
.empty-state {
    text-align: center;
    padding: 64px 24px;
    color: #a0aec0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}
.empty-state p { font-size: 14px; margin: 0; }

/* Table */
.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
.table thead tr {
    background: #f7fafc;
    border-bottom: 1px solid #e2e8f0;
}
.table th {
    padding: 12px 20px;
    text-align: left;
    font-size: 11px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #718096;
}
.table-row {
    border-bottom: 1px solid #f0f4f8;
    transition: background 0.12s;
}
.table-row:last-child { border-bottom: none; }
.table-row:hover { background: #f7fafc; }
.table td { padding: 14px 20px; color: #2d3748; vertical-align: middle; }

.id-cell { color: #a0aec0; font-size: 13px; font-weight: 500; }

.user-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}
.avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #EBF4FF;
    color: #185FA5;
    font-size: 12px;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.user-name { font-weight: 500; color: #1a202c; }
.email-cell { color: #718096; }
.date-cell { color: #a0aec0; font-size: 13px; }

/* Action Buttons */
.action-btns {
    display: flex;
    gap: 6px;
    justify-content: flex-end;
    align-items: center;
}
.action-btn {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 6px 11px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all 0.15s;
}
.edit-btn {
    background: #EBF4FF;
    color: #185FA5;
    border-color: #bee3f8;
}
.edit-btn:hover { background: #BEE3F8; }

.details-btn {
    background: #E6FFFA;
    color: #0F6E56;
    border-color: #9FDFD0;
}
.details-btn:hover { background: #B2F5EA; }

.delete-btn {
    background: #fff;
    color: #e53e3e;
    border-color: #fed7d7;
    padding: 6px 8px;
}
.delete-btn:hover { background: #fff5f5; border-color: #fc8181; }

/* Modal */
.modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
}
.modal {
    background: #fff;
    border-radius: 14px;
    padding: 32px 28px;
    max-width: 360px;
    width: 90%;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}
.modal-icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: #fff5f5;
    color: #e53e3e;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
}
.modal-title {
    font-size: 17px;
    font-weight: 500;
    color: #1a202c;
    margin: 0 0 8px;
}
.modal-sub {
    font-size: 13px;
    color: #718096;
    line-height: 1.6;
    margin: 0 0 24px;
}
.modal-actions {
    display: flex;
    gap: 10px;
}
.modal-cancel {
    flex: 1;
    padding: 10px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    font-family: 'DM Sans', sans-serif;
    color: #4a5568;
    cursor: pointer;
}
.modal-cancel:hover { background: #f7fafc; }
.modal-confirm {
    flex: 1;
    padding: 10px;
    background: #e53e3e;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
}
.modal-confirm:hover:not(:disabled) { background: #c53030; }
.modal-confirm:disabled { opacity: 0.7; cursor: not-allowed; }

@media (max-width: 600px) {
    .stats-row { flex-direction: column; }
    .table th:nth-child(1), .table td:nth-child(1),
    .table th:nth-child(4), .table td:nth-child(4) { display: none; }
}
</style>
