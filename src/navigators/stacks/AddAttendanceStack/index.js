import { createNativeStackNavigator } from '@react-navigation/native-stack';
import React from 'react';
import MyMachineScreen from '../../../screens/MyMachineScreen';
import EmployeeListScreen from '../../../screens/EmployeeListScreen/index.js'
 
const Stack = createNativeStackNavigator();

const AddAttendanceStack = () => {
  return (
    <Stack.Navigator initialRouteName="MyMachineScreen">
      <Stack.Screen
        name="MyMachineScreen"
        component={MyMachineScreen}
        options={{ headerShown: false }}
      />
       <Stack.Screen
        name="EmployeeListScreen"
        component={EmployeeListScreen}
        options={{ headerShown: false }}
      />
      
    </Stack.Navigator>
  );
};

export default AddAttendanceStack;
