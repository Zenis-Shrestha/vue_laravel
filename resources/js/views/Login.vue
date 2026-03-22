<template>
    <div class="auth-wrapper">
        <div class="auth-card">

            <!-- Logo -->
            <div class="card-logo">
                <div class="logo-icon">LVL</div>
                <span class="logo-text">Learning Vue Laravel</span>
            </div>

            <h1 class="card-title">Welcome back</h1>
            <p class="card-sub">Sign in to your account to continue</p>

            <div class="form-group">
                <label class="form-label">Email address</label>
                <input v-model="email" type="email" class="form-input" placeholder="you@example.com"
                    :class="{ 'input-error': error }" @keyup.enter="login" />
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <input v-model="password" :type="showPassword ? 'text' : 'password'" class="form-input"
                        placeholder="••••••••" :class="{ 'input-error': error }" @keyup.enter="login" />
                    <!-- toggle button sits inside the input -->
                    <button type="button" class="eye-btn" @click="showPassword = !showPassword">
                        <!-- Eye icon — show when password is hidden -->
                        <svg v-if="!showPassword" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        <!-- Eye-off icon — show when password is visible -->
                        <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
                            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                            <line x1="1" y1="1" x2="23" y2="23" />
                        </svg>
                    </button>
                </div>
            </div>

            <div v-if="error" class="error-msg">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                {{ error }}
            </div>

            <button class="submit-btn" :disabled="loadingState.isLoading" @click="login">
                <span v-if="loadingState.isLoading" class="spinner"></span>
                {{ loadingState.isLoading ? 'Signing in...' : 'Sign in' }}
            </button>
            <p class="card-footer">
                Don't have an account?
                <router-link to="/register" class="link">Create one</router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import api from "../services/api";                           // ✅ fixed: was axios
import { useRouter } from "vue-router";
import { loadingState } from '../stores/loading';   // ✅ already correct

const router = useRouter();
const email = ref("");
const password = ref("");
const error = ref("");
const showPassword = ref(false);

async function login() {
    error.value = "";

    try {
        const res = await api.post("/login", {      // ✅ api is now defined
            email: email.value,
            password: password.value,
        });
        localStorage.setItem('token', res.data.token);
        localStorage.setItem('user', JSON.stringify(res.data.user)); // ✅ add this
        router.push('/dashboard');
    } catch (err) {
        error.value = "Invalid email or password. Please try again.";
    }
}
</script>
<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&family=DM+Serif+Display&display=swap');

* {
    box-sizing: border-box;
}

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
    max-width: 420px;
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

.form-input.input-error {
    border-color: #e53e3e;
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

.submit-btn:hover:not(:disabled) {
    background: #0C447C;
}

.submit-btn:active:not(:disabled) {
    transform: scale(0.99);
}

.submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.spinner {
    width: 15px;
    height: 15px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    flex-shrink: 0;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

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

.link:hover {
    text-decoration: underline;
}

@media (max-width: 480px) {
    .auth-card {
        padding: 28px 20px;
    }
}


/* wraps the input and button together */
.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

/* positions the eye button inside the input on the right */
.eye-btn {
    position: absolute;
    right: 12px;
    background: none;
    border: none;
    cursor: pointer;
    color: #718096;
    padding: 0;
    display: flex;
    align-items: center;
}

.eye-btn:hover {
    color: #185FA5;
}

/* add padding-right so text doesn't go under the eye icon */
.input-wrapper .form-input {
    padding-right: 40px;
}
</style>
