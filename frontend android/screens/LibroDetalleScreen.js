import React from 'react';
import { View, Text, ScrollView, StyleSheet } from 'react-native';

export default function LibroDetalleScreen({ route }) {
  const { libro } = route.params;

  return (
    <ScrollView style={styles.container}>
      <Text style={styles.title}>{libro.titulo}</Text>
      <Text style={styles.label}>Autor:</Text>
      <Text style={styles.text}>{libro.autor?.nombre || 'Desconocido'}</Text>

      <Text style={styles.label}>Editorial:</Text>
      <Text style={styles.text}>{libro.editorial?.nombre || 'N/A'}</Text>

      <Text style={styles.label}>Categoría:</Text>
      <Text style={styles.text}>{libro.categoria?.nombre || 'N/A'}</Text>

      <Text style={styles.label}>Estado:</Text>
      <Text style={styles.text}>{libro.estado_libro?.nombre || 'N/A'}</Text>

      <Text style={styles.label}>Año de Publicación:</Text>
      <Text style={styles.text}>{libro.anio_publicacion}</Text>

      <Text style={styles.label}>Número de Páginas:</Text>
      <Text style={styles.text}>{libro.num_paginas}</Text>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    padding: 20,
    backgroundColor: '#fff',
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    marginBottom: 15,
  },
  label: {
    fontSize: 16,
    fontWeight: '600',
    marginTop: 10,
  },
  text: {
    fontSize: 16,
    color: '#444',
  },
});
