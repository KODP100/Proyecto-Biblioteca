<script setup>
import { ref } from "vue";
import axios from "axios";

const name = ref("");
const rol = ref("");
const logged = ref(false);

const meUrl = import.meta.env.VITE_API_URL + "/auth/me";

const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    "Content-Type": "application/json",
  },
});

apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  const tokenType = localStorage.getItem("token_type");
  if (token && tokenType) config.headers.Authorization = `${tokenType} ${token}`;
  return config;
});

const getUserProfile = async () => {
  try {
    const response = await apiClient.get(meUrl);
    localStorage.setItem("user", JSON.stringify(response.data));
    logged.value = true;
    name.value = response.data.name;
    rol.value = response.data.rol || "Usuario";
  } catch (error) {
    localStorage.clear();
    window.location.href = "/";
  }
};

getUserProfile();
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-800">Bienvenido, {{ name }}</h1>
    <p class="text-gray-600 mb-4">Rol: {{ rol }}</p>
    <p class="text-gray-700">Estás en el panel principal. Usa el menú lateral para navegar.</p>
  </div>
</template>
