import React, { useState } from 'react';
import { View, TextInput, Button, Text, StyleSheet } from 'react-native';
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function LoginScreen({ navigation, setUserToken }) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [errorMessage, setErrorMessage] = useState('');
  const [isLoading, setLoading] = useState(false);

  const login = async () => {
    setLoading(true);
    setErrorMessage('');

    try {
      const res = await axios.post('http://192.168.1.11:8000/api/v1/auth/login', {
        email,
        password,
      });

      const { access_token } = res.data;

      await AsyncStorage.setItem('token', access_token);

      setUserToken(access_token); // Cambia el estado para mostrar Home automáticamente
      // NO uses navigation.replace('Home') aquí

    } catch (error) {
      setErrorMessage('Error en el inicio de sesión');
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Iniciar sesión</Text>

      {errorMessage ? <Text style={styles.error}>{errorMessage}</Text> : null}

      <TextInput
        placeholder="Correo"
        style={styles.input}
        value={email}
        onChangeText={setEmail}
        keyboardType="email-address"
        autoCapitalize="none"
      />
      <TextInput
        placeholder="Contraseña"
        style={styles.input}
        value={password}
        onChangeText={setPassword}
        secureTextEntry
      />

      <Button
        title={isLoading ? 'Ingresando...' : 'Ingresar'}
        onPress={login}
        disabled={isLoading}
      />

      <Text style={styles.link} onPress={() => navigation.navigate('Register')}>
        ¿No tienes cuenta? Regístrate aquí
      </Text>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { padding: 20, flex: 1, justifyContent: 'center' },
  title: { fontSize: 24, fontWeight: 'bold', marginBottom: 16, textAlign: 'center' },
  input: {
    borderWidth: 1,
    borderColor: '#ccc',
    marginBottom: 12,
    padding: 10,
    borderRadius: 8,
  },
  error: { color: 'red', marginBottom: 10, textAlign: 'center' },
  link: { marginTop: 20, color: 'blue', textAlign: 'center' },
});
