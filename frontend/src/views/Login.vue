<template>
  <div
    class="min-h-screen flex items-center justify-center gradient-primary py-12 px-4 sm:px-6 lg:px-8"
  >
    <div class="max-w-md w-full">
      <!-- Logo/Brand Section -->
      <div class="text-center mb-8">
        <div
          class="mx-auto h-16 w-16 bg-white rounded-2xl shadow-xl flex items-center justify-center mb-4"
        >
          <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
            />
          </svg>
        </div>
        <h2 class="text-3xl font-bold text-white mb-2">Welcome Back</h2>
        <p class="text-blue-100 text-lg">Sign in to your Task Management account</p>
      </div>

      <!-- Login Form -->
      <div class="bg-white rounded-2xl shadow-2xl p-8 animate-fade-in">
        <form class="space-y-6" @submit.prevent="handleLogin">
          <div>
            <BaseInput
              v-model="form.email"
              type="email"
              label="Email Address"
              placeholder="Enter your email"
              :error="errors.email"
              required
              id="email"
            />
          </div>

          <div>
            <BaseInput
              v-model="form.password"
              type="password"
              label="Password"
              placeholder="Enter your password"
              :error="errors.password"
              required
              id="password"
            />
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                id="remember-me"
                name="remember-me"
                type="checkbox"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded"
              />
              <label for="remember-me" class="ml-2 block text-sm text-slate-700">
                Remember me
              </label>
            </div>

            <div class="text-sm">
              <a href="#" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                Forgot password?
              </a>
            </div>
          </div>

          <div>
            <BaseButton
              type="submit"
              variant="primary"
              :loading="authStore.isLoading"
              full-width
              size="lg"
            >
              <span v-if="!authStore.isLoading">Sign In</span>
              <span v-else>Signing In...</span>
            </BaseButton>
          </div>
        </form>

        <!-- Divider -->
        <div class="mt-6">
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-slate-300" />
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-white text-slate-500">Don't have an account?</span>
            </div>
          </div>

          <div class="mt-6">
            <BaseButton variant="outline" full-width size="lg"> Create New Account </BaseButton>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="text-center mt-8">
        <p class="text-blue-100 text-sm">© 2024 Task Management. All rights reserved.</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { useToastStore } from "@/stores/toast";
import BaseInput from "@/components/base/Input.vue";
import BaseButton from "@/components/base/Button.vue";

const router = useRouter();
const authStore = useAuthStore();
const toastStore = useToastStore();

const form = reactive({
  email: "",
  password: "",
});

const errors = ref<Record<string, string>>({});

const handleLogin = async () => {
  errors.value = {};

  try {
    await authStore.login(form.email, form.password);
    toastStore.success("Login successful!");
    router.push("/dashboard");
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    } else {
      toastStore.error("Login failed. Please try again.");
    }
  }
};
</script>
