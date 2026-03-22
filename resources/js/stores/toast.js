import { reactive } from 'vue';

export const toastState = reactive({
    toasts: [],

    // add a new toast
    show(message, type = 'success', duration = 3000) {
        const id = Date.now(); // unique id for each toast

        this.toasts.push({ id, message, type });

        // auto remove after duration
        setTimeout(() => {
            this.remove(id);
        }, duration);
    },

    // remove a toast by id
    remove(id) {
        const index = this.toasts.findIndex(t => t.id === id);
        if (index !== -1) {
            this.toasts.splice(index, 1);
        }
    },

    // shortcut helpers
    success(message) { this.show(message, 'success'); },
    error(message)   { this.show(message, 'error'); },
    warning(message) { this.show(message, 'warning'); },
});
