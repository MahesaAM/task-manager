<template>
  <div class="space-y-8">
    <!-- Task Header -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
      <div class="flex items-start justify-between mb-6">
        <div class="flex-1">
          <h3 class="text-2xl font-bold text-slate-900 mb-2">{{ task.title }}</h3>
          <p class="text-slate-600 leading-relaxed">{{ task.description }}</p>
        </div>
        <BaseButton variant="outline" size="sm" @click="$emit('edit', task)">
          <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
            />
          </svg>
          Edit Task
        </BaseButton>
      </div>

      <!-- Task Metadata -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg p-4 shadow-sm border border-slate-200">
          <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg
                  class="h-4 w-4 text-blue-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </div>
            </div>
            <div>
              <p class="text-sm font-medium text-slate-500">Status</p>
              <span class="status-badge" :class="statusBadgeClass(task.status)">
                {{ task.status.replace("-", " ") }}
              </span>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg p-4 shadow-sm border border-slate-200">
          <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg
                  class="h-4 w-4 text-orange-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                  />
                </svg>
              </div>
            </div>
            <div>
              <p class="text-sm font-medium text-slate-500">Priority</p>
              <span class="priority-badge" :class="priorityBadgeClass(task.priority)">
                {{ task.priority }}
              </span>
            </div>
          </div>
        </div>

        <div v-if="task.due_date" class="bg-white rounded-lg p-4 shadow-sm border border-slate-200">
          <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                <svg
                  class="h-4 w-4 text-green-600"
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
              </div>
            </div>
            <div>
              <p class="text-sm font-medium text-slate-500">Due Date</p>
              <p class="text-sm font-semibold text-slate-900">{{ formatDate(task.due_date) }}</p>
            </div>
          </div>
        </div>

        <div
          v-if="task.assigned_user"
          class="bg-white rounded-lg p-4 shadow-sm border border-slate-200"
        >
          <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg
                  class="h-4 w-4 text-purple-600"
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
              </div>
            </div>
            <div>
              <p class="text-sm font-medium text-slate-500">Assigned to</p>
              <p class="text-sm font-semibold text-slate-900">{{ task.assigned_user.name }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
      <div class="border-b border-slate-200">
        <nav class="flex space-x-8 px-6">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="activeTab = tab.id"
            :class="[
              activeTab === tab.id
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-slate-500 hover:text-slate-700 ',
              'whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm transition-all duration-200 flex items-center space-x-2',
            ]"
          >
            <span v-if="tab.id === 'attachments'">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                />
              </svg>
            </span>
            <span v-if="tab.id === 'comments'">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                />
              </svg>
            </span>
            <span>{{ tab.name }}</span>
            <span
              v-if="tab.id === 'attachments' && attachments.length > 0"
              class="bg-blue-100 text-blue-600 text-xs px-2 py-0.5 rounded-full font-medium"
            >
              {{ attachments.length }}
            </span>
            <span
              v-if="tab.id === 'comments' && comments.length > 0"
              class="bg-green-100 text-green-600 text-xs px-2 py-0.5 rounded-full font-medium"
            >
              {{ comments.length }}
            </span>
          </button>
        </nav>
      </div>

      <div class="p-6">
        <!-- Attachments Tab -->
        <div v-if="activeTab === 'attachments'" class="space-y-6">
          <div class="flex justify-between items-center">
            <div>
              <h4 class="text-lg font-semibold text-slate-900">Attachments</h4>
              <p class="text-sm text-slate-600">Manage files and documents for this task</p>
            </div>
            <BaseButton size="sm" @click="showUploadModal = true">
              <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                />
              </svg>
              Upload File
            </BaseButton>
          </div>

          <div
            v-if="attachments.length === 0"
            class="text-center py-12 bg-slate-50 rounded-lg border-2 border-dashed border-slate-200"
          >
            <svg
              class="mx-auto h-12 w-12 text-slate-400"
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
            <h3 class="mt-4 text-lg font-medium text-slate-900">No attachments yet</h3>
            <p class="mt-2 text-slate-600">Upload files to share with your team</p>
          </div>
          <div v-else class="grid gap-4">
            <div
              v-for="attachment in attachments"
              :key="attachment.id"
              class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200 hover:bg-slate-100 transition-colors duration-200"
            >
              <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                  <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg
                      class="w-5 h-5 text-blue-600"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                      />
                    </svg>
                  </div>
                </div>
                <div class="min-w-0 flex-1">
                  <p class="text-sm font-semibold text-slate-900 truncate">
                    {{ attachment.file_name }}
                  </p>
                  <p class="text-xs text-slate-500">{{ formatFileSize(attachment.file_size) }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-2">
                <BaseButton variant="ghost" size="sm" @click="downloadAttachment(attachment)">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                  </svg>
                </BaseButton>
                <BaseButton
                  variant="ghost"
                  size="sm"
                  class="text-red-600 hover:text-red-700 hover:bg-red-50"
                  @click="deleteAttachment(attachment)"
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

        <!-- Comments Tab -->
        <div v-if="activeTab === 'comments'" class="space-y-6">
          <div>
            <h4 class="text-lg font-semibold text-slate-900">Comments</h4>
            <p class="text-sm text-slate-600">Discuss and collaborate on this task</p>
          </div>

          <!-- Chat Container -->
          <div class="bg-slate-50 rounded-lg border border-slate-200 overflow-hidden">
            <!-- Comments Chat -->
            <div v-if="comments.length === 0" class="text-center py-12">
              <svg
                class="mx-auto h-12 w-12 text-slate-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                />
              </svg>
              <h3 class="mt-4 text-lg font-medium text-slate-900">No comments yet</h3>
              <p class="mt-2 text-slate-600">Start the conversation by adding the first comment</p>
            </div>
            <div ref="commentsContainer" class="h-96 overflow-y-auto p-4">
              <div class="space-y-4">
                <div
                  v-for="comment in comments"
                  :key="comment.id"
                  :class="[
                    'flex items-start space-x-3',
                    isCurrentUser(comment.user?.id) ? 'justify-end' : 'justify-start',
                  ]"
                >
                  <div v-if="!isCurrentUser(comment.user?.id)" class="flex-shrink-0">
                    <div
                      class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-sm"
                    >
                      <span class="text-white text-xs font-semibold">
                        {{ comment.user?.name?.charAt(0).toUpperCase() }}
                      </span>
                    </div>
                  </div>
                  <div
                    :class="[
                      'max-w-xs lg:max-w-md px-4 py-2 rounded-lg shadow-sm',
                      isCurrentUser(comment.user?.id)
                        ? 'bg-blue-500 text-white rounded-br-sm'
                        : 'bg-white text-slate-900 rounded-bl-sm border border-slate-200',
                    ]"
                  >
                    <div
                      v-if="!isCurrentUser(comment.user?.id)"
                      class="flex items-center space-x-2 mb-1"
                    >
                      <p class="text-xs font-semibold text-slate-900">{{ comment.user?.name }}</p>
                      <span class="text-xs text-slate-500">
                        {{ formatDate(comment.created_at) }}
                      </span>
                    </div>
                    <p
                      :class="[
                        'text-sm leading-relaxed',
                        isCurrentUser(comment.user?.id) ? 'text-white' : 'text-slate-700',
                      ]"
                    >
                      {{ comment.comment }}
                    </p>
                    <div
                      v-if="isCurrentUser(comment.user?.id)"
                      class="text-xs text-blue-100 mt-1 text-right"
                    >
                      {{ formatDate(comment.created_at) }}
                    </div>
                  </div>
                  <div v-if="isCurrentUser(comment.user?.id)" class="flex-shrink-0">
                    <div
                      class="w-8 h-8 bg-gradient-to-br from-green-500 to-blue-600 rounded-full flex items-center justify-center shadow-sm"
                    >
                      <span class="text-white text-xs font-semibold">
                        {{ comment.user?.name?.charAt(0).toUpperCase() }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Add Comment -->
            <div class="border-t border-slate-200 p-4 bg-white">
              <div class="flex space-x-3">
                <div class="flex-shrink-0">
                  <div
                    class="w-8 h-8 bg-gradient-to-br from-green-500 to-blue-600 rounded-full flex items-center justify-center shadow-sm"
                  >
                    <span class="text-white text-xs font-semibold">
                      {{ currentUser?.name?.charAt(0).toUpperCase() }}
                    </span>
                  </div>
                </div>
                <div class="flex-1">
                  <BaseTextarea
                    v-model="newComment"
                    placeholder="Type a message..."
                    :rows="2"
                    class="bg-slate-50 border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                  />
                  <div class="flex justify-end mt-2">
                    <BaseButton
                      @click="addComment"
                      :loading="isAddingComment"
                      :disabled="!newComment.trim()"
                      size="sm"
                    >
                      <svg
                        class="h-4 w-4 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                        />
                      </svg>
                      Send
                    </BaseButton>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Upload Modal -->
    <BaseModal
      :is-open="showUploadModal"
      title="Upload Attachment"
      @close="showUploadModal = false"
    >
      <div class="space-y-6">
        <div
          class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 cursor-pointer group"
          @click="$refs.fileInput?.click()"
          @dragover.prevent
          @drop.prevent="handleFileDrop"
        >
          <div
            class="mx-auto h-16 w-16 bg-slate-100 rounded-full flex items-center justify-center group-hover:bg-blue-100 transition-colors duration-200"
          >
            <svg
              class="h-8 w-8 text-slate-400 group-hover:text-blue-500 transition-colors duration-200"
              stroke="currentColor"
              fill="none"
              viewBox="0 0 48 48"
            >
              <path
                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></path>
            </svg>
          </div>
          <h3 class="mt-4 text-lg font-semibold text-slate-900">Upload files</h3>
          <p class="mt-2 text-slate-600">Drag and drop files here, or click to browse</p>
          <p class="mt-1 text-sm text-slate-500">Support for images, documents, and archives</p>
        </div>
        <input ref="fileInput" type="file" class="hidden" @change="handleFileSelect" multiple />
        <div v-if="selectedFiles.length > 0" class="space-y-3">
          <div class="flex items-center justify-between">
            <h4 class="text-sm font-semibold text-slate-900">Selected files</h4>
            <span class="text-xs text-slate-500">{{ selectedFiles.length }} file(s)</span>
          </div>
          <div class="space-y-2 max-h-40 overflow-y-auto">
            <div
              v-for="(file, index) in selectedFiles"
              :key="index"
              class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-200"
            >
              <div class="flex items-center space-x-3 min-w-0 flex-1">
                <div class="flex-shrink-0">
                  <svg
                    class="h-5 w-5 text-slate-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                  </svg>
                </div>
                <div class="min-w-0 flex-1">
                  <p class="text-sm font-medium text-slate-900 truncate">{{ file.name }}</p>
                  <p class="text-xs text-slate-500">{{ formatFileSize(file.size) }}</p>
                  <div v-if="isUploading && uploadProgress[file.name + file.size]" class="mt-1">
                    <div class="w-full bg-slate-200 rounded-full h-1.5">
                      <div
                        class="bg-blue-600 h-1.5 rounded-full transition-all duration-300"
                        :style="{ width: uploadProgress[file.name + file.size] + '%' }"
                      ></div>
                    </div>
                    <p class="text-xs text-blue-600 mt-0.5">
                      {{ uploadProgress[file.name + file.size] }}%
                    </p>
                  </div>
                </div>
              </div>
              <BaseButton
                variant="ghost"
                size="sm"
                class="text-red-600 hover:text-red-700 hover:bg-red-50"
                @click="removeFile(index)"
              >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </BaseButton>
            </div>
          </div>
        </div>
      </div>

      <template #footer>
        <div class="flex justify-end space-x-3">
          <BaseButton variant="outline" @click="showUploadModal = false"> Cancel </BaseButton>
          <BaseButton
            @click="uploadFiles"
            :loading="isUploading"
            :disabled="selectedFiles.length === 0"
            variant="primary"
          >
            <svg
              v-if="!isUploading"
              class="h-4 w-4 mr-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
              />
            </svg>
            {{ isUploading ? "Uploading..." : "Upload Files" }}
          </BaseButton>
        </div>
      </template>
    </BaseModal>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, onUnmounted, nextTick, TransitionGroup, watch } from "vue";
import type { Task, TaskAttachment, TaskComment } from "@/types";
import { useTaskStore } from "@/stores/tasks";
import { useToastStore } from "@/stores/toast";
import { useAuthStore } from "@/stores/auth";
import BaseButton from "@/components/base/Button.vue";
import BaseTextarea from "@/components/base/Textarea.vue";
import BaseModal from "@/components/base/Modal.vue";

interface Props {
  task: Task;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  edit: [task: Task];
  close: [];
}>();

const taskStore = useTaskStore();
const toastStore = useToastStore();
const authStore = useAuthStore();

const activeTab = ref("attachments");
const showUploadModal = ref(false);
const newComment = ref("");
const isAddingComment = ref(false);
const isUploading = ref(false);
const selectedFiles = ref<File[]>([]);
const uploadProgress = ref<{ [key: string]: number }>({});
const commentsContainer = ref<HTMLElement>();

const tabs = [
  { id: "attachments", name: "Attachments" },
  { id: "comments", name: "Comments" },
];

const attachments = computed(() => props.task.attachments || []);
const comments = computed(() => props.task.comments || []);

const currentUser = computed(() => authStore.user);

const isCurrentUser = (userId: number | undefined) => {
  return currentUser.value?.id === userId;
};

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
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const formatFileSize = (bytes: number) => {
  if (bytes === 0) return "0 Bytes";
  const k = 1024;
  const sizes = ["Bytes", "KB", "MB", "GB"];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};

const addComment = async () => {
  if (!newComment.value.trim()) return;

  isAddingComment.value = true;
  try {
    await taskStore.createComment(props.task.id, newComment.value);
    newComment.value = "";
    toastStore.success("Comment added successfully");

    // Auto-scroll to bottom after adding comment
    await nextTick();
    if (commentsContainer.value) {
      commentsContainer.value.scrollTop = commentsContainer.value.scrollHeight;
    }
  } catch (error) {
    toastStore.error("Failed to add comment");
  } finally {
    isAddingComment.value = false;
  }
};

const fileInput = ref<{ click: () => void } | null>(null);

const handleFileSelect = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files) {
    selectedFiles.value = Array.from(target.files);
  }
};

const handleFileDrop = (event: DragEvent) => {
  if (event.dataTransfer?.files) {
    selectedFiles.value = Array.from(event.dataTransfer.files);
  }
};

const removeFile = (index: number) => {
  selectedFiles.value.splice(index, 1);
};

const uploadFiles = async () => {
  if (selectedFiles.value.length === 0) return;

  isUploading.value = true;
  uploadProgress.value = {};

  try {
    for (const file of selectedFiles.value) {
      const fileKey = file.name + file.size;
      uploadProgress.value[fileKey] = 0;

      await taskStore.uploadAttachment(props.task.id, file, (progress) => {
        uploadProgress.value[fileKey] = progress;
      });
    }
    selectedFiles.value = [];
    showUploadModal.value = false;
    toastStore.success("Files uploaded successfully");
  } catch (error) {
    toastStore.error("Failed to upload files");
  } finally {
    isUploading.value = false;
    uploadProgress.value = {};
  }
};

const downloadAttachment = async (attachment: TaskAttachment) => {
  try {
    await taskStore.downloadAttachment(attachment.id, attachment.file_name);
  } catch (error) {
    toastStore.error("Failed to download file");
  }
};

const deleteAttachment = async (attachment: TaskAttachment) => {
  if (confirm("Are you sure you want to delete this attachment?")) {
    try {
      await taskStore.deleteAttachment(attachment.id);
      toastStore.success("Attachment deleted successfully");
    } catch (error) {
      toastStore.error("Failed to delete attachment");
    }
  }
};

onMounted(() => {
  taskStore.fetchComments(props.task.id);
});
</script>

<style scoped>
.comment-enter-active,
.comment-leave-active {
  transition: all 0.3s ease-out;
}

.comment-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.comment-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}

.comment-move {
  transition: transform 0.3s ease-out;
}
</style>
