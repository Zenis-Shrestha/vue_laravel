import { reactive } from 'vue';

export const loadingState = reactive({
  isLoading: false,
  activeRequests: 0,

  start() {
    this.activeRequests++;
    this.isLoading = true;
  },

  stop() {
    this.activeRequests--;
    if (this.activeRequests <= 0) {
      this.activeRequests = 0;
      this.isLoading = false;
    }
  }
});
