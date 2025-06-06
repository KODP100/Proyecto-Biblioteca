import React from 'react';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { Ionicons } from '@expo/vector-icons';

// Screens
import HomeScreen from '../screens/HomeScreen';
import CatalogoScreen from '../screens/CatalogoScreen';
import PrestamosScreen from '../screens/PrestamosScreen';
import MultasScreen from '../screens/MultasScreen';
import PerfilScreen from '../screens/PerfilScreen';

const Tab = createBottomTabNavigator();

export default function BottomTabs({ setUserToken }) {
  return (
    <Tab.Navigator
      initialRouteName="Home"
      screenOptions={({ route }) => ({
        tabBarIcon: ({ color, size }) => {
          let iconName;
          switch (route.name) {
            case 'Home':
              iconName = 'home';
              break;
            case 'Catalogo':
              iconName = 'book';
              break;
            case 'Prestamos':
              iconName = 'swap-horizontal';
              break;
            case 'Multas':
              iconName = 'cash';
              break;
            case 'devolucion':
              iconName = 'person';
              break;
          }
          return <Ionicons name={iconName} size={size} color={color} />;
        },
        tabBarActiveTintColor: '#007aff',
        tabBarInactiveTintColor: 'gray',
        headerShown: true,
      })}
    >
      <Tab.Screen name="Home">
        {props => <HomeScreen {...props} setUserToken={setUserToken} />}
      </Tab.Screen>

      <Tab.Screen name="Catalogo" component={CatalogoScreen} />
      <Tab.Screen name="Prestamos" component={PrestamosScreen} />
      <Tab.Screen name="Multas" component={MultasScreen} />
      <Tab.Screen name="devolucion" component={PerfilScreen} />
    </Tab.Navigator>
  );
}
