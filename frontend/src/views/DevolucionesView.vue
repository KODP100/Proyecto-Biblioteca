<template>
  <div class="p-4 w-full overflow-auto">
    <h2 class="text-2xl font-bold mb-4">Gestión de Devoluciones</h2>

    <!-- Botón -->
    <div class="mb-4">
      <button @click="mostrarFormulario = !mostrarFormulario" class="bg-green-600 text-white px-4 py-2 rounded">
        {{ mostrarFormulario ? 'Ocultar Formulario' : 'Agregar Devolución' }}
      </button>
    </div>

    <!-- Formulario -->
    <form v-if="mostrarFormulario" @submit.prevent="guardarDevolucion" class="bg-white p-4 rounded shadow-md mb-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label>Préstamo</label>
          <select v-model="id_prestamo" required class="w-full border px-3 py-2 rounded">
            <option disabled value="">Seleccione un préstamo</option>
            <option v-for="prestamo in prestamos" :key="prestamo.id" :value="prestamo.id">
              {{ prestamo.libro?.titulo || 'Préstamo #' + prestamo.id }} - {{ prestamo.fecha_prestamo }}
            </option>
          </select>
        </div>

        <div>
          <label>Fecha de Devolución</label>
          <input v-model="fecha_devolucion" type="date" required class="w-full border px-3 py-2 rounded" />
        </div>

        <div>
          <label>Estado del Libro</label>
          <select v-model="estado_libro" class="w-full border px-3 py-2 rounded">
            <option value="Bueno">Bueno</option>
            <option value="Dañado">Dañado</option>
            <option value="Perdido">Perdido</option>
          </select>
        </div>

        <div>
          <label>Observaciones</label>
          <textarea v-model="observaciones" rows="3" class="w-full border px-3 py-2 rounded"></textarea>
        </div>
      </div>

      <div class="mt-4">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mr-2">
          {{ isEditing ? 'Actualizar' : 'Guardar' }}
        </button>
        <button v-if="isEditing" type="button" @click="cancelarEdicion" class="bg-gray-500 text-white px-4 py-2 rounded">Cancelar</button>
      </div>

      <p v-if="errorMessage" class="text-red-500 mt-2">{{ errorMessage }}</p>
    </form>

    <!-- Tabla -->
    <table class="w-full table-auto border">
      <thead class="bg-gray-100">
        <tr>
          <th class="border px-4 py-2">Préstamo</th>
          <th class="border px-4 py-2">Libro</th>
          <th class="border px-4 py-2">Fecha Devolución</th>
          <th class="border px-4 py-2">Estado Libro</th>
          <th class="border px-4 py-2">Observaciones</th>
          <th class="border px-4 py-2">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="devolucion in devoluciones" :key="devolucion.id">
          <td class="border px-4 py-2">#{{ devolucion.prestamo?.id }}</td>
          <td class="border px-4 py-2">{{ devolucion.prestamo?.libro?.titulo || '—' }}</td>
          <td class="border px-4 py-2">{{ devolucion.fecha_devolucion }}</td>
          <td class="border px-4 py-2">{{ devolucion.estado_libro || '—' }}</td>
          <td class="border px-4 py-2">{{ devolucion.observaciones || '—' }}</td>
          <td class="border px-4 py-2 space-x-2">
            <button @click="editarDevolucion(devolucion)" class="bg-yellow-400 px-3 py-1 rounded text-white">Editar</button>
            <button @click="eliminarDevolucion(devolucion.id)" class="bg-red-600 px-3 py-1 rounded text-white">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Paginación -->
    <div class="mt-4 flex justify-center items-center space-x-4" v-if="lastPage > 1">
      <button @click="fetchDevoluciones(currentPage - 1)" :disabled="currentPage === 1" class="px-4 py-2 bg-gray-200 rounded">Anterior</button>
      <span>Página {{ currentPage }} de {{ lastPage }}</span>
      <button @click="fetchDevoluciones(currentPage + 1)" :disabled="currentPage === lastPage" class="px-4 py-2 bg-gray-200 rounded">Siguiente</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const devoluciones = ref([])
const prestamos = ref([])

const id_prestamo = ref('')
const fecha_devolucion = ref('')
const estado_libro = ref('Bueno')
const observaciones = ref('')

const isEditing = ref(false)
const idEditando = ref(null)
const mostrarFormulario = ref(false)
const errorMessage = ref('')
const currentPage = ref(1)
const lastPage = ref(1)

const API = import.meta.env.VITE_API_URL
const getHeaders = () => ({ Authorization: `Bearer ${localStorage.getItem('token')}` })

const fetchDevoluciones = async (page = 1) => {
  try {
    const res = await axios.get(`${API}/devoluciones?page=${page}`, { headers: getHeaders() })
    devoluciones.value = res.data.data
    currentPage.value = res.data.meta.current_page
    lastPage.value = res.data.meta.last_page
  } catch (err) {
    console.error('Error al cargar devoluciones:', err)
  }
}

const fetchPrestamos = async () => {
  try {
    const res = await axios.get(`${API}/prestamos`, { headers: getHeaders() })
    prestamos.value = res.data.data
  } catch (err) {
    console.error('Error al cargar préstamos:', err)
  }
}

const guardarDevolucion = async () => {
  const payload = {
    id_prestamo: id_prestamo.value,
    fecha_devolucion: fecha_devolucion.value,
    estado_libro: estado_libro.value,
    observaciones: observaciones.value,
  }

  try {
    if (isEditing.value) {
      await axios.put(`${API}/devoluciones/${idEditando.value}`, payload, { headers: getHeaders() })
    } else {
      await axios.post(`${API}/devoluciones`, payload, { headers: getHeaders() })
    }
    await fetchDevoluciones(currentPage.value)
    cancelarEdicion()
    mostrarFormulario.value = false
    errorMessage.value = ''
  } catch (err) {
    errorMessage.value = err.response?.data?.message || 'Error al guardar devolución'
  }
}

const eliminarDevolucion = async (id) => {
  if (!confirm('¿Eliminar esta devolución?')) return
  try {
    await axios.delete(`${API}/devoluciones/${id}`, { headers: getHeaders() })
    await fetchDevoluciones(currentPage.value)
  } catch (error) {
    console.error('Error eliminando devolución:', error)
  }
}

const editarDevolucion = (d) => {
  id_prestamo.value = d.id_prestamo
  fecha_devolucion.value = d.fecha_devolucion
  estado_libro.value = d.estado_libro || 'Bueno'
  observaciones.value = d.observaciones || ''
  idEditando.value = d.id
  isEditing.value = true
  mostrarFormulario.value = true
}

const cancelarEdicion = () => {
  id_prestamo.value = ''
  fecha_devolucion.value = ''
  estado_libro.value = 'Bueno'
  observaciones.value = ''
  idEditando.value = null
  isEditing.value = false
  errorMessage.value = ''
}

onMounted(() => {
  fetchDevoluciones()
  fetchPrestamos()
})
</script>
