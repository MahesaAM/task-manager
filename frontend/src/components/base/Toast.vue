<template>
  <div class="fixed top-4 right-4 z-50 space-y-2">
    <TransitionGroup
      enter-active-class="transition-all duration-300"
      leave-active-class="transition-all duration-300"
      enter-from-class="opacity-0 transform translate-x-full"
      enter-to-class="opacity-100 transform translate-x-0"
      leave-from-class="opacity-100 transform translate-x-0"
      leave-to-class="opacity-0 transform translate-x-full"
    >
      <div
        v-for="toast in toasts"
        :key="toast.id"
        :class="toastClasses(toast.type)"
        class="flex items-center p-4 rounded-md shadow-lg max-w-sm"
      >
        <div class="flex-shrink-0">
          <component :is="iconComponent(toast.type)" class="w-5 h-5" />
        </div>
        <div class="ml-3 flex-1">
          <p class="text-sm font-medium">{{ toast.message }}</p>
        </div>
        <div class="ml-4 flex-shrink-0">
          <button
            @click="removeToast(toast.id)"
            class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 rounded-md"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>
        </div>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { useToastStore, type Toast } from "@/stores/toast";

const toastStore = useToastStore();

const toasts = computed(() => toastStore.toasts);
const removeToast = (id: number) => toastStore.removeToast(id);

const toastClasses = (type: Toast["type"]) => {
  const baseClasses = "text-white";

  const typeClasses = {
    success: "bg-green-500",
    error: "bg-red-500",
    warning: "bg-yellow-500",
    info: "bg-blue-500",
  };

  return [baseClasses, typeClasses[type]].join(" ");
};

const iconComponent = (type: Toast["type"]) => {
  const icons = {
    success: "CheckCircleIcon",
    error: "XCircleIcon",
    warning: "ExclamationTriangleIcon",
    info: "InformationCircleIcon",
  };

  return icons[type];
};
</script>
