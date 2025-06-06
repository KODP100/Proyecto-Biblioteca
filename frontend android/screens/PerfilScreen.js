import React, { useEffect, useState } from 'react';
import {
  View, Text, TextInput, Button, FlatList, Alert,
  ScrollView, StyleSheet, TouchableOpacity
} from 'react-native';
import axios from 'axios';
import { Picker } from '@react-native-picker/picker';
import AsyncStorage from '@react-native-async-storage/async-storage';

const API = 'http://192.168.1.11:8000/api/v1';

const GestorDevoluciones = () => {
  const [devoluciones, setDevoluciones] = useState([]);
  const [prestamos, setPrestamos] = useState([]);

  const [idPrestamo, setIdPrestamo] = useState('');
  const [fechaDevolucion, setFechaDevolucion] = useState('');
  const [estadoLibro, setEstadoLibro] = useState('Bueno');
  const [observaciones, setObservaciones] = useState('');

  const [mostrarFormulario, setMostrarFormulario] = useState(false);
  const [isEditing, setIsEditing] = useState(false);
  const [idEditando, setIdEditando] = useState(null);
  const [errorMessage, setErrorMessage] = useState('');

  const [currentPage, setCurrentPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);

  const getHeaders = async () => {
    const token = await AsyncStorage.getItem('token');
    return { Authorization: `Bearer ${token}` };
  };

  useEffect(() => {
    fetchDevoluciones();
    fetchPrestamos();
  }, []);

  const fetchDevoluciones = async (page = 1) => {
    try {
      const res = await axios.get(`${API}/devoluciones?page=${page}`, {
        headers: await getHeaders()
      });
      setDevoluciones(res.data.data);
      setCurrentPage(res.data.meta.current_page);
      setLastPage(res.data.meta.last_page);
    } catch (err) {
      console.error('Error cargando devoluciones:', err);
    }
  };

  const fetchPrestamos = async () => {
    try {
      const res = await axios.get(`${API}/prestamos`, {
        headers: await getHeaders()
      });
      setPrestamos(res.data.data);
    } catch (err) {
      console.error('Error cargando préstamos:', err);
    }
  };

  const guardarDevolucion = async () => {
    const payload = {
      id_prestamo: idPrestamo,
      fecha_devolucion: fechaDevolucion,
      estado_libro: estadoLibro,
      observaciones,
    };

    try {
      if (isEditing) {
        await axios.put(`${API}/devoluciones/${idEditando}`, payload, {
          headers: await getHeaders()
        });
      } else {
        await axios.post(`${API}/devoluciones`, payload, {
          headers: await getHeaders()
        });
      }
      fetchDevoluciones(currentPage);
      cancelarEdicion();
      setMostrarFormulario(false);
      setErrorMessage('');
    } catch (err) {
      console.error(err);
      setErrorMessage(err.response?.data?.message || 'Error al guardar devolución');
    }
  };

  const eliminarDevolucion = async (id) => {
    Alert.alert('Confirmar', '¿Eliminar esta devolución?', [
      { text: 'Cancelar', style: 'cancel' },
      {
        text: 'Eliminar',
        style: 'destructive',
        onPress: async () => {
          try {
            await axios.delete(`${API}/devoluciones/${id}`, {
              headers: await getHeaders()
            });
            fetchDevoluciones(currentPage);
          } catch (err) {
            console.error('Error eliminando devolución:', err);
          }
        }
      }
    ]);
  };

  const editarDevolucion = (d) => {
    setIdPrestamo(d.id_prestamo);
    setFechaDevolucion(d.fecha_devolucion);
    setEstadoLibro(d.estado_libro || 'Bueno');
    setObservaciones(d.observaciones || '');
    setIdEditando(d.id);
    setIsEditing(true);
    setMostrarFormulario(true);
  };

  const cancelarEdicion = () => {
    setIdPrestamo('');
    setFechaDevolucion('');
    setEstadoLibro('Bueno');
    setObservaciones('');
    setIdEditando(null);
    setIsEditing(false);
    setErrorMessage('');
  };

  const renderItem = ({ item }) => (
    <View style={styles.item}>
      <Text>Préstamo #{item.prestamo?.id}</Text>
      <Text>Libro: {item.prestamo?.libro?.titulo || '—'}</Text>
      <Text>Devolución: {item.fecha_devolucion}</Text>
      <Text>Estado: {item.estado_libro || '—'}</Text>
      <Text>Obs: {item.observaciones || '—'}</Text>
      <View style={styles.row}>
        <Button title="Editar" color="#facc15" onPress={() => editarDevolucion(item)} />
        <Button title="Eliminar" color="#dc2626" onPress={() => eliminarDevolucion(item.id)} />
      </View>
    </View>
  );

  return (
    <ScrollView style={styles.container}>
      <Text style={styles.title}>Gestión de Devoluciones</Text>

      <TouchableOpacity onPress={() => setMostrarFormulario(!mostrarFormulario)} style={styles.toggleButton}>
        <Text style={styles.buttonText}>{mostrarFormulario ? 'Ocultar Formulario' : 'Agregar Devolución'}</Text>
      </TouchableOpacity>

      {mostrarFormulario && (
        <View style={styles.form}>
          <Text>Préstamo</Text>
          <Picker selectedValue={idPrestamo} onValueChange={setIdPrestamo}>
            <Picker.Item label="Seleccione un préstamo" value="" />
            {prestamos.map(p => (
              <Picker.Item key={p.id} label={`#${p.id} - ${p.libro?.titulo || 'Préstamo'} (${p.fecha_prestamo})`} value={p.id} />
            ))}
          </Picker>

          <Text>Fecha Devolución</Text>
          <TextInput value={fechaDevolucion} onChangeText={setFechaDevolucion} placeholder="YYYY-MM-DD" style={styles.input} />

          <Text>Estado del Libro</Text>
          <Picker selectedValue={estadoLibro} onValueChange={setEstadoLibro}>
            <Picker.Item label="Bueno" value="Bueno" />
            <Picker.Item label="Dañado" value="Dañado" />
            <Picker.Item label="Perdido" value="Perdido" />
          </Picker>

          <Text>Observaciones</Text>
          <TextInput value={observaciones} onChangeText={setObservaciones} style={styles.input} multiline numberOfLines={3} />

          <View style={styles.row}>
            <Button title={isEditing ? 'Actualizar' : 'Guardar'} onPress={guardarDevolucion} color="#2563eb" />
            {isEditing && <Button title="Cancelar" onPress={cancelarEdicion} color="#6b7280" />}
          </View>

          {errorMessage !== '' && <Text style={styles.error}>{errorMessage}</Text>}
        </View>
      )}

      <FlatList
        data={devoluciones}
        keyExtractor={item => item.id.toString()}
        renderItem={renderItem}
      />

      {lastPage > 1 && (
        <View style={styles.pagination}>
          <Button title="Anterior" onPress={() => fetchDevoluciones(currentPage - 1)} disabled={currentPage === 1} />
          <Text>Página {currentPage} de {lastPage}</Text>
          <Button title="Siguiente" onPress={() => fetchDevoluciones(currentPage + 1)} disabled={currentPage === lastPage} />
        </View>
      )}
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: { padding: 16 },
  title: { fontSize: 22, fontWeight: 'bold', marginBottom: 16 },
  toggleButton: { backgroundColor: '#16a34a', padding: 10, borderRadius: 6, marginBottom: 12 },
  buttonText: { color: 'white', textAlign: 'center' },
  form: { backgroundColor: 'white', padding: 16, borderRadius: 8, marginBottom: 20, elevation: 3 },
  input: { borderWidth: 1, borderColor: '#ccc', borderRadius: 6, padding: 10, marginBottom: 10 },
  row: { flexDirection: 'row', justifyContent: 'space-between', marginTop: 12 },
  pagination: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginTop: 16 },
  error: { color: 'red', marginTop: 10 },
  item: { borderBottomWidth: 1, borderColor: '#ccc', paddingVertical: 10 }
});

export default GestorDevoluciones;
