<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Estados reactivos
const editoriales = ref([])
const nombre = ref('')
const direccion = ref('')
const telefono = ref('')
const correo = ref('')
const isLoading = ref(false)
const errorMessage = ref('')
const isEditing = ref(false)
const editorialEditandoId = ref(null)
const mostrarFormulario = ref(false)

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
    mostrarFormulario.value = false
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Error inesperado al guardar'
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
  mostrarFormulario.value = true
}

// Limpiar
const limpiarFormulario = () => {
  nombre.value = ''
  direccion.value = ''
  telefono.value = ''
  correo.value = ''
  isEditing.value = false
  editorialEditandoId.value = null
}

onMounted(fetchEditoriales)
</script>

<template>
  <div class="max-w-7xl mx-auto p-6">
    <!-- Botón para mostrar formulario -->
    <div class="mb-4">
      <button
        @click="mostrarFormulario = !mostrarFormulario"
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"
      >
        {{ mostrarFormulario ? 'Ocultar Formulario' : 'Nueva Editorial' }}
      </button>
    </div>

    <!-- Formulario -->
    <div v-if="mostrarFormulario" class="bg-white p-6 rounded shadow mb-8">
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
            {{ isEditing ? 'Actualizar' : 'Crear' }}
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
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto bg-white rounded shadow">
      <table class="min-w-full border">
        <thead class="bg-gray-100">
          <tr>
            <th class="border px-4 py-2">ID</th>
            <th class="border px-4 py-2">Nombre</th>
            <th class="border px-4 py-2">Correo</th>
            <th class="border px-4 py-2">Teléfono</th>
            <th class="border px-4 py-2">Dirección</th>
            <th class="border px-4 py-2 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="editorial in editoriales" :key="editorial.id" class="hover:bg-gray-50">
            <td class="border px-4 py-2">{{ editorial.id }}</td>
            <td class="border px-4 py-2">{{ editorial.nombre }}</td>
            <td class="border px-4 py-2">{{ editorial.correo }}</td>
            <td class="border px-4 py-2">{{ editorial.telefono }}</td>
            <td class="border px-4 py-2">{{ editorial.direccion }}</td>
            <td class="border px-4 py-2 text-center">
              <div class="flex justify-center gap-2">
                <button @click="editarEditorial(editorial)" class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">
                  Editar
                </button>
                <button @click="eliminarEditorial(editorial.id)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
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
