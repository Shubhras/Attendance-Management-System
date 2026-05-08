import React, { useRef, useState } from 'react';
import {
  View,
  Image,
  ScrollView,
  TouchableOpacity,
  ActivityIndicator,
  KeyboardAvoidingView,
  Platform,
} from 'react-native';
import { useDispatch, useSelector } from 'react-redux';
import { Formik } from 'formik';
import * as Yup from 'yup';
import { scale } from 'react-native-size-matters';
import { showMessage } from 'react-native-flash-message';
import { CustomText } from '../../components/global/CustomComponents';
import TextInput from '../../components/inputs/TextInput';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView';
import Icons from '../../components/Icons/Icons';
import styles from './styles';
import { Colors, LightThemeColors } from '../../config/Colors';
import { loginUser } from '../../redux/slices/SessionUser';
import { LoginAPI } from '../../api/auth';
import { createTable, resetTable, syncEmployees } from '../../../db';

const LoginSchema = Yup.object().shape({
  userId: Yup.string().required('User ID is required'),
  password: Yup.string().required('Password is required'),
});

const LogInScreen = ({ navigation }) => {
  const dispatch = useDispatch();
  const passwordRef = useRef(null);
  const [hidePassword, setHidePassword] = useState(true);
  const [loading, setLoading] = useState(false);
  const user = useSelector(state => state.users.users);
  console.log('user6666', user);

  const init = async access_token => {
    await resetTable();
    await createTable(); // create DB
    await syncEmployees(access_token); // API → DB
  };

  const loginUserButton = async values => {
    setLoading(true);
    const data = {
      email: values.userId.trim(),
      password: values.password,
    };
    console.log('data', data);

    try {
      const response = await LoginAPI(data);
      setLoading(false);
      console.log('response111111', response);
      if (response?.status === 200) {
        const tokenData = {
          access_token: response?.token,
          user: response?.user,
        };
        dispatch(loginUser(tokenData));
        init(response?.token);
        navigation.reset({
          index: 0,
          routes: [{ name: 'HomeScreen' }],
        });
      } else {
        showMessage({
          message: 'Error',
          description: response?.message || 'Login failed',
          type: 'danger',
        });
      }
    } catch (error) {
      setLoading(false);
      console.log('error', error);
      showMessage({
        message: 'Error',
        description: error?.message || 'Something went wrong',
        type: 'danger',
      });
    }
  };

  return (
    <CustomSafeAreaView
      statusBarBackgroundColor={Colors.white}
      barStyle={'dark-content'}
      style={[styles.mainWrapper, { backgroundColor: Colors.white }]}
    >
      <KeyboardAvoidingView
        style={{ flex: 1 }}
        keyboardVerticalOffset={Platform.OS === 'ios' ? scale(50) : scale(50)}
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      >
        <ScrollView
          bounces={false}
          showsVerticalScrollIndicator={false}
          overScrollMode="never"
        >
          <View style={styles.logoContainer}>
            <View style={styles.logoWrapper}>
              <Image
                style={styles.logoImage}
                source={require('../../assets/images/logo.png')}
              />
            </View>
          </View>

          <Formik
            initialValues={{ userId: '', password: '' }}
            validationSchema={LoginSchema}
            onSubmit={loginUserButton}
          >
            {({
              handleChange,
              handleBlur,
              handleSubmit,
              values,
              errors,
              touched,
            }) => (
              <>
                <View style={styles.textInputWrapper}>
                  <TextInput
                    label="User ID"
                    placeholder="Enter user id"
                    value={values.userId}
                    onChangeText={handleChange('userId')}
                    onBlur={handleBlur('userId')}
                    errors={touched.userId && errors.userId}
                    returnKeyType="next"
                    keyboardType={'email-address'}
                    autoCapitalize="none"
                    onSubmitEditing={() => passwordRef.current?.focus()}
                  />
                </View>

                <View style={styles.textInputWrapper}>
                  <TextInput
                    refText={passwordRef}
                    label="Password"
                    placeholder="Enter password"
                    value={values.password}
                    onChangeText={handleChange('password')}
                    onBlur={handleBlur('password')}
                    hidePassword={hidePassword}
                    errors={touched.password && errors.password}
                    rightIcon={
                      <TouchableOpacity
                        style={{ width: scale(40) }}
                        onPress={() => setHidePassword(!hidePassword)}
                      >
                        <Icons
                          name={hidePassword ? 'eye-off-sharp' : 'eye'}
                          iconType={'Ionicons'}
                          color={Colors.black}
                          size={scale(20)}
                        />
                      </TouchableOpacity>
                    }
                    returnKeyType="done"
                    onSubmitEditing={handleSubmit}
                  />
                </View>

                <View style={styles.buttonWrapper}>
                  <TouchableOpacity
                    style={[
                      styles.button,
                      {
                        backgroundColor: LightThemeColors.titleColor,
                        flexDirection: 'row',
                        justifyContent: 'center',
                        alignItems: 'center',
                      },
                    ]}
                    onPress={handleSubmit}
                    disabled={loading}
                  >
                    {loading ? (
                      <ActivityIndicator color={Colors.white} size="small" />
                    ) : (
                      <CustomText
                        style={[styles.label, { color: Colors.white }]}
                      >
                        Login
                      </CustomText>
                    )}
                  </TouchableOpacity>
                </View>
              </>
            )}
          </Formik>
        </ScrollView>
      </KeyboardAvoidingView>
    </CustomSafeAreaView>
  );
};

export default LogInScreen;

// import React, { useEffect, useState } from "react";
// import { View, Text, TouchableOpacity, StyleSheet, ActivityIndicator } from "react-native";
// import { getEmployees } from "../../../db";
// import Morfin from "../../../MorfinAuth";
// import { useDispatch, useSelector } from "react-redux";
// import { addEmployee, loginUser } from "../../redux/slices/SessionUser";

// const LogInScreen = () => {
//   const [loading, setLoading] = useState(false);
//   const [status, setStatus] = useState("");
//   const [result, setResult] = useState(null);
//   const dispatch = useDispatch();
//   const user = useSelector(state => state.users.users);
//   const employeeList = useSelector(state => state.users.employeeList);
//   console.log("useruseruseruser",user);
//   console.log("employeeListemployeeList",employeeList);

//   // // 🔥 MAIN FUNCTION
//   // const handleScan = async () => {
//   //   try {
//   //     setLoading(true);
//   //     setStatus("Checking device...");

//   //     // 1. Device check
//   //     const connected = await Morfin.isDeviceConnected();

//   //     if (!connected) {
//   //       setStatus("❌ Device not connected");
//   //       setLoading(false);
//   //       return;
//   //     }

//   //     setStatus("Initializing device...");

//   //     // 2. Init device
//   //     await Morfin.initDevice();

//   //     setStatus("Place finger on scanner...");

//   //     // 3. Capture
//   //     await Morfin.autoCapture(60, 10000);

//   //     // 4. Get template
//   //     const capturedTemplate = await Morfin.getTemplate();
//   //     dispatch(loginUser(capturedTemplate));
//   //     setStatus("Matching...");

//   //     // 5. Load employees from DB
//   //     const employees = await getEmployees("MFS500");

//   //     if (employees.length === 0) {
//   //       setStatus("No employees found");
//   //       setLoading(false);
//   //       return;
//   //     }

//   //     // 6. Extract templates
//   //     const templates = employees.map(e => e.fingerprintdata);

//   //     // 7. Fast match (NATIVE)
//   //     const res = await Morfin.matchTemplatesFast(capturedTemplate, templates);

//   //     // 8. Result
//   //     if (res.matched) {
//   //       const emp = employees[res.index];

//   //       setResult(emp);
//   //       setStatus(`✅ Matched: ${emp.name} (${emp.empId})`);

//   //       // 👉 attendance API call yaha kar sakta hai
//   //     } else {
//   //       setResult(null);
//   //       setStatus("❌ No match found");
//   //     }

//   //     setLoading(false);

//   //   } catch (error) {
//   //     console.log(error);
//   //     setStatus("Error: " + error.message);
//   //     setLoading(false);
//   //   }
//   // };

//   const handleScan = async () => {

//     try {
//       setLoading(true);
//       setStatus("Checking device...");

//       // 1. Device check
//       const connected = await Morfin.isDeviceConnected();

//       if (!connected) {
//         setStatus("❌ Device not connected");
//         setLoading(false);
//         return;
//       }

//       setStatus("Initializing device...");

//       // 2. Init device
//       await Morfin.initDevice();

//       setStatus("Place finger on scanner...");

//       // 3. Capture
//       await Morfin.autoCapture(60, 10000);

//       // 4. Get template
//       const capturedTemplate = await Morfin.getTemplate();
//       dispatch(loginUser(capturedTemplate));
//       // dispatch(addEmployee({
//       //   id: 5001,
//       //   name: "New Employee",
//       //   empId: "EMP" + Date.now(),
//       //   fingerprintdata: capturedTemplate,
//       //   machinename: "MFS500"
//       // }));
//       console.log("Captured Template:", capturedTemplate);

//       setStatus("Loading employees...");

//       // 5. Load employees from DB
//       const employees = await getEmployees("MFS500");

//       if (!employees.length) {
//         setStatus("No employees found");
//         setLoading(false);
//         return;
//       }

//       // ✅ IMPORTANT: filter invalid templates
//       const validEmployees = employees.filter(
//         e => e.fingerprintdata && e.fingerprintdata.length > 50
//       );

//       if (!validEmployees.length) {
//         setStatus("No valid fingerprint data");
//         setLoading(false);
//         return;
//       }

//       // 6. Extract templates
//       const templates = validEmployees.map(e => e.fingerprintdata);

//       setStatus("Matching fingerprint...");

//       // 7. Native match
//       const res = await Morfin.matchTemplatesFast(
//         capturedTemplate,
//         templates
//       );

//       console.log("Match Result:", res);

//       // 8. Result
//       if (res.matched && res.index !== -1) {
//         const emp = validEmployees[res.index];

//         setResult(emp);
//         setStatus(`✅ Matched: ${emp.name} (${emp.empId})`);

//         // ✅ NOW CALL API (ONLY empId)
//         // markAttendance(emp);

//       } else {
//         setResult(null);
//         setStatus("❌ No match found");
//       }

//     } catch (error) {
//       console.log("Error:", error);
//       setStatus("Error: " + error.message);
//     } finally {
//       setLoading(false);
//     }
//   };

//   return (
//     <View style={styles.container}>
//       <Text style={styles.title}>Fingerprint Attendance</Text>

//       <TouchableOpacity style={styles.button} onPress={handleScan}>
//         <Text style={styles.buttonText}>Scan Finger</Text>
//       </TouchableOpacity>

//       {loading && <ActivityIndicator size="large" color="#000" />}

//       {status !== "" && <Text style={styles.status}>{status}</Text>}

//       {result && (
//         <View style={styles.card}>
//           <Text style={styles.resultText}>Name: {result.name}</Text>
//           <Text style={styles.resultText}>Emp ID: {result.empId}</Text>
//         </View>
//       )}
//     </View>
//   );
// };

// export default LogInScreen;

// const styles = StyleSheet.create({
//   container: {
//     flex: 1,
//     justifyContent: "center",
//     alignItems: "center",
//     padding: 20,
//   },
//   title: {
//     fontSize: 22,
//     fontWeight: "bold",
//     marginBottom: 20,
//   },
//   button: {
//     backgroundColor: "#007bff",
//     padding: 15,
//     borderRadius: 10,
//   },
//   buttonText: {
//     color: "#fff",
//     fontSize: 16,
//   },
//   status: {
//     marginTop: 20,
//     fontSize: 16,
//     textAlign: "center",
//   },
//   card: {
//     marginTop: 20,
//     padding: 15,
//     borderWidth: 1,
//     borderRadius: 10,
//     width: "80%",
//   },
//   resultText: {
//     fontSize: 16,
//   },
// });
