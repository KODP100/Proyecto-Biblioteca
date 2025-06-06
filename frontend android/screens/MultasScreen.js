import React, { useEffect, useState } from 'react';
import {
  View,
  Text,
  TextInput,
  Button,
  FlatList,
  Alert,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
} from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import axios from 'axios';

const API_URL = 'http://192.168.1.11:8000/api/v1/multas'; // <-- ajusta tu URL

export default function MultasScreen() {
  const [rangoDias, setRangoDias] = useState('');
  const [monto, setMonto] = useState('');
  const [multas, setMultas] = useState([]);
  const [isEditing, setIsEditing] = useState(false);
  const [editId, setEditId] = useState(null);
  const [loading, setLoading] = useState(false);
  const [errorMsg, setErrorMsg] = useState('');

  const getToken = async () => {
    return await AsyncStorage.getItem('token');
  };

  const fetchMultas = async () => {
    try {
      const token = await getToken();
      const response = await axios.get(API_URL, {
        headers: { Authorization: `Bearer ${token}` },
      });
      setMultas(response.data);
    } catch (error) {
      setErrorMsg('Error al cargar multas');
    }
  };

  const guardarMulta = async () => {
    setLoading(true);
    setErrorMsg('');
    const token = await getToken();
    const datos = {
      rango_dias: parseInt(rangoDias),
      monto: parseFloat(monto),
    };

    try {
      if (isEditing) {
        await axios.put(`${API_URL}/${editId}`, datos, {
          headers: { Authorization: `Bearer ${token}` },
        });
      } else {
        await axios.post(API_URL, datos, {
          headers: { Authorization: `Bearer ${token}` },
        });
      }
      await fetchMultas();
      limpiarFormulario();
    } catch (error) {
      setErrorMsg(error.response?.data?.message || 'Error inesperado');
    } finally {
      setLoading(false);
    }
  };

  const eliminarMulta = async (id) => {
    Alert.alert('Eliminar', '¿Estás seguro de eliminar esta multa?', [
      { text: 'Cancelar' },
      {
        text: 'Eliminar',
        onPress: async () => {
          try {
            const token = await getToken();
            await axios.delete(`${API_URL}/${id}`, {
              headers: { Authorization: `Bearer ${token}` },
            });
            fetchMultas();
          } catch (error) {
            Alert.alert('Error', 'No se pudo eliminar');
          }
        },
      },
    ]);
  };

  const editarMulta = (multa) => {
    setRangoDias(multa.rango_dias.toString());
    setMonto(multa.monto.toString());
    setEditId(multa.id);
    setIsEditing(true);
  };

  const limpiarFormulario = () => {
    setRangoDias('');
    setMonto('');
    setIsEditing(false);
    setEditId(null);
  };

  useEffect(() => {
    fetchMultas();
  }, []);

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Gestión de Multas</Text>

      <TextInput
        placeholder="Rango de días"
        value={rangoDias}
        onChangeText={setRangoDias}
        keyboardType="numeric"
        style={styles.input}
      />
      <TextInput
        placeholder="Monto"
        value={monto}
        onChangeText={setMonto}
        keyboardType="decimal-pad"
        style={styles.input}
      />

      <View style={styles.buttonRow}>
        <Button
          title={isEditing ? 'Actualizar' : 'Crear'}
          onPress={guardarMulta}
          disabled={loading}
        />
        {isEditing && (
          <Button title="Cancelar" color="#999" onPress={limpiarFormulario} />
        )}
      </View>

      {errorMsg ? <Text style={styles.error}>{errorMsg}</Text> : null}

      <View style={styles.separator} />

      {loading ? (
        <ActivityIndicator size="large" />
      ) : (
        <FlatList
          data={multas}
          keyExtractor={(item) => item.id.toString()}
          renderItem={({ item }) => (
            <View style={styles.item}>
              <View style={styles.itemData}>
                <Text>ID: {item.id}</Text>
                <Text>Rango: {item.rango_dias} días</Text>
                <Text>Monto: ${parseFloat(item.monto).toFixed(2)}</Text>
              </View>
              <View style={styles.actions}>
                <TouchableOpacity onPress={() => editarMulta(item)}>
                  <Text style={styles.edit}>Editar</Text>
                </TouchableOpacity>
                <TouchableOpacity onPress={() => eliminarMulta(item.id)}>
                  <Text style={styles.delete}>Eliminar</Text>
                </TouchableOpacity>
              </View>
            </View>
          )}
        />
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    padding: 16,
    paddingTop: 40,
    backgroundColor: '#fff',
    flex: 1,
  },
  title: {
    fontSize: 22,
    fontWeight: 'bold',
    marginBottom: 16,
    textAlign: 'center',
  },
  input: {
    borderColor: '#ccc',
    borderWidth: 1,
    borderRadius: 6,
    marginBottom: 12,
    padding: 10,
  },
  buttonRow: {
    flexDirection: 'row',
    gap: 10,
    justifyContent: 'space-between',
    marginBottom: 10,
  },
  error: {
    color: 'red',
    marginBottom: 12,
    textAlign: 'center',
  },
  separator: {
    height: 1,
    backgroundColor: '#eee',
    marginVertical: 12,
  },
  item: {
    padding: 12,
    borderColor: '#ccc',
    borderWidth: 1,
    marginBottom: 10,
    borderRadius: 8,
  },
  itemData: {
    marginBottom: 8,
  },
  actions: {
    flexDirection: 'row',
    gap: 16,
  },
  edit: {
    color: '#007bff',
    fontWeight: 'bold',
  },
  delete: {
    color: '#dc3545',
    fontWeight: 'bold',
  },
});
