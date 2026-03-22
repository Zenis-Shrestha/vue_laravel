<template>
    <button class="btn" :class="[variantClass, sizeClass]" :disabled="disabled || loading" @click="$emit('click')">
        <span v-if="loading" class="spinner"></span>
        <slot /> <!-- the button label goes here -->
    </button>
</template>

<script setup>
const props = defineProps({
    variant: {
        type: String,
        default: 'primary',        // 'primary', 'secondary', 'danger'
    },
    size: {
        type: String,
        default: 'md',             // 'sm', 'md'
    },
    loading: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['click']);

const variantClass = {
    primary: 'btn-primary',
    secondary: 'btn-secondary',
    danger: 'btn-danger',
}[props.variant] ?? 'btn-primary';

const sizeClass = {
    sm: 'btn-sm',
    md: 'btn-md',
}[props.size] ?? 'btn-md';
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&display=swap');

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-family: 'DM Sans', sans-serif;
    font-weight: 500;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.15s, opacity 0.15s;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Sizes */
.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.btn-md {
    padding: 10px 18px;
    font-size: 14px;
}

/* Variants */
.btn-primary {
    background: #185FA5;
    color: #fff;
}

.btn-primary:hover:not(:disabled) {
    background: #0C447C;
}

.btn-secondary {
    background: #fff;
    color: #4a5568;
    border: 1px solid #e2e8f0;
}

.btn-secondary:hover:not(:disabled) {
    background: #f7fafc;
}

.btn-danger {
    background: #e53e3e;
    color: #fff;
}

.btn-danger:hover:not(:disabled) {
    background: #c53030;
}

/* Spinner */
.spinner {
    width: 13px;
    height: 13px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
