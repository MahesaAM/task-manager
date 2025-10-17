<template>
  <div class="relative">
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 mb-1">
      {{ label }}
    </label>
    <select
      :id="id"
      :value="modelValue"
      :disabled="disabled"
      :class="selectClasses"
      @change="$emit('update:modelValue', ($event.target as HTMLSelectElement).value)"
      @blur="$emit('blur', $event)"
      @focus="$emit('focus', $event)"
    >
      <option v-if="placeholder" value="" disabled>{{ placeholder }}</option>
      <option v-for="option in options" :key="option.value" :value="option.value">
        {{ option.label }}
      </option>
    </select>
    <div v-if="error" class="mt-1 text-sm text-red-600">
      {{ error }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from "vue";

interface Option {
  value: string | number;
  label: string;
}

interface Props {
  modelValue?: string | number;
  options: Option[];
  label?: string;
  placeholder?: string;
  disabled?: boolean;
  error?: string;
  id?: string;
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: "",
  options: () => [],
  label: "",
  placeholder: "",
  disabled: false,
  error: "",
  id: "",
});

defineEmits<{
  "update:modelValue": [value: string | number];
  blur: [event: FocusEvent];
  focus: [event: Event];
}>();

const selectClasses = computed(() => {
  const baseClasses =
    "block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-200 appearance-none bg-white";

  const errorClass = props.error ? "border-red-300 text-red-900" : "border-gray-300";

  const disabledClass = props.disabled ? "bg-gray-50 text-gray-500 cursor-not-allowed" : "";

  return [baseClasses, errorClass, disabledClass].filter(Boolean).join(" ");
});
</script>
