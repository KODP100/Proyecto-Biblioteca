<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Variables
const nombreCategoria = ref('');
const descripcion = ref('');
const categorias = ref([]);
const isLoading = ref(false);
const isEditing = ref(false);
const categoriaEditandoId = ref(null);
const errorMessage = ref('');

// API
const API_URL = import.meta.env.VITE_API_URL + '/categorias';

// Obtener categorías
const fetchCategorias = async () => {
  try {
    const response = await axios.get(API_URL, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    });
    categorias.value = response.data;
  } catch (error) {
    errorMessage.value = 'Error al cargar las categorías';
  }
};

// Guardar o actualizar
const guardarCategoria = async () => {
  isLoading.value = true;
  errorMessage.value = '';

  try {
    const data = {
      nombre: nombreCategoria.value,
      descripcion: descripcion.value,
    };

    if (isEditing.value) {
      await axios.put(`${API_URL}/${categoriaEditandoId.value}`, data, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      });
    } else {
      await axios.post(API_URL, data, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      });
    }

    await fetchCategorias();
    cancelarEdicion();
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Error al guardar';
  } finally {
    isLoading.value = false;
  }
};

// Editar
const editarCategoria = (categoria) => {
  nombreCategoria.value = categoria.nombre;
  descripcion.value = categoria.descripcion || '';
  isEditing.value = true;
  categoriaEditandoId.value = categoria.id;
};

// Eliminar
const eliminarCategoria = async (id) => {
  if (!confirm('¿Estás seguro de eliminar esta categoría?')) return;

  try {
    await axios.delete(`${API_URL}/${id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    });
    await fetchCategorias();
  } catch (error) {
    alert('Error al eliminar la categoría');
  }
};

// Cancelar
const cancelarEdicion = () => {
  nombreCategoria.value = '';
  descripcion.value = '';
  isEditing.value = false;
  categoriaEditandoId.value = null;
};

onMounted(() => {
  fetchCategorias();
});
</script>

<template>
  <div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <form @submit.prevent="guardarCategoria">
      <div class="mb-4">
        <label class="block mb-1">Nombre de la categoría</label>
        <input
          v-model="nombreCategoria"
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
          class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
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

    <h2 class="text-lg font-semibold mb-4">Categorías</h2>
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
        <tr v-for="cat in categorias" :key="cat.id">
          <td class="border px-4 py-2">{{ cat.id }}</td>
          <td class="border px-4 py-2">{{ cat.nombre }}</td>
          <td class="border px-4 py-2">{{ cat.descripcion }}</td>
          <td class="border px-4 py-2 flex gap-2">
            <button
              class="text-blue-600 hover:underline"
              @click="editarCategoria(cat)"
            >
              Editar
            </button>
            <button
              class="text-red-600 hover:underline"
              @click="eliminarCategoria(cat.id)"
            >
              Eliminar
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
