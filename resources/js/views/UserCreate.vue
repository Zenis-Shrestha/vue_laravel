<template>
    <div class="page">

        <div class="page-header">
            <div>
                <h1 class="page-title">Create User</h1>
                <p class="page-sub">Add a new user to the system</p>
            </div>
            <AppButton variant="secondary" @click="router.push('/users')">
                Back
            </AppButton>
        </div>

        <div class="form-card">

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input v-model="form.name" type="text" class="form-input" placeholder="John Doe" />
                <span v-if="errors?.name" class="field-error">{{ errors.name[0] }}</span>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input v-model="form.email" type="email" class="form-input" placeholder="you@example.com" />
                <span v-if="errors?.email" class="field-error">{{ errors.email[0] }}</span>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input v-model="form.password" type="password" class="form-input" placeholder="••••••••" />
                <span v-if="errors?.password" class="field-error">{{ errors.password[0] }}</span>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input v-model="form.password_confirmation" type="password" class="form-input" placeholder="••••••••" />
            </div>

            <div class="form-group">
                <label class="form-label">Profile Image <span class="optional">(optional)</span></label>
                <input type="file" accept="image/*" @change="onFileChange" class="form-input" />
            </div>

            <!-- Image Preview -->
            <div v-if="previewUrl" class="form-group">
                <img :src="previewUrl"
                    style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0" />
            </div>

            <div class="form-actions">
                <AppButton variant="secondary" @click="router.push('/users')">
                    Cancel
                </AppButton>
                <AppButton variant="primary" @click="submitForm">
                    Save User
                </AppButton>
            </div>

            <!-- Success -->
            <div v-if="successMessage" class="success-banner">
                ✅ {{ successMessage }}
            </div>

            <!-- Errors -->
            <div v-if="errors?.general" class="error-banner">
                {{ errors.general[0] }}
            </div>

        </div>
    </div>
</template>

<script setup>
import api from "../services/api";
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import AppButton from '../components/UI/AppButton.vue';

const router = useRouter();
const successMessage = ref("");
const errors = ref(null);

const form = reactive({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const selectedImage = ref(null);
const previewUrl = ref("");

const onFileChange = (e) => {
    const file = e.target.files?.[0] || null;
    selectedImage.value = file;
    if (file) {
        previewUrl.value = URL.createObjectURL(file);
    } else {
        previewUrl.value = "";
    }
};

const resetForm = () => {
    form.name = "";
    form.email = "";
    form.password = "";
    form.password_confirmation = "";
    selectedImage.value = null;
    previewUrl.value = "";
};

const submitForm = async () => {
    errors.value = null;
    successMessage.value = "";

    try {
        const fd = new FormData();
        fd.append("name", form.name);
        fd.append("email", form.email);
        fd.append("password", form.password);
        fd.append("password_confirmation", form.password_confirmation);

        if (selectedImage.value) {
            fd.append("display_image", selectedImage.value);
        }

        await api.post("/users", fd, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        successMessage.value = "User created successfully!";
        resetForm();
        setTimeout(() => router.push("/users"), 1000);

    } catch (e) {
        if (e.response?.status === 422) {
            errors.value = e.response.data.errors;
        } else {
            errors.value = { general: ["Failed to create user. Please try again."] };
        }
    }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&display=swap');

* {
    box-sizing: border-box;
}

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

.btn-back:hover {
    background: #f7fafc;
}

.form-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 28px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-label {
    font-size: 12px;
    font-weight: 500;
    color: #4a5568;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.optional {
    font-weight: 400;
    color: #a0aec0;
    text-transform: none;
}

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

.form-input:focus {
    border-color: #185FA5;
    box-shadow: 0 0 0 3px rgba(24, 95, 165, 0.1);
}

.field-error {
    font-size: 12px;
    color: #e53e3e;
}

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

.btn-cancel:hover {
    background: #f7fafc;
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

.btn-save:hover {
    background: #0C447C;
}

.success-banner {
    padding: 12px 16px;
    background: #f0fff4;
    border: 1px solid #9ae6b4;
    border-radius: 8px;
    color: #276749;
    font-size: 13px;
}

.error-banner {
    padding: 12px 16px;
    background: #fff5f5;
    border: 1px solid #fed7d7;
    border-radius: 8px;
    color: #c53030;
    font-size: 13px;
}
</style>
