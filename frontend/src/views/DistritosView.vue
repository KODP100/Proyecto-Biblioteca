<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Variables
const distritos = ref([])
const nombreDistrito = ref('')
const departamentoId = ref('')
const departamentos = ref([])

const isEditing = ref(false)
const distritoEditandoId = ref(null)
const isLoading = ref(false)
const errorMessage = ref('')

// URLs
const API_BASE = import.meta.env.VITE_API_URL
const API_DISTRITOS = `${API_BASE}/distritos`
const API_DEPARTAMENTOS = `${API_BASE}/departamentos`

// Headers
const getHeaders = () => ({
  Authorization: `Bearer ${localStorage.getItem('token')}`,
})

// Cargar distritos
const fetchDistritos = async () => {
  try {
    const res = await axios.get(API_DISTRITOS, { headers: getHeaders() })
    distritos.value = res.data
  } catch (error) {
    errorMessage.value = 'Error al cargar distritos'
  }
}

// Cargar departamentos (para el select)
const fetchDepartamentos = async () => {
  try {
    const res = await axios.get(API_DEPARTAMENTOS, { headers: getHeaders() })
    departamentos.value = res.data
  } catch (error) {
    errorMessage.value = 'Error al cargar departamentos'
  }
}

// Guardar o actualizar
const guardarDistrito = async () => {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const payload = {
      nombre_distrito: nombreDistrito.value,
      id_departamento: departamentoId.value,
    }

    if (isEditing.value) {
      await axios.put(`${API_DISTRITOS}/${distritoEditandoId.value}`, payload, {
        headers: getHeaders(),
      })
    } else {
      await axios.post(API_DISTRITOS, payload, {
        headers: getHeaders(),
      })
    }

    await fetchDistritos()
    cancelarEdicion()
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Error al guardar'
  } finally {
    isLoading.value = false
  }
}

// Eliminar
const eliminarDistrito = async (id) => {
  if (!confirm('¿Eliminar este distrito?')) return
  try {
    await axios.delete(`${API_DISTRITOS}/${id}`, {
      headers: getHeaders(),
    })
    await fetchDistritos()
  } catch (error) {
    alert('Error al eliminar distrito')
  }
}

// Editar
const editarDistrito = (distrito) => {
  nombreDistrito.value = distrito.nombre_distrito
  departamentoId.value = distrito.id_departamento
  distritoEditandoId.value = distrito.id
  isEditing.value = true
}

// Cancelar edición
const cancelarEdicion = () => {
  nombreDistrito.value = ''
  departamentoId.value = ''
  isEditing.value = false
  distritoEditandoId.value = null
}

// Inicial
onMounted(() => {
  fetchDepartamentos()
  fetchDistritos()
})
</script>

<template>
  <div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <form @submit.prevent="guardarDistrito">
      <div class="mb-4">
        <label class="block mb-1">Nombre del distrito</label>
        <input
          v-model="nombreDistrito"
          type="text"
          required
          class="w-full border px-3 py-2 rounded"
        />
      </div>

      <div class="mb-4">
        <label class="block mb-1">Departamento</label>
        <select v-model="departamentoId" required class="w-full border px-3 py-2 rounded">
          <option value="" disabled>Seleccione un departamento</option>
          <option v-for="d in departamentos" :key="d.id" :value="d.id">
            {{ d.nombre_depto }}
          </option>
        </select>
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

    <h2 class="text-lg font-semibold mb-4">Distritos</h2>
    <table class="w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="border px-4 py-2 text-left">ID</th>
          <th class="border px-4 py-2 text-left">Nombre</th>
          <th class="border px-4 py-2 text-left">Departamento</th>
          <th class="border px-4 py-2 text-left">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="distrito in distritos" :key="distrito.id_distrito">
          <td class="border px-4 py-2">{{ distrito.id_distrito }}</td>
          <td class="border px-4 py-2">{{ distrito.nombre_distrito }}</td>
          <td class="border px-4 py-2">
            {{
              departamentos.find((d) => d.id === distrito.id_departamento)?.nombre_depto || '—'
            }}
          </td>
          <td class="border px-4 py-2 flex gap-2">
            <button class="text-blue-600 hover:underline" @click="editarDistrito(distrito)">
              Editar
            </button>
            <button class="text-red-600 hover:underline" @click="eliminarDistrito(distrito.id_distrito)">
              Eliminar
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

