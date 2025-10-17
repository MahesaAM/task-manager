import { defineStore } from "pinia";
import { ref } from "vue";

export interface Toast {
  id: number;
  message: string;
  type: "success" | "error" | "warning" | "info";
  duration?: number;
}

export const useToastStore = defineStore("toast", () => {
  const toasts = ref<Toast[]>([]);
  let nextId = 1;

  const addToast = (message: string, type: Toast["type"] = "info", duration = 3000) => {
    const toast: Toast = {
      id: nextId++,
      message,
      type,
      duration,
    };

    toasts.value.push(toast);

    if (duration > 0) {
      setTimeout(() => {
        removeToast(toast.id);
      }, duration);
    }

    return toast.id;
  };

  const removeToast = (id: number) => {
    const index = toasts.value.findIndex((t) => t.id === id);
    if (index !== -1) {
      toasts.value.splice(index, 1);
    }
  };

  const success = (message: string, duration?: number) => addToast(message, "success", duration);
  const error = (message: string, duration?: number) => addToast(message, "error", duration);
  const warning = (message: string, duration?: number) => addToast(message, "warning", duration);
  const info = (message: string, duration?: number) => addToast(message, "info", duration);

  return {
    toasts,
    addToast,
    removeToast,
    success,
    error,
    warning,
    info,
  };
});
