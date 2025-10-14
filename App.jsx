/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 */

import { NewAppScreen } from '@react-native/new-app-screen';
import { Alert, Button, StatusBar, StyleSheet, useColorScheme, View } from 'react-native';
import {
  SafeAreaProvider,
  useSafeAreaInsets,
} from 'react-native-safe-area-context';
import { GestureHandlerRootView } from 'react-native-gesture-handler';
import { NavigationContainer } from '@react-navigation/native';
import { getDeviceInfo, captureFinger, DEFAULT_PID_OPTIONS } from 'react-native-rdservice-fingerprintscanner';
// import axios from 'axios';  // Backend ke liye



function App() {
  const isDarkMode = useColorScheme() === 'dark';
const enrollOrVerify = async (isEnroll = true) => {
  try {
    console.log("PLPLPPLL");
    
    const deviceInfo = await getDeviceInfo();
    console.log("PLPLPPLL1111111111111", deviceInfo);

    if (deviceInfo.isWhitelisted) {  // Device ready
      const response = await captureFinger(DEFAULT_PID_OPTIONS);  // Ya custom XML options
      if (response.status === 1) {
        const pidData = response.pidDataJson;  // JSON with fingerprint data (XML converted)
        // pidData mein 'data' field binary template hota hai
        if (isEnroll) {
          // Add: Template ko backend pe send kar store
          // await axios.post('http://your-backend/api/enroll', { userId: 'emp123', template: pidData.data });
          Alert.alert('Success', 'Fingerprint Enrolled!');
        } else {
          // Check: Backend se stored template fetch kar, compare (app ya backend pe)
          // const stored = await axios.get('http://your-backend/api/template/emp123');
          // Simple hash compare (not secure, use proper algo)
          if (pidData.data === stored.template) {  // Ya advanced matching
            // await axios.post('http://your-backend/api/attendance', { userId: 'emp123' });
            Alert.alert('Success', 'Attendance Verified!');
          } else {
            Alert.alert('Fail', 'Fingerprint Mismatch!');
          }
        }
      }
    }else{
       Alert.alert('Fail', deviceInfo.message);
    }
  } catch (error) {
    Alert.alert('Error', error.message);
  }
};
  return (
    <SafeAreaProvider>
      <StatusBar barStyle={isDarkMode ? 'light-content' : 'dark-content'} />
     
       <GestureHandlerRootView style={{flex:1}}>
     
        <NavigationContainer
        
        >
          <View style={{marginTop:90}}>

          <Button title='pk' onPress={()=>{
            enrollOrVerify()
          }} />
          </View>
 <AppContent />
        </NavigationContainer>
     
    </GestureHandlerRootView>
    </SafeAreaProvider>
  );
}

function AppContent() {
  const safeAreaInsets = useSafeAreaInsets();

  return (
    <View style={styles.container}>
      <NewAppScreen
        templateFileName="App.tsx"
        safeAreaInsets={safeAreaInsets}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
});

export default App;
