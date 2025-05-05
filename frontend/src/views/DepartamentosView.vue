<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Variables
const nombreDepto = ref('');
const isLoading = ref(false);
const errorMessage = ref('');
const departamentos = ref([]);
const isEditing = ref(false);
const departamentoEditandoId = ref(null);

// URL base
const API_URL = import.meta.env.VITE_API_URL + '/departamentos';

// Cargar departamentos
const fetchDepartamentos = async () => {
  try {
    const response = await axios.get(API_URL, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    });
    departamentos.value = response.data;
  } catch (error) {
    errorMessage.value = 'Error al cargar departamentos';
  }
};

// Crear o actualizar departamento
const guardarDepartamento = async () => {
  isLoading.value = true;
  errorMessage.value = '';

  try {
    if (isEditing.value) {
      // EDITAR
      await axios.put(
        `${API_URL}/${departamentoEditandoId.value}`,
        { nombre_depto: nombreDepto.value },
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
          },
        }
      );
    } else {
      // CREAR
      await axios.post(
        API_URL,
        { nombre_depto: nombreDepto.value },
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
          },
        }
      );
    }

    await fetchDepartamentos();
    nombreDepto.value = '';
    isEditing.value = false;
    departamentoEditandoId.value = null;
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Error inesperado';
  } finally {
    isLoading.value = false;
  }
};

// Eliminar
const eliminarDepartamento = async (id) => {
  if (!confirm('¿Estás seguro de eliminar este departamento?')) return;

  try {
    await axios.delete(`${API_URL}/${id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    });
    await fetchDepartamentos();
  } catch (error) {
    alert('Error al eliminar el departamento');
  }
};

// Editar
const editarDepartamento = (departamento) => {
  nombreDepto.value = departamento.nombre_depto;
  isEditing.value = true;
  departamentoEditandoId.value = departamento.id;
};

// Cancelar edición
const cancelarEdicion = () => {
  nombreDepto.value = '';
  isEditing.value = false;
  departamentoEditandoId.value = null;
};

// Inicial
onMounted(() => {
  fetchDepartamentos();
});
</script>

<template>
  <div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <form @submit.prevent="guardarDepartamento">
      <div class="mb-4">
        <label class="block mb-1">Nombre del departamento</label>
        <input
          v-model="nombreDepto"
          type="text"
          required
          class="w-full border px-3 py-2 rounded"
        />
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

    <h2 class="text-lg font-semibold mb-4">Departamentos</h2>
    <table class="w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="border px-4 py-2 text-left">ID</th>
          <th class="border px-4 py-2 text-left">Nombre</th>
          <th class="border px-4 py-2 text-left">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="depto in departamentos" :key="depto.id">
          <td class="border px-4 py-2">{{ depto.id }}</td>
          <td class="border px-4 py-2">{{ depto.nombre_depto }}</td>
          <td class="border px-4 py-2 flex gap-2">
            <button
              class="text-blue-600 hover:underline"
              @click="editarDepartamento(depto)"
            >
              Editar
            </button>
            <button
              class="text-red-600 hover:underline"
              @click="eliminarDepartamento(depto.id)"
            >
              Eliminar
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
