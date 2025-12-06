import { createNativeStackNavigator } from '@react-navigation/native-stack';
import React from 'react';
import AttendanceEmployeeList from '../../screens/AttendanceEmployeeList';
import ChangePassword from '../../screens/ChangePassword/index.js';
import ContractorListScreen from '../../screens/ContractorListScreen/index.js';
import EditProfileScreen from '../../screens/EditProfileScreen/index.js';
import EmployeeAttendanceView from '../../screens/EmployeeAttendanceView';
import EmployeeInfoScreen from '../../screens/EmployeeInfoScreen/index.js';
import FingerPrintEmployeeList from '../../screens/FingerPrintEmployeeList';
import HomeScreen from '../../screens/HomeScreen/index.js';
import LogInScreen from '../../screens/LogInScreen/index.js';
import MachineEmployeeList from '../../screens/MachineEmployeeList';
import MarkAttendance from '../../screens/MarkAttendance';
import MyEmployee from '../../screens/MyEmployee';
import MyMachine from '../../screens/MyMachine';
import ProfileScreen from '../../screens/ProfileScreen/index.js';
import RegisterEmployeeScreen from '../../screens/RegisterEmployeeScreen/index.js';
import WelcomeScreen from '../../screens/WelcomeScreen/index.js';

import { useSelector } from 'react-redux';
const Stack = createNativeStackNavigator();

const Routes = () => {
  const user = useSelector(state => state?.users);
  const initial = user?.users?.access_token
    ? 'HomeScreen'
    : user?.welcomeFlag == false
    ? 'WelcomeScreen'
    : 'LogInScreen';
  return (
    <Stack.Navigator initialRouteName={initial}>
      <Stack.Screen
        name="WelcomeScreen"
        component={WelcomeScreen}
        options={{ headerShown: false, animation: 'none' }}
      />
      {/* <Stack.Screen
        name="AuthStack"
        component={AuthStack}
        options={{ headerShown: false }}
      /> */}
      <Stack.Screen
        name="LogInScreen"
        component={LogInScreen}
        options={{ headerShown: false, animation: 'none' }}
      />
      <Stack.Screen
        name="HomeScreen"
        component={HomeScreen}
        options={{ headerShown: false, animation: 'none' }}
      />
      {/* <Stack.Screen
        name="EmployeeStack"
        component={EmployeeStack}
        options={{ headerShown: false }}
      /> */}
      <Stack.Screen
        name="MyEmployee"
        component={MyEmployee}
        options={{ headerShown: false, animation: 'none' }}
      />
      <Stack.Screen
        name="EmployeeInfoScreen"
        component={EmployeeInfoScreen}
        options={{ headerShown: false, animation: 'none' }}
      />
      <Stack.Screen
        name="EmployeeAttendanceView"
        component={EmployeeAttendanceView}
        options={{ headerShown: false, animation: 'none' }}
      />
      {/* <Stack.Screen
        name="AddAttendanceStack"
        component={AddAttendanceStack}
        options={{ headerShown: false }}
      /> */}

      <Stack.Screen
        name="MarkAttendance"
        component={MarkAttendance}
        options={{ headerShown: false, animation: 'none' }}
      />
      <Stack.Screen
        name="MyMachine"
        component={MyMachine}
        options={{ headerShown: false, animation: 'none' }}
      />
      <Stack.Screen
        name="AttendanceEmployeeList"
        component={AttendanceEmployeeList}
        options={{ headerShown: false, animation: 'none' }}
      />
      <Stack.Screen
        name="ContractorListScreen"
        component={ContractorListScreen}
        options={{ headerShown: false, animation: 'none' }}
      />
      <Stack.Screen
        name="ProfileScreen"
        component={ProfileScreen}
        options={{ headerShown: false, animation: 'none' }}
      />
      <Stack.Screen
        name="EditProfileScreen"
        component={EditProfileScreen}
        options={{ headerShown: false, animation: 'none' }}
      />
      <Stack.Screen
        name="ChangePassword"
        component={ChangePassword}
        options={{ headerShown: false, animation: 'none' }}
      />
      <Stack.Screen
        name="MachineEmployeeList"
        component={MachineEmployeeList}
        options={{ headerShown: false, animation: 'none' }}
      />
      <Stack.Screen
        name="RegisterEmployeeScreen"
        component={RegisterEmployeeScreen}
        options={{ headerShown: false, animation: 'none' }}
      />
      <Stack.Screen
        name="FingerPrintEmployeeList"
        component={FingerPrintEmployeeList}
        options={{ headerShown: false, animation: 'none' }}
      />
    </Stack.Navigator>
  );
};

export default Routes;
