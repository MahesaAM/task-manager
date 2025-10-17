<template>
  <button :class="buttonClasses" :disabled="disabled || loading" @click="$emit('click', $event)">
    <span v-if="loading" class="mr-2">
      <svg class="animate-spin h-4 w-4 text-current" fill="none" viewBox="0 0 24 24">
        <circle
          class="opacity-25"
          cx="12"
          cy="12"
          r="10"
          stroke="currentColor"
          stroke-width="4"
        ></circle>
        <path
          class="opacity-75"
          fill="currentColor"
          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
        ></path>
      </svg>
    </span>
    <slot />
  </button>
</template>

<script setup lang="ts">
import { computed } from "vue";

interface Props {
  variant?: "primary" | "secondary" | "danger" | "outline" | "ghost";
  size?: "sm" | "md" | "lg";
  loading?: boolean;
  disabled?: boolean;
  fullWidth?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  variant: "primary",
  size: "md",
  loading: false,
  disabled: false,
  fullWidth: false,
});

defineEmits<{
  click: [event: Event];
}>();

const buttonClasses = computed(() => {
  const baseClasses =
    "inline-flex items-center justify-center font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 btn-hover shadow-sm";

  const variantClasses = {
    primary:
      "bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500 shadow-blue-500/25 hover:shadow-blue-500/40",
    secondary:
      "bg-slate-600 hover:bg-slate-700 text-white focus:ring-slate-500 shadow-slate-500/25 hover:shadow-slate-500/40",
    danger:
      "bg-red-600 hover:bg-red-700 text-white focus:ring-red-500 shadow-red-500/25 hover:shadow-red-500/40",
    outline:
      "border-2 border-slate-300 bg-white hover:bg-slate-50 text-slate-700 focus:ring-blue-500 hover:border-slate-400",
    ghost: "bg-transparent hover:bg-slate-100 text-slate-700 focus:ring-slate-500",
  };

  const sizeClasses = {
    sm: "px-3 py-1.5 text-sm gap-1.5",
    md: "px-4 py-2 text-sm gap-2",
    lg: "px-6 py-3 text-base gap-2",
  };

  const widthClass = props.fullWidth ? "w-full" : "";
  const disabledClass =
    props.disabled || props.loading ? "opacity-50 cursor-not-allowed shadow-none" : "";

  return [
    baseClasses,
    variantClasses[props.variant],
    sizeClasses[props.size],
    widthClass,
    disabledClass,
  ]
    .filter(Boolean)
    .join(" ");
});
</script>
