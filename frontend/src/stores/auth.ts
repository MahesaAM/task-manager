import { defineStore } from "pinia";
import { ref, computed } from "vue";
import type { User } from "@/types";
import { authApi } from "@/utils/api";
import { useTaskStore } from "./tasks";

export const useAuthStore = defineStore("auth", () => {
  const user = ref<User | null>(null);
  const token = ref<string | null>(null);
  const isLoading = ref(false);

  const isAuthenticated = computed(() => !!token.value && !!user.value);

  const setUser = (userData: User | null) => {
    user.value = userData;
    if (userData) {
      localStorage.setItem("user", JSON.stringify(userData));
    } else {
      localStorage.removeItem("user");
    }
  };

  const setToken = (tokenData: string | null) => {
    token.value = tokenData;
    if (tokenData) {
      localStorage.setItem("token", tokenData);
    } else {
      localStorage.removeItem("token");
    }
  };

  const initializeAuth = () => {
    const storedToken = localStorage.getItem("token");
    const storedUser = localStorage.getItem("user");

    if (storedToken) {
      setToken(storedToken);
    }

    if (storedUser) {
      try {
        setUser(JSON.parse(storedUser));
      } catch {
        setUser(null);
      }
    }
  };

  const login = async (email: string, password: string) => {
    isLoading.value = true;
    try {
      const response = await authApi.login(email, password);
      setToken(response.token);
      setUser(response.user);

      // Set up real-time listeners after login
      const taskStore = useTaskStore();
      taskStore.setupRealtimeListeners();

      return response;
    } finally {
      isLoading.value = false;
    }
  };

  const logout = async () => {
    isLoading.value = true;
    try {
      await authApi.logout();
    } catch {
      // Ignore logout errors
    } finally {
      setToken(null);
      setUser(null);
      isLoading.value = false;
    }
  };

  const fetchUser = async () => {
    if (!token.value) return;

    isLoading.value = true;
    try {
      const userData = await authApi.me();
      setUser(userData);
    } catch {
      setToken(null);
      setUser(null);
    } finally {
      isLoading.value = false;
    }
  };

  return {
    user,
    token,
    isLoading,
    isAuthenticated,
    initializeAuth,
    login,
    logout,
    fetchUser,
  };
});
