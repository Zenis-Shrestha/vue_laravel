<template>
    <div class="page">

        <div class="page-header">
            <div>
                <h1 class="page-title">Edit User</h1>
                <p class="page-sub">Update user information</p>
            </div>
            <button class="btn-back" @click="router.push('/users')">
                ← Back to Users
            </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="loading">Loading user...</div>

        <!-- Error -->
        <div v-if="error" class="error-banner">{{ error }}</div>

        <!-- Form -->
        <div v-if="!loading" class="form-card">

            <!-- Current Image -->
            <div v-if="currentImage" class="form-group">
                <label class="form-label">Current Image</label>
                <img :src="`/storage/${currentImage}`"
                    style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0" />
            </div>

            <div class="form-group">
                <label class="form-label">Name</label>
                <input v-model="form.name" type="text" class="form-input" placeholder="Full name" />
                <span v-if="errors.name" class="field-error">{{ errors.name[0] }}</span>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input v-model="form.email" type="email" class="form-input" placeholder="email@example.com" />
                <span v-if="errors.email" class="field-error">{{ errors.email[0] }}</span>
            </div>

            <div class="form-group">
                <label class="form-label">Profile Image <span class="optional">(optional)</span></label>
                <input type="file" accept="image/*" @change="onFileChange" class="form-input" />
            </div>

            <div v-if="previewUrl" class="form-group">
                <img :src="previewUrl"
                    style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0" />
            </div>

            <div class="form-actions">
                <button class="btn-cancel" @click="router.push('/users')">Cancel</button>
                <button class="btn-save" @click="submitForm" :disabled="saving">
                    {{ saving ? 'Saving...' : 'Save Changes' }}
                </button>
            </div>

            <div v-if="successMessage" class="success-banner">
                ✅ {{ successMessage }}
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../services/api';    // ✅ two levels up from views/User/

const route = useRoute();
const router = useRouter();

const loading = ref(true);
const saving = ref(false);
const error = ref('');
const successMessage = ref('');
const currentImage = ref('');
const previewUrl = ref('');
const selectedImage = ref(null);
const errors = reactive({});

const form = reactive({
    name: '',
    email: '',
});

// load user data when page opens
onMounted(async () => {
    try {
        const res = await api.get(`/users/${route.params.id}`);
        form.name = res.data.name;
        form.email = res.data.email;
        currentImage.value = res.data.display_image;
    } catch (e) {
        error.value = 'Failed to load user.';
    } finally {
        loading.value = false;
    }
});

const onFileChange = (e) => {
    const file = e.target.files?.[0] || null;
    selectedImage.value = file;
    if (file) {
        previewUrl.value = URL.createObjectURL(file);
    }
};

const submitForm = async () => {
    saving.value = true;
    successMessage.value = '';
    Object.keys(errors).forEach(k => delete errors[k]);

    try {
        const fd = new FormData();
        fd.append('name', form.name);
        fd.append('email', form.email);
        fd.append('_method', 'PUT');        // ✅ Laravel needs this for FormData + PUT

        if (selectedImage.value) {
            fd.append('display_image', selectedImage.value);
        }

        await api.post(`/users/${route.params.id}`, fd, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });

        successMessage.value = 'User updated successfully!';
        setTimeout(() => router.push('/users'), 1000);

    } catch (e) {
        if (e.response?.status === 422) {
            Object.assign(errors, e.response.data.errors);
        } else {
            error.value = 'Failed to update user.';
        }
    } finally {
        saving.value = false;
    }
};
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

.form-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 28px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-label {
    font-size: 12px;
    font-weight: 500;
    color: #4a5568;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.optional { font-weight: 400; color: #a0aec0; text-transform: none; }
.form-input {
    padding: 11px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    font-family: 'DM Sans', sans-serif;
    color: #1a202c;
    outline: none;
    transition: border-color 0.2s;
}
.form-input:focus { border-color: #185FA5; box-shadow: 0 0 0 3px rgba(24,95,165,0.1); }
.field-error { font-size: 12px; color: #e53e3e; }

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 8px;
}
.btn-cancel {
    flex: 1;
    padding: 11px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    font-family: 'DM Sans', sans-serif;
    color: #4a5568;
    cursor: pointer;
}
.btn-save {
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
.btn-save:hover:not(:disabled) { background: #0C447C; }
.btn-save:disabled { opacity: 0.7; cursor: not-allowed; }

.loading { color: #718096; font-size: 14px; }
.error-banner {
    padding: 12px 16px;
    background: #fff5f5;
    border: 1px solid #fed7d7;
    border-radius: 8px;
    color: #c53030;
    font-size: 13px;
    margin-bottom: 16px;
}
.success-banner {
    padding: 12px 16px;
    background: #f0fff4;
    border: 1px solid #9ae6b4;
    border-radius: 8px;
    color: #276749;
    font-size: 13px;
}
</style>
