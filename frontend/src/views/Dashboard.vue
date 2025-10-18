<template>
  <div class="min-h-screen bg-slate-50">
    <!-- Header -->
    <header class="bg-white shadow-lg border-b border-slate-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <div class="flex items-center space-x-4">
            <div class="bg-blue-600 rounded-lg p-2">
              <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
              </svg>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-slate-900">TaskFlow</h1>
              <p class="text-sm text-slate-500">Professional Task Management</p>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
              <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-semibold">
                  {{ authStore.user?.name?.charAt(0).toUpperCase() }}
                </span>
              </div>
              <span class="text-sm font-medium text-slate-700"
                >Welcome, {{ authStore.user?.name }}</span
              >
            </div>
            <BaseButton variant="ghost" @click="handleLogout" size="sm">
              <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                />
              </svg>
              Logout
            </BaseButton>
          </div>
        </div>
      </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        <div class="lg:w-64 sticky top-6 max-h-[calc(100vh-3rem)] overflow-y-auto">
          <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <nav class="space-y-2">
              <router-link
                to="/dashboard"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200"
                :class="
                  isActive('/dashboard')
                    ? 'bg-blue-50 text-blue-700 border border-blue-200'
                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'
                "
              >
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                  />
                </svg>
                Dashboard
              </router-link>
              <router-link
                to="/my-tasks"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200"
                :class="
                  isActive('/my-tasks')
                    ? 'bg-blue-50 text-blue-700 border border-blue-200'
                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'
                "
              >
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                  />
                </svg>
                My Tasks
              </router-link>
              <router-link
                to="/assigned-tasks"
                class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200"
                :class="
                  isActive('/assigned-tasks')
                    ? 'bg-blue-50 text-blue-700 border border-blue-200'
                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'
                "
              >
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                  />
                </svg>
                Assigned to Me
              </router-link>
            </nav>

            <!-- Quick Stats -->
            <div class="mt-8 pt-6 border-t border-slate-200">
              <h3 class="text-sm font-semibold text-slate-900 mb-4">Quick Stats</h3>
              <div class="space-y-3">
                <div class="flex items-center justify-between">
                  <span class="text-sm text-slate-600">Total Tasks</span>
                  <span class="text-sm font-semibold text-slate-900">{{
                    taskStore.tasks.length
                  }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-slate-600">In Progress</span>
                  <span class="text-sm font-semibold text-blue-600">
                    {{ taskStore.tasks.filter((t) => t.status === "in-progress").length }}
                  </span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-slate-600">Completed</span>
                  <span class="text-sm font-semibold text-green-600">
                    {{ taskStore.tasks.filter((t) => t.status === "done").length }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
          <div class="flex justify-between items-center mb-8">
            <div>
              <h2 class="text-2xl font-bold text-slate-900">Tasks</h2>
              <p class="text-slate-600 mt-1">Manage and track your tasks efficiently</p>
            </div>
            <BaseButton @click="showCreateModal = true" size="lg">
              <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                />
              </svg>
              New Task
            </BaseButton>
          </div>

          <!-- Filters -->
          <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
            <div class="flex flex-wrap gap-4 items-end">
              <div class="flex-1 min-w-64">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Search Tasks</label>
                <BaseInput
                  v-model="filters.search"
                  placeholder="Search by title or description..."
                  @input="debouncedFetchTasks"
                />
              </div>
              <div class="min-w-32">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                <BaseSelect
                  v-model="filters.status"
                  :options="statusOptions"
                  placeholder="All Status"
                  @change="fetchTasks"
                />
              </div>
              <div class="min-w-32">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Priority</label>
                <BaseSelect
                  v-model="filters.priority"
                  :options="priorityOptions"
                  placeholder="All Priority"
                  @change="fetchTasks"
                />
              </div>
              <div class="min-w-32">
                <label class="block text-sm font-semibold text-slate-700 mb-2"
                  >Items per page</label
                >
                <BaseSelect
                  v-model="filters.per_page"
                  :options="perPageOptions"
                  @change="fetchTasks"
                />
              </div>
            </div>
          </div>

          <!-- Tasks Grid -->
          <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div v-if="taskStore.isLoading" class="p-12 text-center">
              <div
                class="animate-spin rounded-full h-12 w-12 border-4 border-blue-200 border-t-blue-600 mx-auto"
              ></div>
              <p class="mt-4 text-slate-600 font-medium">Loading tasks...</p>
            </div>
            <div v-else-if="taskStore.tasks.length === 0" class="p-12 text-center">
              <svg
                class="mx-auto h-16 w-16 text-slate-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="1"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
              </svg>
              <h3 class="mt-4 text-lg font-semibold text-slate-900">No tasks found</h3>
              <p class="mt-2 text-slate-600">Get started by creating your first task.</p>
            </div>
            <div v-else class="divide-y divide-slate-200">
              <div
                v-for="task in taskStore.tasks"
                :key="task.id"
                class="p-6 hover:bg-slate-50 cursor-pointer transition-colors duration-200 card-hover"
                @click="viewTask(task)"
              >
                <div class="flex items-start justify-between">
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-3 mb-2">
                      <h3 class="text-lg font-semibold text-slate-900 truncate">
                        {{ task.title }}
                      </h3>
                      <span class="status-badge" :class="statusBadgeClass(task.status)">
                        {{ task.status.replace("-", " ") }}
                      </span>
                    </div>
                    <p class="text-slate-600 mb-3 line-clamp-2">{{ task.description }}</p>
                    <div class="flex items-center space-x-4 text-sm">
                      <span class="priority-badge" :class="priorityBadgeClass(task.priority)">
                        {{ task.priority }}
                      </span>
                      <span v-if="task.due_date" class="flex items-center text-slate-500">
                        <svg
                          class="h-4 w-4 mr-1"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                          />
                        </svg>
                        {{ formatDate(task.due_date) }}
                      </span>
                      <span v-if="task.assigned_user" class="flex items-center text-slate-500">
                        <svg
                          class="h-4 w-4 mr-1"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                          />
                        </svg>
                        {{ task.assigned_user.name }}
                      </span>
                    </div>
                  </div>
                  <div class="flex items-center space-x-2 ml-4">
                    <BaseButton variant="ghost" size="sm" @click.stop="editTask(task)">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                        />
                      </svg>
                    </BaseButton>
                    <BaseButton
                      variant="ghost"
                      size="sm"
                      class="text-red-600 hover:text-red-700 hover:bg-red-50"
                      @click.stop="deleteTask(task)"
                    >
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                        />
                      </svg>
                    </BaseButton>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="taskStore.lastPage > 1" class="mt-8 flex justify-center">
            <nav class="flex items-center space-x-2">
              <BaseButton
                variant="outline"
                size="sm"
                @click="changePage(taskStore.currentPage - 1)"
                :disabled="taskStore.currentPage === 1"
              >
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7"
                  />
                </svg>
                Previous
              </BaseButton>

              <div class="flex items-center space-x-1">
                <span class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg">
                  Page {{ taskStore.currentPage }} of {{ taskStore.lastPage }}
                </span>
              </div>

              <BaseButton
                variant="outline"
                size="sm"
                @click="changePage(taskStore.currentPage + 1)"
                :disabled="taskStore.currentPage === taskStore.lastPage"
              >
                Next
                <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                  />
                </svg>
              </BaseButton>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Task Modal -->
    <BaseModal
      :is-open="showCreateModal || showEditModal"
      :title="showEditModal ? 'Edit Task' : 'Create New Task'"
      @close="closeModals"
    >
      <TaskForm :task="editingTask" @submit="handleTaskSubmit" @cancel="closeModals" />
    </BaseModal>

    <!-- Task Detail Modal -->
    <BaseModal
      :is-open="showTaskDetailModal"
      :title="selectedTask?.title || 'Task Details'"
      size="4xl"
      @close="closeTaskDetail"
    >
      <TaskDetail
        v-if="selectedTask"
        :task="selectedTask"
        @edit="editTask(selectedTask)"
        @close="closeTaskDetail"
      />
    </BaseModal>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { useTaskStore } from "@/stores/tasks";
import { useToastStore } from "@/stores/toast";
import type { Task } from "@/types";
import BaseButton from "@/components/base/Button.vue";
import BaseInput from "@/components/base/Input.vue";
import BaseSelect from "@/components/base/Select.vue";
import BaseModal from "@/components/base/Modal.vue";
import TaskForm from "@/components/TaskForm.vue";
import TaskDetail from "@/components/TaskDetail.vue";

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();
const taskStore = useTaskStore();
const toastStore = useToastStore();

const showCreateModal = ref(false);
const showEditModal = ref(false);
const showTaskDetailModal = ref(false);
const editingTask = ref<Task | null>(null);
const selectedTask = ref<Task | null>(null);

const filters = reactive({
  search: "",
  status: "",
  priority: "",
  per_page: 10,
});

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

const perPageOptions = [
  { value: 10, label: "10" },
  { value: 25, label: "25" },
  { value: 50, label: "50" },
  { value: 100, label: "100" },
];

let debounceTimer: NodeJS.Timeout | null = null;

const debouncedFetchTasks = () => {
  if (debounceTimer) clearTimeout(debounceTimer);
  debounceTimer = setTimeout(fetchTasks, 300);
};

const fetchTasks = async () => {
  const params: any = {};

  if (filters.search) params.search = filters.search;
  if (filters.status) params.status = filters.status;
  if (filters.priority) params.priority = filters.priority;
  if (filters.per_page) params.per_page = filters.per_page;

  // Add route-specific filters
  if (route.path === "/my-tasks") {
    params.created_by = authStore.user?.id;
  } else if (route.path === "/assigned-tasks") {
    params.assigned_user_id = authStore.user?.id;
  }

  await taskStore.fetchTasks(params);
};

const changePage = async (page: number) => {
  const params: any = { page, per_page: filters.per_page };

  if (filters.search) params.search = filters.search;
  if (filters.status) params.status = filters.status;
  if (filters.priority) params.priority = filters.priority;

  // Add route-specific filters
  if (route.path === "/my-tasks") {
    params.created_by = authStore.user?.id;
  } else if (route.path === "/assigned-tasks") {
    params.assigned_user_id = authStore.user?.id;
  }

  await taskStore.fetchTasks(params);
};

const isActive = (path: string) => route.path === path;

const statusBadgeClass = (status: string) => {
  const classes = {
    todo: "bg-gray-100 text-gray-800",
    "in-progress": "bg-blue-100 text-blue-800",
    done: "bg-green-100 text-green-800",
  };
  return classes[status as keyof typeof classes] || classes.todo;
};

const priorityBadgeClass = (priority: string) => {
  const classes = {
    low: "bg-green-100 text-green-800",
    medium: "bg-yellow-100 text-yellow-800",
    high: "bg-red-100 text-red-800",
  };
  return classes[priority as keyof typeof classes] || classes.low;
};

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString();
};

const handleLogout = async () => {
  await authStore.logout();
  router.push("/login");
};

const viewTask = async (task: Task) => {
  selectedTask.value = task;
  await taskStore.fetchTask(task.id);
  selectedTask.value = taskStore.currentTask;
  showTaskDetailModal.value = true;
};

const editTask = (task: Task) => {
  editingTask.value = task;
  showEditModal.value = true;
  showTaskDetailModal.value = false;
};

const deleteTask = async (task: Task) => {
  if (confirm("Are you sure you want to delete this task?")) {
    try {
      await taskStore.deleteTask(task.id);
      toastStore.success("Task deleted successfully");
    } catch (error) {
      toastStore.error("Failed to delete task");
    }
  }
};

const handleTaskSubmit = async (taskData: any) => {
  try {
    if (editingTask.value) {
      await taskStore.updateTask(editingTask.value.id, taskData);
      toastStore.success("Task updated successfully");
    } else {
      await taskStore.createTask(taskData);
      toastStore.success("Task created successfully");
    }
    closeModals();
    fetchTasks();
  } catch (error) {
    toastStore.error("Failed to save task");
  }
};

const closeModals = () => {
  showCreateModal.value = false;
  showEditModal.value = false;
  editingTask.value = null;
};

const closeTaskDetail = () => {
  showTaskDetailModal.value = false;
  selectedTask.value = null;
};

onMounted(() => {
  fetchTasks();
  taskStore.setupRealtimeListeners();
});

// Watch for route changes to refetch tasks
watch(
  () => route.path,
  () => {
    fetchTasks();
  }
);
</script>
