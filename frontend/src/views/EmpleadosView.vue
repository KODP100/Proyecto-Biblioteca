<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Variables
const empleados = ref([])
const nombre = ref('')
const apellido = ref('')
const edad = ref(null)
const telefono = ref('')
const idDepartamento = ref('')
const idMunicipio = ref('')
const altaEmpleado = ref('')
const bajaEmpleado = ref('')
const direccion = ref('')

const departamentos = ref([])
const municipios = ref([])

const isEditing = ref(false)
const empleadoEditandoId = ref(null)
const isLoading = ref(false)
const errorMessage = ref('')

// URLs
const API_BASE = import.meta.env.VITE_API_URL
const API_EMPLEADOS = `${API_BASE}/empleados`
const API_DEPARTAMENTOS = `${API_BASE}/departamentos`
const API_MUNICIPIOS = `${API_BASE}/municipios`

// Headers
const getHeaders = () => ({
  Authorization: `Bearer ${localStorage.getItem('token')}`,
})

// Fetch funciones
const fetchEmpleados = async () => {
  try {
    const res = await axios.get(API_EMPLEADOS, { headers: getHeaders() })
    empleados.value = res.data
  } catch {
    errorMessage.value = 'Error al cargar empleados'
  }
}

const fetchDepartamentos = async () => {
  try {
    const res = await axios.get(API_DEPARTAMENTOS, { headers: getHeaders() })
    departamentos.value = res.data
  } catch {
    errorMessage.value = 'Error al cargar departamentos'
  }
}

const fetchMunicipios = async () => {
  try {
    const res = await axios.get(API_MUNICIPIOS, { headers: getHeaders() })
    municipios.value = res.data
  } catch {
    errorMessage.value = 'Error al cargar municipios'
  }
}

// Guardar / actualizar
const guardarEmpleado = async () => {
  isLoading.value = true
  errorMessage.value = ''

  const payload = {
    nombre: nombre.value,
    apellido: apellido.value,
    edad: edad.value,
    telefono: telefono.value,
    id_departamento: idDepartamento.value,
    id_municipio: idMunicipio.value,
    alta_empleado: altaEmpleado.value,
    baja_empleado: bajaEmpleado.value,
    direccion: direccion.value,
  }

  try {
    if (isEditing.value) {
      await axios.put(`${API_EMPLEADOS}/${empleadoEditandoId.value}`, payload, {
        headers: getHeaders(),
      })
    } else {
      await axios.post(API_EMPLEADOS, payload, {
        headers: getHeaders(),
      })
    }

    await fetchEmpleados()
    cancelarEdicion()
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Error al guardar empleado'
  } finally {
    isLoading.value = false
  }
}

// Eliminar
const eliminarEmpleado = async (id) => {
  if (!confirm('¿Eliminar este empleado?')) return
  try {
    await axios.delete(`${API_EMPLEADOS}/${id}`, {
      headers: getHeaders(),
    })
    await fetchEmpleados()
  } catch {
    alert('Error al eliminar empleado')
  }
}

// Editar
const editarEmpleado = (empleado) => {
  nombre.value = empleado.nombre
  apellido.value = empleado.apellido
  edad.value = empleado.edad
  telefono.value = empleado.telefono
  idDepartamento.value = empleado.id_departamento
  idMunicipio.value = empleado.id_municipio
  altaEmpleado.value = empleado.alta_empleado
  bajaEmpleado.value = empleado.baja_empleado
  direccion.value = empleado.direccion

  empleadoEditandoId.value = empleado.id
  isEditing.value = true
}

const cancelarEdicion = () => {
  nombre.value = ''
  apellido.value = ''
  edad.value = null
  telefono.value = ''
  idDepartamento.value = ''
  idMunicipio.value = ''
  altaEmpleado.value = ''
  bajaEmpleado.value = ''
  direccion.value = ''

  empleadoEditandoId.value = null
  isEditing.value = false
}

onMounted(() => {
  fetchDepartamentos()
  fetchMunicipios()
  fetchEmpleados()
})
</script>

<template>
  <div class="max-w-5xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <form @submit.prevent="guardarEmpleado">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block mb-1">Nombre</label>
          <input v-model="nombre" type="text" required class="w-full border px-3 py-2 rounded" />
        </div>
        <div>
          <label class="block mb-1">Apellido</label>
          <input v-model="apellido" type="text" required class="w-full border px-3 py-2 rounded" />
        </div>
        <div>
          <label class="block mb-1">Edad</label>
          <input v-model="edad" type="number" required class="w-full border px-3 py-2 rounded" />
        </div>
        <div>
          <label class="block mb-1">Teléfono</label>
          <input v-model="telefono" type="text" class="w-full border px-3 py-2 rounded" />
        </div>
        <div>
          <label class="block mb-1">Departamento</label>
          <select v-model="idDepartamento" required class="w-full border px-3 py-2 rounded">
            <option value="" disabled>Seleccione un departamento</option>
            <option v-for="d in departamentos" :key="d.id" :value="d.id">{{ d.nombre_depto }}</option>
          </select>
        </div>
        <div>
          <label class="block mb-1">Municipio</label>
          <select v-model="idMunicipio" required class="w-full border px-3 py-2 rounded">
            <option value="" disabled>Seleccione un municipio</option>
            <option v-for="m in municipios" :key="m.id" :value="m.id">{{ m.nombre_municipio }}</option>
          </select>
        </div>
        <div>
          <label class="block mb-1">Alta empleado</label>
          <input v-model="altaEmpleado" type="date" class="w-full border px-3 py-2 rounded" />
        </div>
        <div>
          <label class="block mb-1">Baja empleado</label>
          <input v-model="bajaEmpleado" type="date" class="w-full border px-3 py-2 rounded" />
        </div>
        <div class="col-span-2">
          <label class="block mb-1">Dirección</label>
          <input v-model="direccion" type="text" class="w-full border px-3 py-2 rounded" />
        </div>
      </div>

      <div class="flex gap-2 mt-4">
        <button :disabled="isLoading" type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
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

    <h2 class="text-lg font-semibold mb-4">Empleados</h2>
    <table class="w-full border text-sm">
      <thead>
        <tr class="bg-gray-100">
          <th class="border px-4 py-2">ID</th>
          <th class="border px-4 py-2">Nombre</th>
          <th class="border px-4 py-2">Apellido</th>
          <th class="border px-4 py-2">Edad</th>
          <th class="border px-4 py-2">Departamento</th>
          <th class="border px-4 py-2">Municipio</th>
          <th class="border px-4 py-2">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="e in empleados" :key="e.id">
          <td class="border px-2 py-1">{{ e.id }}</td>
          <td class="border px-2 py-1">{{ e.nombre }}</td>
          <td class="border px-2 py-1">{{ e.apellido }}</td>
          <td class="border px-2 py-1">{{ e.edad }}</td>
          <td class="border px-2 py-1">
            {{ departamentos.find(d => d.id === e.id_departamento)?.nombre_depto || '—' }}
          </td>
          <td class="border px-2 py-1">
            {{ municipios.find(m => m.id === e.id_municipio)?.nombre_municipio || '—' }}
          </td>
          <td class="border px-2 py-1 flex gap-2">
            <button class="text-blue-600 hover:underline" @click="editarEmpleado(e)">Editar</button>
            <button class="text-red-600 hover:underline" @click="eliminarEmpleado(e.id)">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
