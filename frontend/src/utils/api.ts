import axios from "axios";
import type {
  AuthResponse,
  ApiResponse,
  PaginatedResponse,
  Task,
  TaskComment,
  TaskAttachment,
  User,
} from "@/types";

const api = axios.create({
  baseURL: "http://localhost:8000/api",
  headers: {
    "Content-Type": "application/json",
  },
});

// Request interceptor to add auth token
api.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Response interceptor to handle errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      window.location.href = "/login";
    }
    return Promise.reject(error);
  }
);

export const authApi = {
  login: (email: string, password: string): Promise<AuthResponse> =>
    api.post("/login", { email, password }).then((res) => res.data),

  logout: (): Promise<void> =>
    api.post("/logout").then(() => {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
    }),

  me: (): Promise<User> => api.get("/user").then((res) => res.data),
};

export const taskApi = {
  getTasks: (params?: {
    page?: number;
    per_page?: number;
    status?: string;
    priority?: string;
    assigned_user_id?: number;
    created_by?: number;
    search?: string;
    sort_by?: string;
    sort_direction?: "asc" | "desc";
  }): Promise<PaginatedResponse<Task>> => api.get("/tasks", { params }).then((res) => res.data),

  createTask: (
    task: Omit<
      Task,
      "id" | "created_at" | "updated_at" | "creator" | "assigned_user" | "attachments" | "comments"
    >
  ): Promise<Task> => api.post("/tasks", task).then((res) => res.data),

  updateTask: (id: number, task: Partial<Task>): Promise<Task> =>
    api.put(`/tasks/${id}`, task).then((res) => res.data),

  deleteTask: (id: number): Promise<void> => api.delete(`/tasks/${id}`),

  getTask: (id: number): Promise<Task> => api.get(`/tasks/${id}`).then((res) => res.data),
};

export const attachmentApi = {
  upload: (
    taskId: number,
    file: File,
    onProgress?: (progress: number) => void
  ): Promise<TaskAttachment> => {
    const formData = new FormData();
    formData.append("file", file);
    return api
      .post(`/tasks/${taskId}/attachments`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
        onUploadProgress: (progressEvent) => {
          if (onProgress && progressEvent.total) {
            const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
            onProgress(percentCompleted);
          }
        },
      })
      .then((res) => res.data);
  },

  download: (id: number): Promise<Blob> =>
    api.get(`/attachments/${id}/download`, { responseType: "blob" }).then((res) => res.data),

  delete: (id: number): Promise<void> => api.delete(`/attachments/${id}`),
};

export const commentApi = {
  getComments: (taskId: number): Promise<TaskComment[]> =>
    api.get(`/tasks/${taskId}/comments`).then((res) => res.data),

  createComment: (taskId: number, comment: string): Promise<TaskComment> =>
    api.post(`/tasks/${taskId}/comments`, { comment }).then((res) => res.data),

  updateComment: (id: number, comment: string): Promise<TaskComment> =>
    api.put(`/comments/${id}`, { comment }).then((res) => res.data),

  deleteComment: (id: number): Promise<void> => api.delete(`/comments/${id}`),
};

export default api;
