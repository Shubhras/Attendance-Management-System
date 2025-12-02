import { createNativeStackNavigator } from '@react-navigation/native-stack';
import React from 'react';
import WelcomeScreen from '../../screens/WelcomeScreen/index.js';
import HomeScreen from '../../screens/HomeScreen/index.js'
import AuthStack from '../../navigators/stacks/AuthStack/index.js'
import EmployeeStack from '../stacks/EmployeeStack/index.js'
import AddAttendanceStack from '../stacks/AddAttendanceStack/index.js'
import ContractorListScreen from '../../screens/ContractorListScreen/index.js'
import ProfileScreen from '../../screens/ProfileScreen/index.js'
import EditProfileScreen from '../../screens/EditProfileScreen/index.js'
import ChangePassword from '../../screens/ChangePassword/index.js'
import MyMachineScreen from '../../screens/MyMachineScreen/index.js';
import EmployeeListScreen from '../../screens/EmployeeListScreen/index.js';
import MyEmployeeScreen from '../../screens/MyEmployeeScreen/index.js';
import EmployeeInfoScreen from '../../screens/EmployeeInfoScreen/index.js';
import AttendanceScreen from '../../screens/AttendanceScreen/index.js';
import MachineEmployeeScreen from '../../screens/MachineEmployeeScreen/index.js'
import LogInScreen from '../../screens/LogInScreen/index.js';
import RegisterEmployeeScreen from '../../screens/RegisterEmployeeScreen/index.js';

import { useSelector } from 'react-redux';
const Stack = createNativeStackNavigator();

const Routes = () => {
  const user = useSelector(state => state?.users);
  const initial = user?.users?.access_token ? 'HomeScreen' : user?.welcomeFlag == false ? 'WelcomeScreen' : 'LogInScreen'
  return (
    <Stack.Navigator initialRouteName={initial}>
      <Stack.Screen
        name="WelcomeScreen"
        component={WelcomeScreen}
        options={{ headerShown: false }}
      />
      {/* <Stack.Screen
        name="AuthStack"
        component={AuthStack}
        options={{ headerShown: false }}
      /> */}
      <Stack.Screen
        name="LogInScreen"
        component={LogInScreen}
        options={{ headerShown: false }}
      />
      <Stack.Screen
        name="HomeScreen"
        component={HomeScreen}
        options={{ headerShown: false }}
      />
      {/* <Stack.Screen
        name="EmployeeStack"
        component={EmployeeStack}
        options={{ headerShown: false }}
      /> */}
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
      {/* <Stack.Screen
        name="AddAttendanceStack"
        component={AddAttendanceStack}
        options={{ headerShown: false }}
      /> */}
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
      <Stack.Screen
        name="ContractorListScreen"
        component={ContractorListScreen}
        options={{ headerShown: false }}
      />
      <Stack.Screen
        name="ProfileScreen"
        component={ProfileScreen}
        options={{ headerShown: false }}
      />
      <Stack.Screen
        name="EditProfileScreen"
        component={EditProfileScreen}
        options={{ headerShown: false }}
      />
      <Stack.Screen
        name="ChangePassword"
        component={ChangePassword}
        options={{ headerShown: false }}
      />
       <Stack.Screen
        name="MachineEmployeeScreen"
        component={MachineEmployeeScreen}
        options={{ headerShown: false }}
      />
       <Stack.Screen
        name="RegisterEmployeeScreen"
        component={RegisterEmployeeScreen}
        options={{ headerShown: false }}
      />




    </Stack.Navigator>
  );
};

export default Routes;
