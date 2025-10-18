<template>
  <div class="relative">
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 mb-1">
      {{ label }}
    </label>
    <textarea
      :id="id"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      :rows="rows"
      :class="textareaClasses"
      @input="$emit('update:modelValue', ($event.target as HTMLTextAreaElement).value)"
      @blur="$emit('blur', $event)"
      @focus="$emit('focus', $event)"
    ></textarea>
    <div v-if="error" class="mt-1 text-sm text-red-600">
      {{ error }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from "vue";

interface Props {
  modelValue?: string;
  label?: string;
  placeholder?: string;
  disabled?: boolean;
  error?: string;
  rows?: number | string;
  id?: string;
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: "",
  label: "",
  placeholder: "",
  disabled: false,
  error: "",
  rows: 3,
  id: "",
});

defineEmits<{
  "update:modelValue": [value: string];
  blur: [event: FocusEvent];
  focus: [event: Event];
}>();

const textareaClasses = computed(() => {
  const baseClasses =
    "block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-200 resize-vertical";

  const errorClass = props.error
    ? "border-red-300 text-red-900 placeholder-red-300"
    : "border-gray-300";

  const disabledClass = props.disabled ? "bg-gray-50 text-gray-500 cursor-not-allowed" : "";

  return [baseClasses, errorClass, disabledClass].filter(Boolean).join(" ");
});
</script>
