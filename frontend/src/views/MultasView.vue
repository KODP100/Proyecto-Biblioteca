<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Estados y refs
const mostrarFormulario = ref(false);
const rangoDias = ref('');
const monto = ref('');
const isLoading = ref(false);
const errorMessage = ref('');
const multas = ref([]);
const isEditing = ref(false);
const multaEditandoId = ref(null);

const API_URL = import.meta.env.VITE_API_URL + '/multas';

// Obtener multas
const fetchMultas = async () => {
  try {
    const response = await axios.get(API_URL, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    });
    multas.value = response.data;
  } catch (error) {
    errorMessage.value = 'Error al cargar multas';
  }
};

// Crear o actualizar multa
const guardarMulta = async () => {
  isLoading.value = true;
  errorMessage.value = '';

  const datos = {
    rango_dias: rangoDias.value,
    monto: monto.value,
  };

  try {
    if (isEditing.value) {
      await axios.put(`${API_URL}/${multaEditandoId.value}`, datos, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      });
    } else {
      await axios.post(API_URL, datos, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      });
    }

    await fetchMultas();
    limpiarFormulario();
    mostrarFormulario.value = false;
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Error inesperado';
  } finally {
    isLoading.value = false;
  }
};

// Eliminar multa
const eliminarMulta = async (id) => {
  if (!confirm('¿Estás seguro de eliminar esta multa?')) return;
  try {
    await axios.delete(`${API_URL}/${id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    });
    await fetchMultas();
  } catch (error) {
    alert('Error al eliminar la multa');
  }
};

// Editar multa
const editarMulta = (multa) => {
  rangoDias.value = multa.rango_dias;
  monto.value = multa.monto;
  isEditing.value = true;
  multaEditandoId.value = multa.id;
  mostrarFormulario.value = true;
};

// Cancelar edición
const cancelarEdicion = () => {
  limpiarFormulario();
  mostrarFormulario.value = false;
};

const limpiarFormulario = () => {
  rangoDias.value = '';
  monto.value = '';
  isEditing.value = false;
  multaEditandoId.value = null;
  errorMessage.value = '';
};

onMounted(() => {
  fetchMultas();
});
</script>

<template>
  <div class="w-full p-6 mt-4">
    <!-- Botón agregar -->
    <div class="mb-4">
      <button
        @click="mostrarFormulario = !mostrarFormulario"
        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
      >
        {{ mostrarFormulario ? 'Ocultar Formulario' : 'Agregar Multa' }}
      </button>
    </div>

    <!-- Formulario -->
    <form
      v-if="mostrarFormulario"
      @submit.prevent="guardarMulta"
      class="bg-white p-6 rounded shadow mb-6"
    >
      <div class="mb-4">
        <label class="block mb-1">Rango de días</label>
        <input
          v-model="rangoDias"
          type="number"
          required
          class="w-full border px-3 py-2 rounded"
        />
      </div>

      <div class="mb-4">
        <label class="block mb-1">Monto</label>
        <input
          v-model="monto"
          type="number"
          step="0.01"
          required
          class="w-full border px-3 py-2 rounded"
        />
      </div>

      <div class="flex gap-2">
        <button
          type="submit"
          :disabled="isLoading"
          class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
        >
          <span v-if="isEditing">Actualizar</span>
          <span v-else>Guardar</span>
        </button>

        <button
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

    <!-- Tabla -->
    <h2 class="text-xl font-semibold mb-4">Multas Registradas</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-200 bg-white shadow rounded">
        <thead>
          <tr class="bg-gray-100 text-left">
            <th class="border px-4 py-2">ID</th>
            <th class="border px-4 py-2">Rango de Días</th>
            <th class="border px-4 py-2">Monto</th>
            <th class="border px-4 py-2 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="multa in multas" :key="multa.id" class="hover:bg-gray-50">
            <td class="border px-4 py-2">{{ multa.id }}</td>
            <td class="border px-4 py-2">{{ multa.rango_dias }}</td>
            <td class="border px-4 py-2">{{ parseFloat(multa.monto).toFixed(2) }}</td>
            <td class="border px-4 py-2">
              <div class="flex justify-center gap-2">
                <button
                  class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded"
                  @click="editarMulta(multa)"
                >
                  Editar
                </button>
                <button
                  class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded"
                  @click="eliminarMulta(multa.id)"
                >
                  Eliminar
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
