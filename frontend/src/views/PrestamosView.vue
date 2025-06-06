<template>
  <div class="p-4 w-full overflow-auto">
    <h2 class="text-2xl font-bold mb-4">Gestión de Préstamos</h2>

    <!-- Botón -->
    <div class="mb-4">
      <button @click="mostrarFormulario = !mostrarFormulario" class="bg-green-600 text-white px-4 py-2 rounded">
        {{ mostrarFormulario ? 'Ocultar Formulario' : 'Agregar Préstamo' }}
      </button>
    </div>

    <!-- Formulario -->
    <form v-if="mostrarFormulario" @submit.prevent="guardarPrestamo" class="bg-white p-4 rounded shadow-md mb-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label>Libro</label>
          <select v-model="id_libro" required class="w-full border px-3 py-2 rounded">
            <option disabled value="">Seleccione un libro</option>
            <option v-for="libro in libros" :key="libro.id" :value="libro.id">
              {{ libro.titulo }}
            </option>
          </select>
        </div>

        <div>
          <label>Número de Ejemplares</label>
          <input v-model="numero_ejemplares" type="number" min="1" required class="w-full border px-3 py-2 rounded" />
        </div>

        <div>
          <label>Fecha de Préstamo</label>
          <input v-model="fecha_prestamo" type="date" required class="w-full border px-3 py-2 rounded" />
        </div>

        <div>
          <label>Fecha Devolución Esperada</label>
          <input v-model="fecha_devolucion_esperada" type="date" required class="w-full border px-3 py-2 rounded" />
        </div>

        <div>
          <label>Multa (opcional)</label>
          <select v-model="id_multa" class="w-full border px-3 py-2 rounded">
            <option disabled value="">Seleccione una multa</option>
            <option v-for="multa in multas" :key="multa.id" :value="multa.id">
              {{ multa.descripcion }} - ${{ multa.monto }}
            </option>
          </select>
        </div>

        <div>
          <label>Monto Multa (opcional)</label>
          <input v-model="monto_multa" type="number" step="0.01" class="w-full border px-3 py-2 rounded" />
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
          <th class="border px-4 py-2">Libro</th>
          <th class="border px-4 py-2">Ejemplares</th>
          <th class="border px-4 py-2">Fecha Préstamo</th>
          <th class="border px-4 py-2">Devolución Esperada</th>
          <th class="border px-4 py-2">Multa</th>
          <th class="border px-4 py-2">Monto</th>
          <th class="border px-4 py-2">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="prestamo in prestamos" :key="prestamo.id">
          <td class="border px-4 py-2">{{ prestamo.libro?.titulo || '—' }}</td>
          <td class="border px-4 py-2">{{ prestamo.numero_ejemplares }}</td>
          <td class="border px-4 py-2">{{ prestamo.fecha_prestamo }}</td>
          <td class="border px-4 py-2">{{ prestamo.fecha_devolucion_esperada }}</td>
          <td class="border px-4 py-2">{{ prestamo.multa?.descripcion || '—' }}</td>
          <td class="border px-4 py-2">${{ prestamo.monto_multa || 0 }}</td>
          <td class="border px-4 py-2 space-x-2">
            <button @click="editarPrestamo(prestamo)" class="bg-yellow-400 px-3 py-1 rounded text-white">Editar</button>
            <button @click="eliminarPrestamo(prestamo.id)" class="bg-red-600 px-3 py-1 rounded text-white">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Paginación -->
    <div class="mt-4 flex justify-center items-center space-x-4" v-if="lastPage > 1">
      <button @click="fetchPrestamos(currentPage - 1)" :disabled="currentPage === 1" class="px-4 py-2 bg-gray-200 rounded">Anterior</button>
      <span>Página {{ currentPage }} de {{ lastPage }}</span>
      <button @click="fetchPrestamos(currentPage + 1)" :disabled="currentPage === lastPage" class="px-4 py-2 bg-gray-200 rounded">Siguiente</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const prestamos = ref([])
const libros = ref([])
const multas = ref([])

const id_libro = ref('')
const numero_ejemplares = ref(1)
const fecha_prestamo = ref('')
const fecha_devolucion_esperada = ref('')
const id_multa = ref('')
const monto_multa = ref(0)

const isEditing = ref(false)
const idEditando = ref(null)
const mostrarFormulario = ref(false)
const errorMessage = ref('')
const currentPage = ref(1)
const lastPage = ref(1)

const API = import.meta.env.VITE_API_URL
const getHeaders = () => ({ Authorization: `Bearer ${localStorage.getItem('token')}` })

// Fetch préstamos con paginación
const fetchPrestamos = async (page = 1) => {
  try {
    const res = await axios.get(`${API}/prestamos?page=${page}`, { headers: getHeaders() })
    prestamos.value = res.data.data
    currentPage.value = res.data.meta.current_page
    lastPage.value = res.data.meta.last_page
  } catch (error) {
    console.error('Error cargando préstamos:', error)
  }
}

// Fetch libros y multas (libros también paginados, pero solo cargamos la primera página para el select)
const fetchLibrosYMultas = async () => {
  try {
    const [resLibros, resMultas] = await Promise.all([
      axios.get(`${API}/libros`, { headers: getHeaders() }),
      axios.get(`${API}/multas`, { headers: getHeaders() }),
    ])
    libros.value = resLibros.data.data || resLibros.data
    multas.value = resMultas.data.data || resMultas.data
  } catch (error) {
    console.error('Error cargando libros y multas:', error)
  }
}

const guardarPrestamo = async () => {
  const payload = {
    id_libro: id_libro.value,
    numero_ejemplares: numero_ejemplares.value,
    fecha_prestamo: fecha_prestamo.value,
    fecha_devolucion_esperada: fecha_devolucion_esperada.value,
    id_multa: id_multa.value || null,
    monto_multa: monto_multa.value,
  }

  try {
    if (isEditing.value) {
      await axios.put(`${API}/prestamos/${idEditando.value}`, payload, { headers: getHeaders() })
    } else {
      await axios.post(`${API}/prestamos`, payload, { headers: getHeaders() })
    }
    await fetchPrestamos(currentPage.value)
    cancelarEdicion()
    mostrarFormulario.value = false
    errorMessage.value = ''
  } catch (err) {
    errorMessage.value = err.response?.data?.message || 'Error al guardar préstamo'
  }
}

const eliminarPrestamo = async (id) => {
  if (!confirm('¿Eliminar este préstamo?')) return
  try {
    await axios.delete(`${API}/prestamos/${id}`, { headers: getHeaders() })
    await fetchPrestamos(currentPage.value)
  } catch (error) {
    console.error('Error eliminando préstamo:', error)
  }
}

const editarPrestamo = (p) => {
  id_libro.value = p.id_libro
  numero_ejemplares.value = p.numero_ejemplares
  fecha_prestamo.value = p.fecha_prestamo
  fecha_devolucion_esperada.value = p.fecha_devolucion_esperada
  id_multa.value = p.id_multa || ''
  monto_multa.value = p.monto_multa || 0
  idEditando.value = p.id
  isEditing.value = true
  mostrarFormulario.value = true
}

const cancelarEdicion = () => {
  id_libro.value = ''
  numero_ejemplares.value = 1
  fecha_prestamo.value = ''
  fecha_devolucion_esperada.value = ''
  id_multa.value = ''
  monto_multa.value = 0
  idEditando.value = null
  isEditing.value = false
  errorMessage.value = ''
}

onMounted(() => {
  fetchPrestamos()
  fetchLibrosYMultas()
})
</script>
