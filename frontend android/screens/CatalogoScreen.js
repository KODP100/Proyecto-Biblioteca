// screens/CatalogoScreen.js
import React, { useEffect, useState, useCallback } from 'react';
import { View, Text, FlatList, ActivityIndicator, TouchableOpacity, StyleSheet } from 'react-native';
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useNavigation } from '@react-navigation/native';

export default function CatalogoScreen() {
  const [libros, setLibros] = useState([]);
  const [page, setPage] = useState(1);
  const [loading, setLoading] = useState(false);
  const [hasMore, setHasMore] = useState(true);
  const navigation = useNavigation();

  const fetchLibros = useCallback(async () => {
    if (loading || !hasMore) return;
    setLoading(true);

    try {
      const token = await AsyncStorage.getItem('token');
      const response = await axios.get(`http://192.168.1.11:8000/api/v1/libros?page=${page}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      const nuevosLibros = response.data.data || response.data;
      setLibros(prev => [...prev, ...nuevosLibros]);
      setHasMore(response.data.next_page_url !== null);
      setPage(prev => prev + 1);
    } catch (error) {
      console.error('Error al cargar libros:', error);
    } finally {
      setLoading(false);
    }
  }, [page, loading, hasMore]);

  useEffect(() => {
    fetchLibros();
  }, []);

  const renderLibro = ({ item }) => (
    <TouchableOpacity
      style={styles.card}
      onPress={() => navigation.navigate('LibroDetalle', { libro: item })}
    >
      <Text style={styles.title}>{item.titulo}</Text>
      <Text style={styles.subtitle}>Autor: {item.autor?.nombre || 'Desconocido'}</Text>
      <Text style={styles.subtitle}>Editorial: {item.editorial?.nombre || 'N/A'}</Text>
    </TouchableOpacity>
  );

  const renderFooter = () => {
    if (!loading) return null;
    return <ActivityIndicator size="large" style={styles.loading} />;
  };

  return (
    <View style={styles.container}>
      <FlatList
        data={libros}
        keyExtractor={(item) => item.id.toString()}
        renderItem={renderLibro}
        ListFooterComponent={renderFooter}
        onEndReached={fetchLibros}
        onEndReachedThreshold={0.5}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#ffffff',
  },
  card: {
    padding: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#cccccc',
    backgroundColor: '#f9f9f9',
  },
  title: {
    fontSize: 18,
    fontWeight: '600',
  },
  subtitle: {
    fontSize: 14,
    color: '#555555',
  },
  loading: {
    marginVertical: 16,
  },
});
