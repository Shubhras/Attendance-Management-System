import { createNativeStackNavigator } from '@react-navigation/native-stack';
import React from 'react';
import EmployeeInfoScreen from '../../../screens/EmployeeInfoScreen/index.js';
import MyEmployeeScreen from '../../../screens/MyEmployeeScreen/index.js';
import AttendanceScreen from '../../../screens/AttendanceScreen/index.js'

const Stack = createNativeStackNavigator();

const EmployeeStack = () => {
  return (
    <Stack.Navigator initialRouteName="MyEmployeeScreen">
      <Stack.Screen
        name="MyEmployeeScreen"
        component={MyEmployeeScreen}
        options={{ headerShown: false }}
      />
      <Stack.Screen
        name="EmployeeInfoScreen"
        component={EmployeeInfoScreen}
        options={{ headerShown: false }}
      />
       <Stack.Screen
        name="AttendanceScreen"
        component={AttendanceScreen}
        options={{ headerShown: false }}
      />

    </Stack.Navigator>
  );
};

export default EmployeeStack;
