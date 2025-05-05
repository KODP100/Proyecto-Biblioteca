<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Estado reactivo
const editoriales = ref([])
const nombre = ref('')
const direccion = ref('')
const telefono = ref('')
const correo = ref('')
const isLoading = ref(false)
const errorMessage = ref('')
const isEditing = ref(false)
const editorialEditandoId = ref(null)

// URL base
const API_URL = import.meta.env.VITE_API_URL + '/editoriales'

// Obtener editoriales
const fetchEditoriales = async () => {
  try {
    const res = await axios.get(API_URL, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    })
    editoriales.value = res.data
  } catch (error) {
    errorMessage.value = 'Error al cargar editoriales'
  }
}

// Guardar o actualizar
const guardarEditorial = async () => {
  isLoading.value = true
  errorMessage.value = ''

  const payload = {
    nombre: nombre.value,
    direccion: direccion.value,
    telefono: telefono.value,
    correo: correo.value
  }

  try {
    if (isEditing.value) {
      await axios.put(`${API_URL}/${editorialEditandoId.value}`, payload, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`
        }
      })
    } else {
      await axios.post(API_URL, payload, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`
        }
      })
    }

    await fetchEditoriales()
    limpiarFormulario()
  } catch (error) {
    errorMessage.value =
      error.response?.data?.message || 'Error inesperado al guardar'
  } finally {
    isLoading.value = false
  }
}

// Eliminar
const eliminarEditorial = async (id) => {
  if (!confirm('¿Deseas eliminar esta editorial?')) return

  try {
    await axios.delete(`${API_URL}/${id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    })
    await fetchEditoriales()
  } catch (error) {
    errorMessage.value = 'Error al eliminar'
  }
}

// Editar
const editarEditorial = (editorial) => {
  nombre.value = editorial.nombre
  direccion.value = editorial.direccion
  telefono.value = editorial.telefono
  correo.value = editorial.correo
  isEditing.value = true
  editorialEditandoId.value = editorial.id
}

// Limpiar formulario
const limpiarFormulario = () => {
  nombre.value = ''
  direccion.value = ''
  telefono.value = ''
  correo.value = ''
  isEditing.value = false
  editorialEditandoId.value = null
}

// Cargar editoriales al inicio
onMounted(fetchEditoriales)
</script>

<template>
  <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <form @submit.prevent="guardarEditorial">
      <h2 class="text-xl font-semibold mb-4">Formulario de Editorial</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block mb-1">Nombre *</label>
          <input v-model="nombre" type="text" required class="w-full border px-3 py-2 rounded" />
        </div>

        <div>
          <label class="block mb-1">Correo *</label>
          <input v-model="correo" type="email" required class="w-full border px-3 py-2 rounded" />
        </div>

        <div>
          <label class="block mb-1">Teléfono</label>
          <input v-model="telefono" type="text" class="w-full border px-3 py-2 rounded" />
        </div>

        <div>
          <label class="block mb-1">Dirección</label>
          <input v-model="direccion" type="text" class="w-full border px-3 py-2 rounded" />
        </div>
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
          @click="limpiarFormulario"
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

    <h2 class="text-lg font-semibold mb-4">Lista de Editoriales</h2>
    <table class="w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="border px-4 py-2 text-left">ID</th>
          <th class="border px-4 py-2 text-left">Nombre</th>
          <th class="border px-4 py-2 text-left">Correo</th>
          <th class="border px-4 py-2 text-left">Teléfono</th>
          <th class="border px-4 py-2 text-left">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="editorial in editoriales" :key="editorial.id">
          <td class="border px-4 py-2">{{ editorial.id }}</td>
          <td class="border px-4 py-2">{{ editorial.nombre }}</td>
          <td class="border px-4 py-2">{{ editorial.correo }}</td>
          <td class="border px-4 py-2">{{ editorial.telefono }}</td>
          <td class="border px-4 py-2 flex gap-2">
            <button class="text-blue-600 hover:underline" @click="editarEditorial(editorial)">
              Editar
            </button>
            <button class="text-red-600 hover:underline" @click="eliminarEditorial(editorial.id)">
              Eliminar
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
