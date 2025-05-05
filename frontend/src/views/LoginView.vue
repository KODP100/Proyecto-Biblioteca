<script setup>
import { ref, computed } from "vue";
import axios from "axios";

// Variables reactivas para controlar el estado del icono
const isPasswordVisible = ref(false);

const inputType = computed(() =>
  isPasswordVisible.value ? "text" : "password"
);
const iconClass = computed(
  () => (isPasswordVisible.value ? "bx bx-show" : "bx bx-hide")
);

// Alternar visibilidad de la contraseña
const togglePasswordVisibility = () => {
  isPasswordVisible.value = !isPasswordVisible.value;
};

// Variables reactivas para el formulario y mensajes de error
const email = ref("");
const password = ref("");
const errorMessage = ref("");
const isLoading = ref(false);

// URL del endpoint de login
const loginUrl = import.meta.env.VITE_API_URL + "/auth/login";

// Login
const login = async () => {
  isLoading.value = true;
  errorMessage.value = "";

  try {
    const response = await axios.post(loginUrl, {
      email: email.value,
      password: password.value,
    });

    const { access_token, token_type } = response.data;
    localStorage.setItem("token", access_token);
    localStorage.setItem("token_type", token_type);

    window.location.href = "/home";
  } catch (error) {
    if (error.response?.data?.error) {
      errorMessage.value = "Error en el inicio de sesión";
    } else {
      errorMessage.value = error.message || "Error en la conexión";
    }
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
     

      <form @submit.prevent="login">
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium mb-1">Correo electrónico</label>
          <input
            v-model="email"
            required
            type="email"
            id="email"
            name="email"
            placeholder="Ingresa tu correo electrónico"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            autofocus
          />
        </div>

        <div class="mb-4">
          <label for="password" class="block text-sm font-medium mb-1">Contraseña</label>
          <div class="relative">
            <input
              :type="inputType"
              id="password"
              v-model="password"
              required
              placeholder="••••••••"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"
            />
            <span
              class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 cursor-pointer"
              @click="togglePasswordVisibility"
            >
              <i :class="iconClass"></i>
            </span>
          </div>
          <div class="mt-2 text-sm">
            <a href="#" class="text-blue-600 hover:underline">¿Olvidaste tu contraseña?</a>
          </div>
        </div>

        <div class="mb-4">
          <button
            type="submit"
            :disabled="isLoading"
            class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition flex items-center justify-center"
          >
            <span v-if="isLoading" class="spinner-border animate-spin w-5 h-5 border-2 border-white rounded-full mr-2"></span>
            <span v-if="!isLoading">Ingresar</span>
          </button>
        </div>

        <div v-if="errorMessage" class="mb-4">
          <div class="bg-red-100 text-red-700 px-4 py-2 rounded-md flex justify-between items-center">
            <span>{{ errorMessage }}</span>
            <button class="text-red-500 hover:text-red-700" @click="errorMessage = ''">
              &times;
            </button>
          </div>
        </div>

        <p class="text-center text-sm text-gray-600">
          ¿Aún no tienes cuenta?
          <router-link to="/register" class="text-blue-600 hover:underline">Crea una gratis</router-link>
        </p>
      </form>
    </div>
  </div>
</template>

<style scoped>
.spinner-border {
  border-top-color: transparent;
  border-left-color: transparent;
  border-right-color: white;
  border-bottom-color: white;
}
</style>

  