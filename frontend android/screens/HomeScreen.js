import React, { useEffect, useState } from 'react';
import { View, Text, Button, StyleSheet } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import axios from 'axios';

export default function HomeScreen({ navigation, setUserToken }) {
  const [user, setUser] = useState(null);

  const fetchMe = async () => {
    const token = await AsyncStorage.getItem('token');
    try {
      const res = await axios.get('http://192.168.1.11:8000/api/v1/auth/me', {
        headers: { Authorization: `Bearer ${token}` },
      });
      setUser(res.data);
    } catch (err) {
      setUserToken(null); // El token no es válido, salir al login
    }
  };

  const logout = async () => {
  const token = await AsyncStorage.getItem('token');

  try {
    await axios.get('http://192.168.1.11:8000/api/v1/auth/logout', {
      headers: { Authorization: `Bearer ${token}` },
    });
  } catch (error) {
    console.log('Error al cerrar sesión:', error.response?.data || error.message);
    // Puedes ignorar errores si el token ya no es válido
  }

  await AsyncStorage.removeItem('token');
  setUserToken(null); // Esto debe redirigir al LoginScreen
};


  useEffect(() => {
    fetchMe();
  }, []);

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Bienvenido</Text>
      {user && <Text style={styles.text}>Hola, {user.name} 👋</Text>}

      <Button title="Cerrar sesión" onPress={logout} />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { 
    padding: 20, 
    flex: 1, 
    justifyContent: 'center', 
    alignItems: 'center',
    backgroundColor: '#f5f5f5',
  },
  title: { 
    fontSize: 28, 
    fontWeight: 'bold', 
    marginBottom: 10,
  },
  text: { 
    fontSize: 18, 
    marginBottom: 20,
  },
});
