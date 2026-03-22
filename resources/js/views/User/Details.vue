<template>
    <div class="page">

        <div class="page-header">
            <div>
                <h1 class="page-title">User Details</h1>
                <p class="page-sub">Viewing full profile information</p>
            </div>
            <button class="btn-back" @click="router.push('/users')">
                ← Back to Users
            </button>
        </div>

        <div v-if="loading" class="loading">Loading...</div>
        <div v-if="error" class="error-banner">{{ error }}</div>

        <div v-if="!loading && user" class="detail-card">

            <!-- Avatar -->
            <div class="profile-header">
                <div v-if="user.display_image">
                    <img :src="`/storage/${user.display_image}`" class="profile-img" />
                </div>
                <div v-else class="profile-avatar">{{ initials(user.name) }}</div>
                <div>
                    <h2 class="profile-name">{{ user.name }}</h2>
                    <p class="profile-email">{{ user.email }}</p>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Details -->
            <div class="detail-row">
                <span class="detail-label">User ID</span>
                <span class="detail-value">#{{ user.id }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Full Name</span>
                <span class="detail-value">{{ user.name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email</span>
                <span class="detail-value">{{ user.email }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Joined</span>
                <span class="detail-value">{{ formatDate(user.created_at) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Last Updated</span>
                <span class="detail-value">{{ formatDate(user.updated_at) }}</span>
            </div>

            <div class="divider"></div>

            <!-- Actions -->
            <div class="detail-actions">
                <button class="btn-edit" @click="router.push(`/users/${user.id}/edit`)">
                    Edit User
                </button>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../services/api';    // ✅ two levels up

const route = useRoute();
const router = useRouter();

const user = ref(null);
const loading = ref(true);
const error = ref('');

onMounted(async () => {
    try {
        const res = await api.get(`/users/${route.params.id}`);
        user.value = res.data;
    } catch (e) {
        error.value = 'Failed to load user details.';
    } finally {
        loading.value = false;
    }
});

function initials(name) {
    if (!name) return '?';
    return name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
}

function formatDate(value) {
    if (!value) return '-';
    return new Date(value).toLocaleDateString('en-US', {
        month: 'long', day: 'numeric', year: 'numeric'
    });
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&display=swap');
* { box-sizing: border-box; }

.page {
    max-width: 560px;
    margin: 0 auto;
    padding: 36px 24px;
    font-family: 'DM Sans', sans-serif;
}
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 28px;
}
.page-title { font-size: 22px; font-weight: 500; color: #1a202c; margin: 0 0 4px; }
.page-sub { font-size: 13px; color: #718096; margin: 0; }

.btn-back {
    padding: 8px 14px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    font-family: 'DM Sans', sans-serif;
    color: #4a5568;
    cursor: pointer;
}
.btn-back:hover { background: #f7fafc; }

.detail-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 28px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 16px;
}
.profile-img {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e2e8f0;
}
.profile-avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: #EBF4FF;
    color: #185FA5;
    font-size: 20px;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.profile-name { font-size: 18px; font-weight: 500; color: #1a202c; margin: 0 0 4px; }
.profile-email { font-size: 13px; color: #718096; margin: 0; }

.divider { height: 1px; background: #f0f4f8; }

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 4px 0;
}
.detail-label {
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #a0aec0;
}
.detail-value { font-size: 14px; color: #2d3748; }

.detail-actions { display: flex; gap: 10px; }
.btn-edit {
    flex: 1;
    padding: 11px;
    background: #185FA5;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
}
.btn-edit:hover { background: #0C447C; }

.loading { color: #718096; font-size: 14px; }
.error-banner {
    padding: 12px 16px;
    background: #fff5f5;
    border: 1px solid #fed7d7;
    border-radius: 8px;
    color: #c53030;
    font-size: 13px;
}
</style>
