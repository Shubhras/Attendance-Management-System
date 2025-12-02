//import liraries
import { NavigationContainer } from '@react-navigation/native';
import React, { useEffect, useState } from 'react';
import { GestureHandlerRootView } from 'react-native-gesture-handler';
import AppStyles from './AppStyles';
import Routes from './src/navigators/Routes';
import Splash from './src/screens/Splash';
import FlashMessage from 'react-native-flash-message';
import { Provider } from 'react-redux';
import store, { persistor } from './src/redux/store/Store';
import { PersistGate } from 'redux-persist/integration/react';

// create a component
const App = () => {
  // Local states
  const [isStarting, setIsStarting] = useState(true);

  // Hooks
  useEffect(() => {
    // Updating state value after 3500 milliseconds(3.5 seconds) of delay. You may change the value of milliseconds(3500) as per your need.
    setTimeout(() => {
      // Updating states
      setIsStarting(false);
    }, 3500);
  }, []);

  // Checking
  if (isStarting) {
    // Returning
    return <Splash />;
  }

  return (
    <GestureHandlerRootView style={AppStyles.gestureHandlerRootView}>
      <Provider store={store}>
      <PersistGate loading={null} persistor={persistor}>
        <NavigationContainer>
          <Routes />
          <FlashMessage position="top" />
        </NavigationContainer>
        </PersistGate>
      </Provider>

    </GestureHandlerRootView>
  );
};

export default App;

// //import liraries
// import React, { useEffect, useState } from 'react';
// import {
//   Button,
//   View,
//   StyleSheet,
//   Alert,
//   Text,
//   ScrollView,
//   // ToastAndroid,
//   // Clipboard,
// } from 'react-native';
// import {
//   getDeviceInfo,
//   captureFinger,
//   isDriverFound,
//   openFingerPrintScanner,
//   AVAILABLE_PACKAGES,
//   DEFAULT_PID_OPTIONS,
// } from 'react-native-rdservice-fingerprintscanner';
// import { fingerPrintAdd } from './src/api/auth'
// import Morfin from './MorfinAuth';

// import FingerprintScreen from './FingerprintScreen';
// import FingerprintScreenMantra from './FingerprintScreenMantra';

// const App = () => {
//   const [txt, setTxt] = useState('');
//   const [verifytxt, setVerifyTxt] = useState('');
//   const [firstCapture, setFirstCapture] = useState(null);
//   const [secondCapture, setSecondCapture] = useState(null);
//   const [resultsVerify, setResultsVerify] = useState('');

//   const test = async () => {
//     const connected = await Morfin.isDeviceConnected();
//     console.log("Connected:", connected);
  
//     const info = await Morfin.initDevice();
//     console.log("Device Info:", info);
  
//     const result = await Morfin.autoCapture(60, 10000);
//     console.log("Captured:", result);
  
//     const template = await Morfin.getTemplate();
//     console.log("Template length:", template.length);
  
//     const imgBase64 = await Morfin.getImage();
//     console.log("Image Base64:", imgBase64);
//   };
//   // useEffect(()=>{

//   //   test();
//   // },[])
  
//   // const copyJSON = jsonData => {
//   //   Clipboard.setString(JSON.stringify(jsonData, null, 2));
//   //   ToastAndroid.show('Copied to Clipboard!', ToastAndroid.SHORT);
//   // };
//   // 👉 1. Get device info
//   const getMachine = () => {
//     getDeviceInfo()
//       .then(res => {
//         console.log(res, 'DEVICE DRIVER FOUND');
//         Alert.alert('DEVICE DRIVER FOUND');
//         setTxt(JSON.stringify(res));
//       })
//       .catch(err => {
//         console.log(err, 'DEVICE DRIVER NOT FOUND');
//         Alert.alert('DEVICE nOT DRIVER FOUND', err);
//         setTxt(err);
//       });
//   };

//   // 👉 2. Capture Finger
//   const handleCaptureFinger = () => {
//     captureFinger(DEFAULT_PID_OPTIONS)
//       .then(res => {
//         console.log(res, 'FINGER CAPTURE');
//         setFirstCapture(res);
//         setTxt(JSON.stringify(res));
//         handleAPI(res)
//       })
//       .catch(err => {
//         console.log(err, 'ERROR_FINGER_CAPTURE');
//         setTxt(JSON.stringify(err));
//       });
//   };

//   const handleAPI = (fingData) => {
//     const payload ={
//       thumb_template_data:fingData
//     }
//     fingerPrintAdd(payload)
//       .then(res => {
//         console.log(res, 'FINGER CAPTURE');
//         setFirstCapture(res);
//         setTxt(JSON.stringify(res));
//       })
//       .catch(err => {
//         console.log(err, 'ERROR_FINGER_CAPTURE');
//         setTxt(JSON.stringify(err));
//       });
//   };

//   // 👉 3. Check if driver found
//   const checkDriver = () => {
//     isDriverFound(AVAILABLE_PACKAGES.MANTRA) // Use package you need
//       .then(res => {
//         console.log(res, 'DRIVER CHECK');
//         setTxt(JSON.stringify(res));
//       })
//       .catch(err => {
//         console.log(err, 'ERROR_DRIVER_CHECK');
//         setTxt(JSON.stringify(err));
//       });
//   };

//   // 👉 4. Open Scanner
//   const openScanner = () => {
//     openFingerPrintScanner(AVAILABLE_PACKAGES.MANTRA, DEFAULT_PID_OPTIONS)
//       .then(res => {
//         console.log(res, 'FINGER CAPTURE VIA SCANNER');
//         setTxt(JSON.stringify(res));
//       })
//       .catch(err => {
//         console.log(err, 'ERROR_OPEN_SCANNER');
//         setTxt(JSON.stringify(err));
//       });
//   };

//   // 👉 2. Capture Finger Verify
//   const handleVerifyCaptureFinger = () => {
//     captureFinger(DEFAULT_PID_OPTIONS)
//       .then(res => {
//         console.log(res, 'FINGER CAPTURE');
//         setVerifyTxt(JSON.stringify(res));
//         setSecondCapture(res);
//       })
//       .catch(err => {
//         console.log(err, 'ERROR_FINGER_CAPTURE');
//         setVerifyTxt(JSON.stringify(err));
//       });
//   };

//   const verifyFingerprints = () => {
//     if (!firstCapture || !secondCapture) {
//       return console.log('Please capture both fingerprints first!');
//     }

//     // Convert both to string for comparison
//     const first = JSON.stringify(firstCapture);
//     const second = JSON.stringify(secondCapture);

//     if (first === second) {
//       console.log('MATCHED ✔ Fingerprint data is identical');
//       setResultsVerify('MATCHED ✔ Fingerprint data is identical')
//     } else {
//       console.log('NOT MATCHED ✘ Fingerprint data is different');
//       setResultsVerify('NOT MATCHED ✘ Fingerprint data is different')
//     }
//   };

//   return (
//     <ScrollView style={{backgroundColor:'#fff', paddingTop:10}}>
//       {/* <FingerprintScreen /> */}

//       <FingerprintScreenMantra />
//       {/* <View style={styles.container}>
//         <Button title="Get Machine Info" onPress={getMachine} />

//         <Button title="Capture Finger" onPress={handleCaptureFinger} />
//         <Button
//           title="Capture Finger Verify"
//           onPress={handleVerifyCaptureFinger}
//         />

//         <Button title="Check Driver" onPress={checkDriver} />

//         <Button title="Open Fingerprint Scanner" onPress={openScanner} /> */}
//         {/* <Button title="COpy first" onPress={()=>{copyJSON(txt)}} />
//         <Button title="COpy second" onPress={()=>{copyJSON(verifytxt)}} /> */}

//         {/* <Button title="Verify" onPress={verifyFingerprints} />

//         <Text>{resultsVerify}</Text>
//         <Text>{txt}</Text>
//       </View> */}
//     </ScrollView>
//   );
// };

// export default App;

// const styles = StyleSheet.create({
//   container: {
//     padding: 20,
//     gap: 20,
//   },
// });
