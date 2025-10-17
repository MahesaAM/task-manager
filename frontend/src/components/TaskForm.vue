<template>
  <BaseModal :isOpen="true" @close="$emit('cancel')">
    <template #title>
      {{ task ? "Edit Task" : "Create New Task" }}
    </template>

    <form @submit.prevent="handleSubmit" class="space-y-4">
      <BaseInput
        v-model="form.title"
        label="Title"
        placeholder="Enter task title"
        :error="errors.title"
        required
      />

      <BaseTextarea
        v-model="form.description"
        label="Description"
        placeholder="Enter task description"
        :error="errors.description"
        rows="4"
      />

      <div class="grid grid-cols-2 gap-4">
        <BaseSelect
          v-model="form.status"
          label="Status"
          :options="statusOptions"
          :error="errors.status"
        />

        <BaseSelect
          v-model="form.priority"
          label="Priority"
          :options="priorityOptions"
          :error="errors.priority"
        />
      </div>

      <BaseInput v-model="form.due_date" label="Due Date" type="date" :error="errors.due_date" />

      <BaseSelect
        v-model="form.assigned_user_id"
        label="Assign to"
        :options="userOptions"
        placeholder="Select user"
        :error="errors.assigned_user_id"
      />
    </form>

    <template #footer>
      <BaseButton variant="outline" @click="$emit('cancel')"> Cancel </BaseButton>
      <BaseButton type="submit" :loading="isSubmitting" @click="handleSubmit">
        {{ task ? "Update Task" : "Create Task" }}
      </BaseButton>
    </template>
  </BaseModal>
</template>

<script setup lang="ts">
import { ref, reactive, watch, onMounted, computed } from "vue";
import type { Task, User } from "@/types";
import BaseModal from "@/components/base/Modal.vue";
import BaseInput from "@/components/base/Input.vue";
import BaseTextarea from "@/components/base/Textarea.vue";
import BaseSelect from "@/components/base/Select.vue";
import BaseButton from "@/components/base/Button.vue";

interface Props {
  task?: Task | null;
}

const props = withDefaults(defineProps<Props>(), {
  task: null,
});

const emit = defineEmits<{
  submit: [taskData: any];
  cancel: [];
}>();

const form = reactive({
  title: "",
  description: "",
  status: "todo" as Task["status"],
  priority: "medium" as Task["priority"],
  due_date: "",
  assigned_user_id: null as number | null,
});

const errors = ref<Record<string, string>>({});
const isSubmitting = ref(false);
const users = ref<User[]>([]);

const statusOptions = [
  { value: "todo", label: "To Do" },
  { value: "in-progress", label: "In Progress" },
  { value: "done", label: "Done" },
];

const priorityOptions = [
  { value: "low", label: "Low" },
  { value: "medium", label: "Medium" },
  { value: "high", label: "High" },
];

const userOptions = computed(() =>
  users.value.map((user) => ({
    value: user.id,
    label: user.name,
  }))
);

const resetForm = () => {
  Object.assign(form, {
    title: "",
    description: "",
    status: "todo",
    priority: "medium",
    due_date: "",
    assigned_user_id: null,
  });
  errors.value = {};
};

const populateForm = (task: Task) => {
  form.title = task.title;
  form.description = task.description;
  form.status = task.status;
  form.priority = task.priority;
  form.due_date = task.due_date ? task.due_date.split("T")[0] : "";
  form.assigned_user_id = task.assigned_user_id;
};

const fetchUsers = async () => {
  // For now, we'll use a simple approach. In a real app, you'd have a users API
  // For demo purposes, let's assume we have some users
  users.value = [
    {
      id: 1,
      name: "John Doe",
      email: "john@example.com",
      role: "admin",
      created_at: "",
      updated_at: "",
    },
    {
      id: 2,
      name: "Jane Smith",
      email: "jane@example.com",
      role: "user",
      created_at: "",
      updated_at: "",
    },
  ];
};

const handleSubmit = async () => {
  errors.value = {};
  isSubmitting.value = true;

  try {
    const taskData = {
      ...form,
      due_date: form.due_date || null,
    };

    emit("submit", taskData);
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    }
  } finally {
    isSubmitting.value = false;
  }
};

watch(
  () => props.task,
  (newTask) => {
    if (newTask) {
      populateForm(newTask);
    } else {
      resetForm();
    }
  },
  { immediate: true }
);

onMounted(() => {
  fetchUsers();
});
</script>
