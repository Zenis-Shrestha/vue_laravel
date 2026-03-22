<template>
    <div class="toast-container">
        <TransitionGroup name="toast">
            <div
                v-for="toast in toastState.toasts"
                :key="toast.id"
                class="toast"
                :class="toast.type"
            >
                <!-- Icon -->
                <div class="toast-icon">
                    <!-- success -->
                    <svg v-if="toast.type === 'success'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <!-- error -->
                    <svg v-else-if="toast.type === 'error'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    <!-- warning -->
                    <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>

                <!-- Message -->
                <span class="toast-message">{{ toast.message }}</span>

                <!-- Close button -->
                <button class="toast-close" @click="toastState.remove(toast.id)">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>

                <!-- Progress bar -->
                <div class="toast-progress" :class="toast.type"></div>
            </div>
        </TransitionGroup>
    </div>
</template>

<script setup>
import { toastState } from '../../stores/toast';
</script>

<style scoped>
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 10px;
    pointer-events: none;
}

.toast {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    border-radius: 10px;
    min-width: 280px;
    max-width: 380px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    pointer-events: all;
    position: relative;
    overflow: hidden;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
}

/* Types */
.toast.success {
    background: #f0fff4;
    border: 1px solid #9ae6b4;
    color: #276749;
}
.toast.error {
    background: #fff5f5;
    border: 1px solid #fed7d7;
    color: #c53030;
}
.toast.warning {
    background: #fffbeb;
    border: 1px solid #fcd34d;
    color: #92400e;
}

.toast-icon {
    flex-shrink: 0;
    display: flex;
    align-items: center;
}

.toast-message {
    flex: 1;
    line-height: 1.4;
}

.toast-close {
    background: none;
    border: none;
    cursor: pointer;
    padding: 2px;
    color: inherit;
    opacity: 0.6;
    display: flex;
    align-items: center;
    flex-shrink: 0;
}
.toast-close:hover { opacity: 1; }

/* Progress bar */
.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    width: 100%;
    animation: progress 3s linear forwards;
    border-radius: 0 0 10px 10px;
}
.toast-progress.success { background: #38a169; }
.toast-progress.error   { background: #e53e3e; }
.toast-progress.warning { background: #d97706; }

@keyframes progress {
    from { width: 100%; }
    to   { width: 0%; }
}

/* Slide in animation */
.toast-enter-active { transition: all 0.3s ease; }
.toast-leave-active { transition: all 0.25s ease; }
.toast-enter-from   { opacity: 0; transform: translateX(100%); }
.toast-leave-to     { opacity: 0; transform: translateX(100%); }
</style>
