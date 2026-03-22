<template>
    <div class="auth-wrapper">
        <div class="auth-card">

            <!-- Logo -->
            <div class="card-logo">
                <div class="logo-icon">LVL</div>
                <span class="logo-text">Learning Vue Laravel</span>
            </div>

            <h1 class="card-title">Create account</h1>
            <p class="card-sub">Fill in your details to get started</p>

            <form @submit.prevent="register">

                <div class="form-group">
                    <label class="form-label">Full name</label>
                    <input
                        v-model.trim="form.name"
                        type="text"
                        class="form-input"
                        placeholder="John Doe"
                    />
                </div>

                <div class="form-group">
                    <label class="form-label">Email address</label>
                    <input
                        v-model.trim="form.email"
                        type="email"
                        class="form-input"
                        placeholder="you@example.com"
                    />
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input
                            v-model="form.password"
                            type="password"
                            class="form-input"
                            placeholder="Min. 8 characters"
                        />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm password</label>
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            class="form-input"
                            placeholder="Repeat password"
                        />
                    </div>
                </div>

                <div v-if="error" class="error-msg">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ error }}
                </div>

                <div v-if="success" class="success-msg">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ success }}
                </div>

                <button type="submit" class="submit-btn" :disabled="loading">
                    <span v-if="loading" class="spinner"></span>
                    {{ loading ? 'Creating account...' : 'Create account' }}
                </button>
            </form>

            <p class="card-footer">
                Already have an account?
                <router-link to="/login" class="link">Sign in</router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import { reactive, ref } from "vue";
import api from "../services/api";

const toast = useToast();
const router = useRouter();

const form = reactive({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const loading = ref(false);
const error = ref("");
const success = ref("");

function getErrorMessage(e) {
    const data = e?.response?.data;
    if (data?.message) return data.message;
    if (data?.errors) {
        const k = Object.keys(data.errors)[0];
        return data.errors[k][0];
    }
    return "Registration failed";
}

async function register() {
    loading.value = true;
    error.value = "";
    success.value = "";

    try {
        await api.post("/register", form);
        toast.success("Registration successful! Please login.");
        form.name = "";
        form.email = "";
        form.password = "";
        form.password_confirmation = "";
        setTimeout(() => { router.push("/login"); }, 1000);
    } catch (e) {
        const msg = getErrorMessage(e);
        error.value = msg;
        toast.error(msg);
    } finally {
        loading.value = false;
    }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap');

* { box-sizing: border-box; }

.auth-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f4f8;
    padding: 24px;
    font-family: 'DM Sans', sans-serif;
}

.auth-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 40px 36px;
    width: 100%;
    max-width: 460px;
}

.card-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 28px;
}

.logo-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #185FA5;
    color: #fff;
    font-size: 12px;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    letter-spacing: 0.03em;
}

.logo-text {
    font-size: 15px;
    font-weight: 500;
    color: #1a202c;
}

.card-title {
    font-family: 'DM Serif Display', serif;
    font-size: 24px;
    font-weight: 400;
    color: #1a202c;
    margin: 0 0 6px;
}

.card-sub {
    font-size: 14px;
    color: #718096;
    margin: 0 0 28px;
}

.form-group {
    margin-bottom: 16px;
}

.form-row {
    display: flex;
    gap: 14px;
}
.form-row .form-group { flex: 1; }

.form-label {
    display: block;
    font-size: 12px;
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.form-input {
    width: 100%;
    padding: 11px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    font-family: 'DM Sans', sans-serif;
    color: #1a202c;
    background: #fff;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-input:focus {
    border-color: #378ADD;
    box-shadow: 0 0 0 3px rgba(55, 138, 221, 0.12);
}

.error-msg {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #c53030;
    background: #fff5f5;
    border: 1px solid #fed7d7;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 16px;
}

.success-msg {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #276749;
    background: #f0fff4;
    border: 1px solid #9ae6b4;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 16px;
}

.submit-btn {
    width: 100%;
    padding: 12px;
    background: #185FA5;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 500;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    margin-top: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background 0.2s, transform 0.1s;
}

.submit-btn:hover:not(:disabled) { background: #0C447C; }
.submit-btn:active:not(:disabled) { transform: scale(0.99); }
.submit-btn:disabled { opacity: 0.7; cursor: not-allowed; }

.spinner {
    width: 15px;
    height: 15px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    flex-shrink: 0;
}
@keyframes spin { to { transform: rotate(360deg); } }

.card-footer {
    margin-top: 20px;
    font-size: 13px;
    color: #718096;
    text-align: center;
}

.link {
    color: #185FA5;
    font-weight: 500;
    text-decoration: none;
}
.link:hover { text-decoration: underline; }

@media (max-width: 480px) {
    .auth-card { padding: 28px 20px; }
    .form-row { flex-direction: column; gap: 0; }
}
</style>
