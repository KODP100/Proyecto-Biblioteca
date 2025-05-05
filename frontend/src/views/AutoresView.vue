<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Variables
const nombre = ref('');
const apellido = ref('');
const nacionalidad = ref('');
const editorialId = ref('');
const autores = ref([]);
const editoriales = ref([]);
const isLoading = ref(false);
const isEditing = ref(false);
const autorEditandoId = ref(null);
const errorMessage = ref('');

// API URLs
const API_URL = import.meta.env.VITE_API_URL + '/autores';
const EDITORIALES_URL = import.meta.env.VITE_API_URL + '/editoriales';

// Obtener autores
const fetchAutores = async () => {
  try {
    const response = await axios.get(API_URL, {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    });
    autores.value = response.data;
  } catch (error) {
    errorMessage.value = 'Error al cargar los autores';
  }
};

// Obtener editoriales
const fetchEditoriales = async () => {
  try {
    const response = await axios.get(EDITORIALES_URL, {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    });
    editoriales.value = response.data;
  } catch (error) {
    errorMessage.value = 'Error al cargar las editoriales';
  }
};

// Guardar o actualizar
const guardarAutor = async () => {
  isLoading.value = true;
  errorMessage.value = '';

  try {
    const data = {
      nombre: nombre.value,
      apellido: apellido.value,
      nacionalidad: nacionalidad.value,
      editorial_id: editorialId.value, // ✅ Campo corregido
    };

    if (isEditing.value) {
      await axios.put(`${API_URL}/${autorEditandoId.value}`, data, {
        headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
      });
    } else {
      await axios.post(API_URL, data, {
        headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
      });
    }

    await fetchAutores();
    cancelarEdicion();
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Error al guardar';
  } finally {
    isLoading.value = false;
  }
};

// Editar
const editarAutor = (autor) => {
  nombre.value = autor.nombre;
  apellido.value = autor.apellido;
  nacionalidad.value = autor.nacionalidad;
  editorialId.value = autor.editorial_id;
  isEditing.value = true;
  autorEditandoId.value = autor.id;
};

// Eliminar
const eliminarAutor = async (id) => {
  if (!confirm('¿Estás seguro de eliminar este autor?')) return;

  try {
    await axios.delete(`${API_URL}/${id}`, {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
    });
    await fetchAutores();
  } catch (error) {
    alert('Error al eliminar el autor');
  }
};

// Cancelar
const cancelarEdicion = () => {
  nombre.value = '';
  apellido.value = '';
  nacionalidad.value = '';
  editorialId.value = '';
  isEditing.value = false;
  autorEditandoId.value = null;
};

onMounted(() => {
  fetchAutores();
  fetchEditoriales();
});
</script>

<template>
  <div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <form @submit.prevent="guardarAutor">
      <div class="mb-4">
        <label class="block mb-1">Nombre</label>
        <input v-model="nombre" type="text" required class="w-full border px-3 py-2 rounded" />
      </div>
      <div class="mb-4">
        <label class="block mb-1">Apellido</label>
        <input v-model="apellido" type="text" required class="w-full border px-3 py-2 rounded" />
      </div>
      <div class="mb-4">
        <label class="block mb-1">Nacionalidad</label>
        <input v-model="nacionalidad" type="text" required class="w-full border px-3 py-2 rounded" />
      </div>
      <div class="mb-4">
        <label class="block mb-1">Editorial</label>
        <select v-model="editorialId" required class="w-full border px-3 py-2 rounded">
          <option value="">Seleccione una editorial</option>
          <option v-for="edit in editoriales" :key="edit.id" :value="edit.id">
            {{ edit.nombre }}
          </option>
        </select>
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

      <div v-if="errorMessage" class="mt-4 text-red-600">{{ errorMessage }}</div>
    </form>

    <hr class="my-6" />

    <h2 class="text-lg font-semibold mb-4">Autores</h2>
    <table class="w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="border px-4 py-2">ID</th>
          <th class="border px-4 py-2">Nombre</th>
          <th class="border px-4 py-2">Apellido</th>
          <th class="border px-4 py-2">Nacionalidad</th>
          <th class="border px-4 py-2">Editorial</th>
          <th class="border px-4 py-2">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="a in autores" :key="a.id">
          <td class="border px-4 py-2">{{ a.id }}</td>
          <td class="border px-4 py-2">{{ a.nombre }}</td>
          <td class="border px-4 py-2">{{ a.apellido }}</td>
          <td class="border px-4 py-2">{{ a.nacionalidad }}</td>
          <td class="border px-4 py-2">{{ a.editorial?.nombre || '—' }}</td>
          <td class="border px-4 py-2 flex gap-2">
            <button class="text-blue-600 hover:underline" @click="editarAutor(a)">Editar</button>
            <button class="text-red-600 hover:underline" @click="eliminarAutor(a.id)">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

