import { defineStore } from "pinia";
import { ref, computed } from "vue";
import type { Task, PaginatedResponse, TaskComment, TaskAttachment } from "@/types";
import { taskApi, commentApi, attachmentApi } from "@/utils/api";

export const useTaskStore = defineStore("tasks", () => {
  const tasks = ref<Task[]>([]);
  const currentTask = ref<Task | null>(null);
  const pagination = ref<{
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  } | null>(null);
  const isLoading = ref(false);
  const isCreating = ref(false);
  const isUpdating = ref(false);
  const isDeleting = ref(false);

  const totalTasks = computed(() => pagination.value?.total || 0);
  const currentPage = computed(() => pagination.value?.current_page || 1);
  const lastPage = computed(() => pagination.value?.last_page || 1);

  const fetchTasks = async (params?: Parameters<typeof taskApi.getTasks>[0]) => {
    isLoading.value = true;
    try {
      const response = await taskApi.getTasks(params);
      tasks.value = response.data;
      pagination.value = {
        current_page: response.current_page,
        last_page: response.last_page,
        per_page: response.per_page,
        total: response.total,
      };
    } finally {
      isLoading.value = false;
    }
  };

  const fetchTask = async (id: number) => {
    isLoading.value = true;
    try {
      const task = await taskApi.getTask(id);
      currentTask.value = task;
      return task;
    } finally {
      isLoading.value = false;
    }
  };

  const createTask = async (taskData: Parameters<typeof taskApi.createTask>[0]) => {
    isCreating.value = true;
    try {
      const task = await taskApi.createTask(taskData);
      tasks.value.unshift(task);
      if (pagination.value) {
        pagination.value.total += 1;
      }
      return task;
    } finally {
      isCreating.value = false;
    }
  };

  const updateTask = async (id: number, updates: Parameters<typeof taskApi.updateTask>[1]) => {
    isUpdating.value = true;
    try {
      const task = await taskApi.updateTask(id, updates);

      // Update in tasks list
      const index = tasks.value.findIndex((t) => t.id === id);
      if (index !== -1) {
        tasks.value[index] = task;
      }

      // Update current task if it's the same
      if (currentTask.value?.id === id) {
        currentTask.value = task;
      }

      return task;
    } finally {
      isUpdating.value = false;
    }
  };

  const deleteTask = async (id: number) => {
    isDeleting.value = true;
    try {
      await taskApi.deleteTask(id);
      tasks.value = tasks.value.filter((t) => t.id !== id);
      if (pagination.value) {
        pagination.value.total -= 1;
      }
    } finally {
      isDeleting.value = false;
    }
  };

  const uploadAttachment = async (
    taskId: number,
    file: File,
    onProgress?: (progress: number) => void
  ) => {
    const attachment = await attachmentApi.upload(taskId, file, onProgress);

    // Update current task attachments
    if (currentTask.value?.id === taskId) {
      currentTask.value.attachments = currentTask.value.attachments || [];
      currentTask.value.attachments.push(attachment);
    }

    return attachment;
  };

  const deleteAttachment = async (id: number) => {
    await attachmentApi.delete(id);

    // Remove from current task attachments
    if (currentTask.value?.attachments) {
      currentTask.value.attachments = currentTask.value.attachments.filter((a) => a.id !== id);
    }
  };

  const downloadAttachment = async (id: number, filename: string) => {
    const blob = await attachmentApi.download(id);
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);
  };

  const fetchComments = async (taskId: number) => {
    const comments = await commentApi.getComments(taskId);
    if (currentTask.value?.id === taskId) {
      currentTask.value.comments = comments;
    }
    return comments;
  };

  const createComment = async (taskId: number, comment: string) => {
    const newComment = await commentApi.createComment(taskId, comment);
    if (currentTask.value?.id === taskId) {
      currentTask.value.comments = currentTask.value.comments || [];
      currentTask.value.comments.push(newComment);
    }
    return newComment;
  };

  const updateComment = async (id: number, comment: string) => {
    const updatedComment = await commentApi.updateComment(id, comment);
    if (currentTask.value?.comments) {
      const index = currentTask.value.comments.findIndex((c) => c.id === id);
      if (index !== -1) {
        currentTask.value.comments[index] = updatedComment;
      }
    }
    return updatedComment;
  };

  const deleteComment = async (id: number) => {
    await commentApi.deleteComment(id);
    if (currentTask.value?.comments) {
      currentTask.value.comments = currentTask.value.comments.filter((c) => c.id !== id);
    }
  };

  return {
    tasks,
    currentTask,
    pagination,
    isLoading,
    isCreating,
    isUpdating,
    isDeleting,
    totalTasks,
    currentPage,
    lastPage,
    fetchTasks,
    fetchTask,
    createTask,
    updateTask,
    deleteTask,
    uploadAttachment,
    deleteAttachment,
    downloadAttachment,
    fetchComments,
    createComment,
    updateComment,
    deleteComment,
  };
});
