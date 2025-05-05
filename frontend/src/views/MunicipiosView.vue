<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const municipios = ref([])
const distritos = ref([])
const nombreMunicipio = ref('')
const distritoId = ref(null) // Cambiado a null

const isEditing = ref(false)
const municipioEditandoId = ref(null)
const isLoading = ref(false)
const errorMessage = ref('')

const API_BASE = import.meta.env.VITE_API_URL
const API_MUNICIPIOS = `${API_BASE}/municipios`
const API_DISTRITOS = `${API_BASE}/distritos`

const getHeaders = () => ({
  Authorization: `Bearer ${localStorage.getItem('token')}`,
})

const fetchMunicipios = async () => {
  try {
    const res = await axios.get(API_MUNICIPIOS, { headers: getHeaders() })
    municipios.value = res.data
  } catch (error) {
    console.error('Error fetchMunicipios:', error)
    errorMessage.value = 'Error al cargar municipios'
  }
}

const fetchDistritos = async () => {
  try {
    const res = await axios.get(API_DISTRITOS, { headers: getHeaders() })
    distritos.value = res.data
  } catch (error) {
    console.error('Error fetchDistritos:', error)
    errorMessage.value = 'Error al cargar distritos'
  }
}

const guardarMunicipio = async () => {
  try {
    isLoading.value = true
    errorMessage.value = ''

    if (!nombreMunicipio.value.trim()) {
      throw new Error('El nombre del municipio es obligatorio')
    }

    if (!distritoId.value) {
      throw new Error('Debe seleccionar un distrito')
    }

    const payload = {
      nombre_municipio: nombreMunicipio.value.trim(),
      id_distrito: Number(distritoId.value) // Convertimos explícitamente a número
    }

    console.log('Payload a enviar:', payload)

    if (isEditing.value) {
      await axios.put(
        `${API_MUNICIPIOS}/${municipioEditandoId.value}`,
        payload,
        { headers: getHeaders() }
      )
    } else {
      await axios.post(API_MUNICIPIOS, payload, { headers: getHeaders() })
    }

    await fetchMunicipios()
    cancelarEdicion()
  } catch (error) {
    console.error('Error guardarMunicipio:', error)
    errorMessage.value = error.response?.data?.message || error.message || 'Error al guardar municipio'
  } finally {
    isLoading.value = false
  }
}

const eliminarMunicipio = async (id) => {
  if (!confirm('¿Está seguro de eliminar este municipio?')) return

  try {
    await axios.delete(`${API_MUNICIPIOS}/${id}`, { headers: getHeaders() })
    await fetchMunicipios()
  } catch (error) {
    console.error('Error eliminarMunicipio:', error)
    errorMessage.value = 'Error al eliminar municipio'
  }
}

const editarMunicipio = (m) => {
  nombreMunicipio.value = m.nombre_municipio
  distritoId.value = Number(m.id_distrito) // Aseguramos que sea número
  municipioEditandoId.value = m.id
  isEditing.value = true
}

const cancelarEdicion = () => {
  nombreMunicipio.value = ''
  distritoId.value = null // Reseteamos a null
  municipioEditandoId.value = null
  isEditing.value = false
  errorMessage.value = ''
}

onMounted(() => {
  fetchMunicipios()
  fetchDistritos()
})
</script>

<template>
  <div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <form @submit.prevent="guardarMunicipio" class="space-y-4">
      <div class="mb-4">
        <label class="block mb-1">Nombre del municipio</label>
        <input 
          v-model="nombreMunicipio"
          type="text"
          required
          placeholder="Ingrese el nombre del municipio"
          class="w-full border px-3 py-2 rounded focus:outline-none focus:border-blue-500"
          :disabled="isLoading"
        />
      </div>

      <div class="mb-4">
        <label class="block mb-1">Distrito</label>
        <select 
          v-model="distritoId"
          required
          class="w-full border px-3 py-2 rounded focus:outline-none focus:border-blue-500"
          :disabled="isLoading"
        >
          <option :value="null">Seleccione un distrito</option>
          <option 
            v-for="d in distritos" 
            :key="d.id_distrito" 
            :value="Number(d.id_distrito)"
          >
            {{ d.nombre_distrito }}
          </option>
        </select>
      </div>

      <div class="flex gap-2">
        <button 
          type="submit" 
          :disabled="isLoading" 
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50"
        >
          {{ isLoading ? 'Guardando...' : (isEditing ? 'Actualizar' : 'Crear') }}
        </button>
        <button
          v-if="isEditing"
          type="button"
          @click="cancelarEdicion"
          :disabled="isLoading"
          class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 disabled:opacity-50"
        >
          Cancelar
        </button>
      </div>

      <div v-if="errorMessage" class="mt-4 p-3 bg-red-100 text-red-600 rounded">
        {{ errorMessage }}
      </div>
    </form>

    <hr class="my-6" />

    <h2 class="text-lg font-semibold mb-4">Lista de Municipios</h2>
    <div class="overflow-x-auto">
      <table class="w-full border">
        <thead>
          <tr class="bg-gray-100">
            <th class="border px-4 py-2 text-left">ID</th>
            <th class="border px-4 py-2 text-left">Nombre</th>
            <th class="border px-4 py-2 text-left">Distrito</th>
            <th class="border px-4 py-2 text-left">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="m in municipios" :key="m.id">
            <td class="border px-4 py-2">{{ m.id }}</td>
            <td class="border px-4 py-2">{{ m.nombre_municipio }}</td>
            <td class="border px-4 py-2">
              {{ distritos.find(d => d.id_distrito === m.id_distrito)?.nombre_distrito || '—' }}
            </td>
            <td class="border px-4 py-2 flex gap-2">
              <button 
                class="text-blue-600 hover:underline"
                @click="editarMunicipio(m)"
                :disabled="isLoading"
              >
                Editar
              </button>
              <button 
                class="text-red-600 hover:underline"
                @click="eliminarMunicipio(m.id)"
                :disabled="isLoading"
              >
                Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>