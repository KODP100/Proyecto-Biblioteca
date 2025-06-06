import React, { useState, useEffect } from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import AsyncStorage from '@react-native-async-storage/async-storage';

// Screens
import LoginScreen from './screens/LoginScreen';
import RegisterScreen from './screens/RegisterScreen';
import BottomTabs from './navigation/BottomTabs';
import LibroDetalleScreen from './screens/LibroDetalleScreen';

const Stack = createNativeStackNavigator();

export default function App() {
  const [userToken, setUserToken] = useState(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    const loadToken = async () => {
      try {
        const token = await AsyncStorage.getItem('token');
        setUserToken(token);
      } catch (e) {
        console.error('Error loading token:', e);
      } finally {
        setIsLoading(false);
      }
    };
    loadToken();
  }, []);

  if (isLoading) return null;

  return (
    <NavigationContainer>
      <Stack.Navigator>
        {userToken ? (
          <>
            <Stack.Screen
              name="Main"
              options={{ headerShown: false }}
            >
              {(props) => <BottomTabs {...props} setUserToken={setUserToken} />}
            </Stack.Screen>

            <Stack.Screen
              name="LibroDetalle"
              component={LibroDetalleScreen}
              options={{ title: 'Detalle del Libro' }}
            />
          </>
        ) : (
          <>
            <Stack.Screen
              name="Login"
              options={{ headerShown: false }}
            >
              {(props) => <LoginScreen {...props} setUserToken={setUserToken} />}
            </Stack.Screen>

            <Stack.Screen
              name="Register"
              options={{ headerShown: false }}
            >
              {(props) => <RegisterScreen {...props} setUserToken={setUserToken} />}
            </Stack.Screen>
          </>
        )}
      </Stack.Navigator>
    </NavigationContainer>
  );
}


