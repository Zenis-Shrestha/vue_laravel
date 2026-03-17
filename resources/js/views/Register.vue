<template>
    <div class="auth-wrapper">
        <div class="auth-sidebar">
            <div class="geo-circle geo-1"></div>
            <div class="geo-circle geo-2"></div>
            <div class="sidebar-content">
                <div class="sidebar-logo">VL</div>
                <h2 class="sidebar-title">Join us today</h2>
                <p class="sidebar-sub">Create your account and start managing your users right away.</p>
            </div>
        </div>

        <div class="auth-panel">
            <div class="panel-inner">
                <h1 class="panel-title">Create account</h1>
                <p class="panel-sub">Fill in your details to get started</p>

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

                    <p v-if="error" class="error-msg">{{ error }}</p>
                    <p v-if="success" class="success-msg">{{ success }}</p>

                    <button type="submit" class="submit-btn" :disabled="loading">
                        <span v-if="loading" class="spinner"></span>
                        {{ loading ? 'Creating account...' : 'Create account' }}
                    </button>
                </form>

                <p class="form-footer">
                    Already have an account?
                    <router-link to="/login" class="link">Sign in</router-link>
                </p>
            </div>
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

        setTimeout(() => {
            router.push("/login");
        }, 1000);

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
@import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500&display=swap');

* { box-sizing: border-box; }

.auth-wrapper {
    min-height: 100vh;
    display: flex;
    font-family: 'DM Sans', sans-serif;
    background: #f0f4f8;
}

.auth-sidebar {
    width: 300px;
    background: #0C447C;
    display: flex;
    align-items: flex-end;
    padding: 48px 40px;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
}

.geo-circle {
    position: absolute;
    border-radius: 50%;
    border: 32px solid rgba(255, 255, 255, 0.07);
}
.geo-1 { width: 260px; height: 260px; bottom: -60px; right: -60px; }
.geo-2 { width: 140px; height: 140px; top: -30px; left: -30px; }

.sidebar-content { position: relative; z-index: 1; }

.sidebar-logo {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(255,255,255,0.15);
    color: #fff;
    font-weight: 500;
    font-size: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 28px;
    letter-spacing: 0.05em;
}

.sidebar-title {
    font-family: 'DM Serif Display', serif;
    color: #fff;
    font-size: 28px;
    font-weight: 400;
    line-height: 1.3;
    margin: 0 0 12px;
}

.sidebar-sub {
    color: rgba(255, 255, 255, 0.5);
    font-size: 14px;
    line-height: 1.7;
    margin: 0;
}

.auth-panel {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 32px;
}

.panel-inner {
    width: 100%;
    max-width: 420px;
}

.panel-title {
    font-family: 'DM Serif Display', serif;
    font-size: 26px;
    font-weight: 400;
    color: #1a202c;
    margin: 0 0 6px;
}

.panel-sub {
    font-size: 14px;
    color: #718096;
    margin: 0 0 32px;
}

.form-group {
    margin-bottom: 18px;
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
    letter-spacing: 0.05em;
    text-transform: uppercase;
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
    font-size: 13px;
    color: #e53e3e;
    margin: -4px 0 16px;
    padding: 10px 14px;
    background: #fff5f5;
    border-radius: 8px;
    border-left: 3px solid #e53e3e;
}

.success-msg {
    font-size: 13px;
    color: #276749;
    margin: -4px 0 16px;
    padding: 10px 14px;
    background: #f0fff4;
    border-radius: 8px;
    border-left: 3px solid #38a169;
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
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    flex-shrink: 0;
}

@keyframes spin { to { transform: rotate(360deg); } }

.form-footer {
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

@media (max-width: 640px) {
    .auth-sidebar { display: none; }
    .auth-panel { padding: 32px 20px; }
    .form-row { flex-direction: column; gap: 0; }
}
</style>
