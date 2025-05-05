<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Variables
const nombre = ref('');
const descripcion = ref('');
const estadoLibros = ref([]);
const isEditing = ref(false);
const estadoEditandoId = ref(null);
const isLoading = ref(false);
const errorMessage = ref('');

// URL de la API
const API_URL = import.meta.env.VITE_API_URL + '/estadolibros';

// Obtener lista
const fetchEstadoLibros = async () => {
  try {
    const response = await axios.get(API_URL, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    });
    estadoLibros.value = response.data;
  } catch (error) {
    errorMessage.value = 'Error al cargar estados de libros';
  }
};

// Guardar (crear o actualizar)
const guardarEstadoLibro = async () => {
  isLoading.value = true;
  errorMessage.value = '';

  try {
    const data = {
      nombre: nombre.value,
      descripcion: descripcion.value,
    };

    if (isEditing.value) {
      await axios.put(`${API_URL}/${estadoEditandoId.value}`, data, {
        headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
      });
    } else {
      await axios.post(API_URL, data, {
        headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
      });
    }

    await fetchEstadoLibros();
    cancelarEdicion();
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Error inesperado';
  } finally {
    isLoading.value = false;
  }
};

// Editar
const editarEstado = (estado) => {
  nombre.value = estado.nombre;
  descripcion.value = estado.descripcion;
  estadoEditandoId.value = estado.id;
  isEditing.value = true;
};

// Cancelar edición
const cancelarEdicion = () => {
  nombre.value = '';
  descripcion.value = '';
  isEditing.value = false;
  estadoEditandoId.value = null;
};

// Eliminar
const eliminarEstado = async (id) => {
  if (!confirm('¿Estás seguro de eliminar este estado?')) return;

  try {
    await axios.delete(`${API_URL}/${id}`, {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    });
    await fetchEstadoLibros();
  } catch (error) {
    alert('Error al eliminar el estado');
  }
};

// Cargar al inicio
onMounted(() => {
  fetchEstadoLibros();
});
</script>

<template>
  <div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <form @submit.prevent="guardarEstadoLibro">
      <div class="mb-4">
        <label class="block mb-1">Nombre del estado</label>
        <input
          v-model="nombre"
          type="text"
          required
          class="w-full border px-3 py-2 rounded"
        />
      </div>

      <div class="mb-4">
        <label class="block mb-1">Descripción</label>
        <textarea
          v-model="descripcion"
          rows="3"
          class="w-full border px-3 py-2 rounded"
        ></textarea>
      </div>

      <div class="flex gap-2">
        <button
          type="submit"
          :disabled="isLoading"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          <span v-if="isEditing">Actualizar</span>
          <span v-else>Crear</span>
        </button>

        <button
          v-if="isEditing"
          type="button"
          @click="cancelarEdicion"
          class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
        >
          Cancelar
        </button>
      </div>

      <div v-if="errorMessage" class="mt-4 text-red-600">
        {{ errorMessage }}
      </div>
    </form>

    <hr class="my-6" />

    <h2 class="text-lg font-semibold mb-4">Estados de Libros</h2>
    <table class="w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="border px-4 py-2 text-left">ID</th>
          <th class="border px-4 py-2 text-left">Nombre</th>
          <th class="border px-4 py-2 text-left">Descripción</th>
          <th class="border px-4 py-2 text-left">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="estado in estadoLibros" :key="estado.id">
          <td class="border px-4 py-2">{{ estado.id }}</td>
          <td class="border px-4 py-2">{{ estado.nombre }}</td>
          <td class="border px-4 py-2">{{ estado.descripcion }}</td>
          <td class="border px-4 py-2 flex gap-2">
            <button
              class="text-blue-600 hover:underline"
              @click="editarEstado(estado)"
            >
              Editar
            </button>
            <button
              class="text-red-600 hover:underline"
              @click="eliminarEstado(estado.id)"
            >
              Eliminar
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
