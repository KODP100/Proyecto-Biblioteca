import React, { useState, useEffect } from 'react';
import {
  View, Text, TextInput, Button, FlatList, StyleSheet,
} from 'react-native';
import axios from 'axios';
import { Picker } from '@react-native-picker/picker';
import AsyncStorage from '@react-native-async-storage/async-storage';

const PrestamosScreen = () => {
  const [mostrarFormulario, setMostrarFormulario] = useState(false);
  const [prestamos, setPrestamos] = useState([]);
  const [libros, setLibros] = useState([]);
  const [multas, setMultas] = useState([]);

  const [idLibro, setIdLibro] = useState('');
  const [numeroEjemplares, setNumeroEjemplares] = useState('1');
  const [fechaPrestamo, setFechaPrestamo] = useState('');
  const [fechaDevolucion, setFechaDevolucion] = useState('');
  const [idMulta, setIdMulta] = useState('');
  const [montoMulta, setMontoMulta] = useState('');

  const [isEditing, setIsEditing] = useState(false);
  const [idEditando, setIdEditando] = useState(null);
  const [errorMessage, setErrorMessage] = useState('');
  const [currentPage, setCurrentPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);

  const BASE_URL = 'http://192.168.1.11:8000/api/v1';

  const getHeaders = async () => {
    const token = await AsyncStorage.getItem('token');
    return {
      Authorization: `Bearer ${token}`,
    };
  };

  useEffect(() => {
    fetchLibrosYMultas();
    fetchPrestamos();
  }, [currentPage]);

  const fetchLibrosYMultas = async () => {
    try {
      const headers = await getHeaders();
      const [resLibros, resMultas] = await Promise.all([
        axios.get(`${BASE_URL}/libros`, { headers }),
        axios.get(`${BASE_URL}/multas`, { headers }),
      ]);
      setLibros(resLibros.data.data || []);
      setMultas(resMultas.data.data || []);
    } catch (e) {
      console.error('Error cargando libros o multas:', e);
    }
  };

  const fetchPrestamos = async () => {
    try {
      const headers = await getHeaders();
      const res = await axios.get(`${BASE_URL}/prestamos?page=${currentPage}`, { headers });

      setPrestamos(res.data.data || []);
      if (res.data.meta) {
        setCurrentPage(res.data.meta.current_page);
        setLastPage(res.data.meta.last_page);
      } else {
        setCurrentPage(1);
        setLastPage(1);
      }
    } catch (e) {
      console.error('Error cargando préstamos:', e);
    }
  };

  const guardarPrestamo = async () => {
    const payload = {
      id_libro: idLibro,
      numero_ejemplares: parseInt(numeroEjemplares),
      fecha_prestamo: fechaPrestamo,
      fecha_devolucion_esperada: fechaDevolucion,
      id_multa: idMulta || null,
      monto_multa: parseFloat(montoMulta || 0),
    };

    try {
      const headers = await getHeaders();
      if (isEditing) {
        await axios.put(`${BASE_URL}/prestamos/${idEditando}`, payload, { headers });
      } else {
        await axios.post(`${BASE_URL}/prestamos`, payload, { headers });
      }
      fetchPrestamos();
      cancelarEdicion();
      setMostrarFormulario(false);
    } catch (err) {
      setErrorMessage(err.response?.data?.message || 'Error al guardar préstamo');
    }
  };

  const eliminarPrestamo = async (id) => {
    try {
      const headers = await getHeaders();
      await axios.delete(`${BASE_URL}/prestamos/${id}`, { headers });
      fetchPrestamos();
    } catch (e) {
      console.error('Error eliminando préstamo:', e);
    }
  };

  const editarPrestamo = (p) => {
    setIdLibro(p.id_libro);
    setNumeroEjemplares(String(p.numero_ejemplares));
    setFechaPrestamo(p.fecha_prestamo);
    setFechaDevolucion(p.fecha_devolucion_esperada);
    setIdMulta(p.id_multa || '');
    setMontoMulta(String(p.monto_multa || 0));
    setIdEditando(p.id);
    setIsEditing(true);
    setMostrarFormulario(true);
  };

  const cancelarEdicion = () => {
    setIdLibro('');
    setNumeroEjemplares('1');
    setFechaPrestamo('');
    setFechaDevolucion('');
    setIdMulta('');
    setMontoMulta('');
    setIsEditing(false);
    setIdEditando(null);
    setErrorMessage('');
  };

  const PrestamoItem = ({ item }) => (
    <View style={styles.row}>
      <Text>📘 {item.libro?.titulo || '—'}</Text>
      <Text>🔢 {item.numero_ejemplares}</Text>
      <Text>📅 {item.fecha_prestamo}</Text>
      <Text>📅 {item.fecha_devolucion_esperada}</Text>
      <Text>⚠️ {item.multa?.descripcion || '—'}</Text>
      <Text>💰 ${item.monto_multa || 0}</Text>
      <View style={styles.rowButtons}>
        <Button title="Editar" color="#facc15" onPress={() => editarPrestamo(item)} />
        <Button title="Eliminar" color="#dc2626" onPress={() => eliminarPrestamo(item.id)} />
      </View>
    </View>
  );

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Gestión de Préstamos</Text>

      <Button
        title={mostrarFormulario ? 'Ocultar Formulario' : 'Agregar Préstamo'}
        onPress={() => setMostrarFormulario(!mostrarFormulario)}
        color="#16a34a"
      />

      {mostrarFormulario && (
        <View style={styles.form}>
          <Text>📘 Libro:</Text>
          <Picker selectedValue={idLibro} onValueChange={setIdLibro}>
            <Picker.Item label="Seleccione un libro" value="" />
            {libros.map((libro) => (
              <Picker.Item key={libro.id} label={libro.titulo} value={libro.id} />
            ))}
          </Picker>

          <TextInput
            placeholder="Número de ejemplares"
            keyboardType="numeric"
            style={styles.input}
            value={numeroEjemplares}
            onChangeText={setNumeroEjemplares}
          />
          <TextInput
            placeholder="Fecha préstamo (YYYY-MM-DD)"
            style={styles.input}
            value={fechaPrestamo}
            onChangeText={setFechaPrestamo}
          />
          <TextInput
            placeholder="Fecha devolución esperada (YYYY-MM-DD)"
            style={styles.input}
            value={fechaDevolucion}
            onChangeText={setFechaDevolucion}
          />
          <Text>⚠️ Multa:</Text>
          <Picker selectedValue={idMulta} onValueChange={setIdMulta}>
            <Picker.Item label="Seleccione una multa" value="" />
            {multas.map((m) => (
              <Picker.Item key={m.id} label={`${m.descripcion} - $${m.monto}`} value={m.id} />
            ))}
          </Picker>
          <TextInput
            placeholder="Monto multa (opcional)"
            keyboardType="decimal-pad"
            style={styles.input}
            value={montoMulta}
            onChangeText={setMontoMulta}
          />

          <View style={styles.buttonRow}>
            <Button title={isEditing ? 'Actualizar' : 'Guardar'} onPress={guardarPrestamo} color="#2563eb" />
            {isEditing && (
              <Button title="Cancelar" onPress={cancelarEdicion} color="#6b7280" />
            )}
          </View>
          {!!errorMessage && <Text style={styles.error}>{errorMessage}</Text>}
        </View>
      )}

      <FlatList
        data={prestamos}
        keyExtractor={(item) => item.id.toString()}
        renderItem={PrestamoItem}
      />

      {lastPage > 1 && (
        <View style={styles.pagination}>
          <Button title="Anterior" disabled={currentPage === 1} onPress={() => setCurrentPage(currentPage - 1)} />
          <Text>{`Página ${currentPage} de ${lastPage}`}</Text>
          <Button title="Siguiente" disabled={currentPage === lastPage} onPress={() => setCurrentPage(currentPage + 1)} />
        </View>
      )}
    </View>
  );
};

export default PrestamosScreen;

const styles = StyleSheet.create({
  container: { padding: 16, flex: 1 },
  title: { fontSize: 24, fontWeight: 'bold', marginBottom: 16 },
  form: { backgroundColor: '#fff', padding: 16, borderRadius: 8, marginVertical: 12 },
  input: { borderWidth: 1, borderColor: '#ccc', borderRadius: 6, padding: 8, marginVertical: 6 },
  error: { color: 'red', marginTop: 8 },
  row: { borderBottomWidth: 1, borderColor: '#ddd', paddingVertical: 10 },
  rowButtons: { flexDirection: 'row', justifyContent: 'space-between', marginTop: 8 },
  buttonRow: { flexDirection: 'row', justifyContent: 'space-between', marginTop: 12 },
  pagination: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginTop: 20 },
});
