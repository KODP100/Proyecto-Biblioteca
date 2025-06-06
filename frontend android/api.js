// api.js
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

const api = axios.create({
  baseURL: 'http://192.168.1.11:8000/api/v1', // Cambia a tu IP real si usas dispositivo físico
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

// Añadir token automáticamente en cada request
api.interceptors.request.use(async (config) => {
  const token = await AsyncStorage.getItem('jwt_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;
