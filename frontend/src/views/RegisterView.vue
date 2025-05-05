<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router"; // para redireccionar

const router = useRouter();

// Datos del formulario
const email = ref("");
const name = ref("");
const rol = ref("");
const password = ref("");

// Estados
const isLoading = ref(false);
const errorMessage = ref("");

// URL del endpoint
const registerUrl = import.meta.env.VITE_API_URL + "/auth/register";

// Función para enviar datos al backend
const register = async () => {
  isLoading.value = true;
  errorMessage.value = "";

  try {
    await axios.post(registerUrl, {
      email: email.value,
      name: name.value,
      rol: rol.value,
      password: password.value,
    });

    // Redirige al login después del registro exitoso
    router.push("/");
  } catch (error) {
    if (error.response && error.response.data?.message) {
      errorMessage.value = error.response.data.message;
    } else {
      errorMessage.value = "Ocurrió un error durante el registro.";
    }
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">
      <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Crear una cuenta</h2>

      <!-- Mensaje de error -->
      <div v-if="errorMessage" class="mb-4 text-red-700 bg-red-100 p-3 rounded">
        {{ errorMessage }}
      </div>

      <form @submit.prevent="register" class="space-y-4">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
          <input
            id="name"
            v-model="name"
            type="text"
            required
            class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-indigo-200 focus:outline-none"
          />
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
          <input
            id="email"
            v-model="email"
            type="email"
            required
            class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-indigo-200 focus:outline-none"
          />
        </div>

        <div>
          <label for="rol" class="block text-sm font-medium text-gray-700">Rol</label>
          <select
            id="rol"
            v-model="rol"
            required
            class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-indigo-200 focus:outline-none"
          >
            <option value="">Selecciona un rol</option>
            <option value="empleado">Empleado</option>
            <option value="solicitante">Solicitante</option>
          </select>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
          <input
            id="password"
            v-model="password"
            type="password"
            required
            class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring focus:ring-indigo-200 focus:outline-none"
          />
        </div>

        <button
          type="submit"
          :disabled="isLoading"
          class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-md transition duration-300"
        >
          <span v-if="isLoading" class="animate-pulse">Registrando...</span>
          <span v-else>Registrarse</span>
        </button>
      </form>

      <p class="text-center text-sm text-gray-600 mt-4">
        ¿Ya tienes una cuenta?
        <router-link to="/login" class="text-indigo-600 hover:underline font-medium">Inicia sesión</router-link>
      </p>
    </div>
  </div>
</template>
