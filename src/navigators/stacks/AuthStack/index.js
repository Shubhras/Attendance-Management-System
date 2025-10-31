

import { createNativeStackNavigator } from '@react-navigation/native-stack';
import React from 'react';
import LogInScreen from '../../../screens/LogInScreen/index.js';

const Stack = createNativeStackNavigator();

const AuthStack = () => {
  return (
    <Stack.Navigator initialRouteName="LogInScreen">
       <Stack.Screen
        name="LogInScreen"
        component={LogInScreen}
        options={{ headerShown: false }}
      />
    </Stack.Navigator>
  );
};

export default AuthStack;
