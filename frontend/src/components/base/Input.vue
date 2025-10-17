<template>
  <div class="relative">
    <label v-if="label" :for="id" class="block text-sm font-semibold text-slate-700 mb-2">
      {{ label }}
    </label>
    <div class="relative">
      <input
        :id="id"
        :type="type"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        :class="inputClasses"
        @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
        @blur="$emit('blur', $event)"
        @focus="$emit('focus', $event)"
      />
      <div
        v-if="error"
        class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
      >
        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
          <path
            fill-rule="evenodd"
            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
            clip-rule="evenodd"
          />
        </svg>
      </div>
    </div>
    <div v-if="error" class="mt-2 text-sm text-red-600 flex items-center gap-1">
      <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path
          fill-rule="evenodd"
          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
          clip-rule="evenodd"
        />
      </svg>
      {{ error }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from "vue";

interface Props {
  modelValue?: string;
  type?: string;
  label?: string;
  placeholder?: string;
  disabled?: boolean;
  error?: string;
  id?: string;
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: "",
  type: "text",
  label: "",
  placeholder: "",
  disabled: false,
  error: "",
  id: "",
});

defineEmits<{
  "update:modelValue": [value: string];
  blur: [event: FocusEvent];
  focus: [event: Event];
}>();

const inputClasses = computed(() => {
  const baseClasses =
    "block w-full px-4 py-3 border-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 sm:text-sm transition-all duration-200 bg-white";

  const stateClasses = props.error
    ? "border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500"
    : "border-slate-300 text-slate-900 placeholder-slate-400 focus:ring-blue-500 focus:border-blue-500";

  const disabledClass = props.disabled
    ? "bg-slate-50 text-slate-500 cursor-not-allowed opacity-60"
    : "hover:border-slate-400";

  return [baseClasses, stateClasses, disabledClass].filter(Boolean).join(" ");
});
</script>
