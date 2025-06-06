<template>
  <div class="p-4 w-full overflow-auto">
    <h2 class="text-2xl font-bold mb-4">Gestión de Libros</h2>

    <!-- Botón para mostrar/ocultar formulario -->
    <div class="flex justify-start mb-4">
      <button @click="mostrarFormulario = !mostrarFormulario" class="bg-green-600 text-white px-4 py-2 rounded">
        {{ mostrarFormulario ? 'Ocultar Formulario' : 'Agregar Libro' }}
      </button>
    </div>

    <!-- Formulario (visible solo si mostrarFormulario es true) -->
    <form v-if="mostrarFormulario" @submit.prevent="guardarLibro" class="bg-white p-4 rounded shadow-md mb-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block mb-1">Título</label>
          <input v-model="titulo" type="text" required class="w-full border px-3 py-2 rounded" />
        </div>

        <div>
          <label class="block mb-1">Año de Publicación</label>
          <input v-model="anio_publicacion" type="number" min="1000" max="9999" required class="w-full border px-3 py-2 rounded" />
        </div>

        <div>
          <label class="block mb-1">Número de Páginas</label>
          <input v-model="num_paginas" type="number" required class="w-full border px-3 py-2 rounded" />
        </div>

        <div>
          <label class="block mb-1">Editorial</label>
          <select v-model="id_editorial" required class="w-full border px-3 py-2 rounded">
            <option value="" disabled>Seleccione una editorial</option>
            <option v-for="e in editoriales" :key="e.id" :value="e.id">{{ e.nombre }}</option>
          </select>
        </div>

        <div>
          <label class="block mb-1">Categoría</label>
          <select v-model="id_categoria" required class="w-full border px-3 py-2 rounded">
            <option value="" disabled>Seleccione una categoría</option>
            <option v-for="c in categorias" :key="c.id" :value="c.id">{{ c.nombre }}</option>
          </select>
        </div>

        <div>
          <label class="block mb-1">Estado</label>
          <select v-model="id_estado_libro" required class="w-full border px-3 py-2 rounded">
            <option value="" disabled>Seleccione un estado</option>
            <option v-for="e in estados" :key="e.id" :value="e.id">{{ e.nombre }}</option>
          </select>
        </div>

        <div>
          <label class="block mb-1">Autor</label>
          <select v-model="id_autor" required class="w-full border px-3 py-2 rounded">
            <option value="" disabled>Seleccione un autor</option>
            <option v-for="a in autores" :key="a.id" :value="a.id">{{ a.nombre }}</option>
          </select>
        </div>

        <div class="mb-4">
          <label class="block mb-1">Existencias</label>
          <input
          v-model="existencias"
          type="number"
          required
          class="w-full border px-3 py-2 rounded"
        />
      </div>

      </div>

      <div class="mt-4">
        <button type="submit" :disabled="isLoading" class="bg-blue-600 text-white px-4 py-2 rounded mr-2">
          {{ isEditing ? 'Actualizar' : 'Guardar' }}
        </button>
        <button v-if="isEditing" @click="cancelarEdicion" type="button" class="bg-gray-400 text-white px-4 py-2 rounded">
          Cancelar
        </button>
      </div>
      <p v-if="errorMessage" class="text-red-600 mt-2">{{ errorMessage }}</p>
    </form>

    <!-- Tabla de Libros -->
    <table v-if="libros.length" class="w-full table-auto border">
      <thead class="bg-gray-100">
        <tr>
          <th class="border px-4 py-2">Título</th>
          <th class="border px-4 py-2">Año</th>
          <th class="border px-4 py-2">Páginas</th>
          <th class="border px-4 py-2">Editorial</th>
          <th class="border px-4 py-2">Categoría</th>
          <th class="border px-4 py-2">Estado</th>
          <th class="border px-4 py-2">Autor</th>
          <th class="border px-4 py-2 text-left">Existencias</th>
          <th class="border px-4 py-2 text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="l in libros" :key="l.id">
          <td class="border px-2 py-1">{{ l.titulo }}</td>
          <td class="border px-2 py-1">{{ l.anio_publicacion }}</td>
          <td class="border px-2 py-1">{{ l.num_paginas }}</td>
          <td class="border px-2 py-1">{{ l.editorial?.nombre || '—' }}</td>
          <td class="border px-2 py-1">{{ l.categoria?.nombre || '—' }}</td>
          <td class="border px-2 py-1">{{ l.estado_libro?.nombre || '—' }}</td>
          <td class="border px-2 py-1">{{ l.autor?.nombre || '—' }}</td>
          <td class="border px-4 py-2">{{ l.existencias }}</td>
          <td class="border px-2 py-1">
            <div class="flex justify-center space-x-2">
              <button @click="editarLibro(l)" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">
                Editar
              </button>
              <button @click="eliminarLibro(l.id)" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                Eliminar
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <p v-else class="text-gray-500">No hay libros registrados aún.</p>

    <!-- Paginación -->
    <div class="mt-4 flex justify-center space-x-2" v-if="lastPage > 1">
      <button
        :disabled="currentPage === 1"
        @click="fetchLibros(currentPage - 1)"
        class="px-3 py-1 bg-gray-300 rounded disabled:opacity-50"
      >
        Anterior
      </button>
      <span class="px-4 py-1">Página {{ currentPage }} de {{ lastPage }}</span>
      <button
        :disabled="currentPage === lastPage"
        @click="fetchLibros(currentPage + 1)"
        class="px-3 py-1 bg-gray-300 rounded disabled:opacity-50"
      >
        Siguiente
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const libros = ref([])
const titulo = ref('')
const anio_publicacion = ref('')
const num_paginas = ref('')
const id_editorial = ref('')
const id_categoria = ref('')
const id_estado_libro = ref('')
const id_autor = ref('')
const existencias = ref(0);

const editoriales = ref([])
const categorias = ref([])
const estados = ref([])
const autores = ref([])

const isEditing = ref(false)
const libroEditandoId = ref(null)
const isLoading = ref(false)
const errorMessage = ref('')
const mostrarFormulario = ref(false)

const currentPage = ref(1)
const lastPage = ref(1)

const API_BASE = import.meta.env.VITE_API_URL
const getHeaders = () => ({
  Authorization: `Bearer ${localStorage.getItem('token')}`,
})

const fetchLibros = async (page = 1) => {
  try {
    const res = await axios.get(`${API_BASE}/libros?page=${page}`, { headers: getHeaders() })
    libros.value = res.data.data
    currentPage.value = res.data.current_page
    lastPage.value = res.data.last_page
  } catch {
    errorMessage.value = 'Error al cargar libros'
  }
}

const fetchRelaciones = async () => {
  const [e, c, es, a] = await Promise.all([
    axios.get(`${API_BASE}/editoriales`, { headers: getHeaders() }),
    axios.get(`${API_BASE}/categorias`, { headers: getHeaders() }),
    axios.get(`${API_BASE}/estadolibros`, { headers: getHeaders() }),
    axios.get(`${API_BASE}/autores`, { headers: getHeaders() }),
  ])
  editoriales.value = e.data
  categorias.value = c.data
  estados.value = es.data
  autores.value = a.data
}

const guardarLibro = async () => {
  isLoading.value = true
  errorMessage.value = ''

  const payload = {
    titulo: titulo.value,
    anio_publicacion: anio_publicacion.value,
    num_paginas: num_paginas.value,
    id_editorial: id_editorial.value,
    id_categoria: id_categoria.value,
    id_estado_libro: id_estado_libro.value,
    id_autor: id_autor.value,
    existencias: existencias.value,
  }

  try {
    if (isEditing.value) {
      await axios.put(`${API_BASE}/libros/${libroEditandoId.value}`, payload, { headers: getHeaders() })
    } else {
      await axios.post(`${API_BASE}/libros`, payload, { headers: getHeaders() })
    }

    await fetchLibros(currentPage.value)
    cancelarEdicion()
    mostrarFormulario.value = false
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Error al guardar libro'
  } finally {
    isLoading.value = false
  }
}

const eliminarLibro = async (id) => {
  if (!confirm('¿Eliminar este libro?')) return
  await axios.delete(`${API_BASE}/libros/${id}`, { headers: getHeaders() })
  await fetchLibros(currentPage.value)
}

const editarLibro = (l) => {
  titulo.value = l.titulo
  anio_publicacion.value = l.anio_publicacion
  num_paginas.value = l.num_paginas
  id_editorial.value = l.id_editorial
  id_categoria.value = l.id_categoria
  id_estado_libro.value = l.id_estado_libro
  id_autor.value = l.id_autor
  existencias.value = libro.existencias;

  libroEditandoId.value = l.id
  isEditing.value = true
  mostrarFormulario.value = true
}

const cancelarEdicion = () => {
  titulo.value = ''
  anio_publicacion.value = ''
  num_paginas.value = ''
  id_editorial.value = ''
  id_categoria.value = ''
  id_estado_libro.value = ''
  id_autor.value = ''

  libroEditandoId.value = null
  isEditing.value = false
}

onMounted(() => {
  fetchRelaciones()
  fetchLibros()
})
</script>
