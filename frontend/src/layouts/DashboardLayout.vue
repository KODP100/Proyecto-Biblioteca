<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const user = ref(JSON.parse(localStorage.getItem("user")));
const logout = () => {
  localStorage.removeItem('token');
  localStorage.removeItem('token_type');
  localStorage.removeItem('user');
  router.push('/');
};
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-blue-800 text-white min-h-screen p-4">
      <h2 class="text-2xl font-bold mb-6">Panel</h2>
      <p class="mb-6">Hola, <span class="font-semibold">{{ user?.name }}</span></p>

      <nav class="flex flex-col gap-3">
        <router-link to="/home" class="hover:bg-blue-700 p-2 rounded">Inicio</router-link>
        <router-link to="/editoriales" class="hover:bg-blue-700 p-2 rounded">Editoriales</router-link>
        <router-link to="/libros" class="hover:bg-blue-700 p-2 rounded">Libros</router-link>
        <router-link to="/multas" class="hover:bg-blue-700 p-2 rounded">Multas</router-link>
        <router-link to="/prestamos" class="hover:bg-blue-700 p-2 rounded">Prestamos</router-link>
         <router-link to="/devoluciones" class="hover:bg-blue-700 p-2 rounded">Devoluciones</router-link>
        <button @click="logout" class="mt-auto bg-red-600 hover:bg-red-700 p-2 rounded text-white w-full text-left">
          Cerrar sesión
        </button>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 bg-white shadow-inner">
      <router-view />
    </main>
  </div>
</template>
