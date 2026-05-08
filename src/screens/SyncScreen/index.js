// import React, { useEffect, useState } from 'react';
// import {
//   View,
//   StatusBar,
//   Text,
//   ActivityIndicator,
//   StyleSheet,
// } from 'react-native';
// import * as Animatable from 'react-native-animatable';
// import { useSelector } from 'react-redux';
// import { createTable, resetTable, syncEmployees } from '../../../db';
// import Button from '../../components/buttons/Button';

// const SyncScreen = ({ navigation }) => {
//   const user = useSelector(state => state?.users);
//   const access_token = user?.users?.access_token;

//   const [progressText, setProgressText] = useState('Starting...');
//   const [progress, setProgress] = useState(0);
//   const [error, setError] = useState(null);
//   const [loading, setLoading] = useState(true);

//   useEffect(() => {
//     if (access_token) {
//       init();
//     }
//   }, [access_token]);

//   const init = async () => {
//     try {
//       setLoading(true);
//       setError(null);
//       setProgress(0);

//       setProgressText('Resetting database...');
//       setProgress(20);
//       await resetTable();

//       setProgressText('Creating database...');
//       setProgress(40);
//       await createTable();

//       setProgressText('Syncing employees...');
//       setProgress(70);

//       const result = await syncEmployees(access_token);

//       if (!result?.success) {
//         setError(result?.message || 'No employees found ❌');
//         setLoading(false);
//         return;
//       }

//       setProgressText('Finalizing...');
//       setProgress(100);

//       setTimeout(() => {
//         navigation.replace('HomeScreen');
//       }, 800);
//     } catch (e) {
//       console.log(e);
//       setError('Something went wrong ❌');
//       setLoading(false);
//     }
//   };

//   return (
//     <Animatable.View
//       style={styles.mainWrapper}
//       delay={100}
//       animation="fadeIn"
//       easing="ease-in-out-sine"
//       useNativeDriver
//     >
//       {/* StatusBar */}
//       <StatusBar translucent backgroundColor="transparent" barStyle="light-content" />

//       <View style={styles.imageBackground}>
//         <View style={styles.imageBackgroundOverlay}>

//           {/* Logo */}
//           <View style={styles.logoWrapper}>
//             <Animatable.Image
//               source={require('../../assets/images/logo.png')}
//               style={styles.logo}
//               delay={600}
//               animation="fadeInDown"
//               easing="ease-in-out-back"
//               useNativeDriver
//             />
//           </View>

//           {/* LOADING */}
//           {loading && (
//             <>
//               <ActivityIndicator size="large" color="#fff" />

//               <Text style={{ marginTop: 20, color: '#fff', fontSize: 14 }}>
//                 {progressText}
//               </Text>

//               {/* Progress Bar */}
//               <View
//                 style={{
//                   width: '70%',
//                   height: 8,
//                   backgroundColor: 'rgba(255,255,255,0.3)',
//                   borderRadius: 10,
//                   marginTop: 15,
//                   overflow: 'hidden',
//                 }}
//               >
//                 <View
//                   style={{
//                     height: '100%',
//                     width: `${progress}%`,
//                     backgroundColor: '#fff',
//                   }}
//                 />
//               </View>

//               <Text style={{ marginTop: 10, color: '#fff', fontWeight: 'bold' }}>
//                 {progress}%
//               </Text>
//             </>
//           )}

//           {/* ERROR */}
//           {!loading && error && (
//             <>
//               <Text style={{ color: '#fff', marginTop: 20, fontSize: 16, textAlign: 'center' }}>
//                 {error}
//               </Text>

//               <Button label="Retry" onPress={init} style={{ marginTop: 20, width: 150 }} />
//             </>
//           )}

//         </View>
//       </View>
//     </Animatable.View>
//   );
// };

// export default SyncScreen;
// const styles = StyleSheet.create({
//   mainWrapper: {
//     flex: 1,
//   },

//   imageBackground: {
//     flex: 1,
//     backgroundColor: '#D61313',
//   },

//   imageBackgroundOverlay: {
//     flex: 1,
//     justifyContent: 'center',
//     alignItems: 'center',
//   },

//   logoWrapper: {
//     width: 140,
//     height: 140,
//     borderRadius: 70,
//     backgroundColor: '#fff',
//     justifyContent: 'center',
//     alignItems: 'center',
//     marginBottom: 40,
//   },

//   logo: {
//     width: '80%',
//     height: '80%',
//     resizeMode: 'contain',
//   },

//   syncText: {
//     marginTop: 20,
//     color: '#fff',
//     fontSize: 14,
//   },

//   progressBar: {
//     width: '70%',
//     height: 8,
//     backgroundColor: 'rgba(255,255,255,0.3)',
//     borderRadius: 10,
//     marginTop: 15,
//     overflow: 'hidden',
//   },

//   progressFill: {
//     height: '100%',
//     backgroundColor: '#fff',
//   },

//   percent: {
//     marginTop: 10,
//     color: '#fff',
//     fontWeight: 'bold',
//   },

//   errorText: {
//     color: '#fff',
//     marginTop: 20,
//     fontSize: 16,
//     textAlign: 'center',
//   },

//   button: {
//     marginTop: 20,
//     width: 150,
//   },
// });


// import { View, ImageBackground, StatusBar, Image } from 'react-native';
// import styles from './styles';

// // Functional component
// const SyncScreen = () => {
//   return (
//     <View
//       style={[styles.mainWrapper]}
//       delay={100}
//       animation="fadeIn"
//       easing="ease-in-out-sine"
//       useNativeDriver={true}
//     >
//       {/* StatusBar */}
//       <StatusBar
//         translucent={true}
//         backgroundColor="transparent"
//         barStyle="light-content" // or "dark-content" based on your background
//       />
//       <View style={styles.imageBackground}>
//         <View style={styles.imageBackgroundOverlay}>
//           {/* Logo wrapper */}
//           <View style={styles.logoWrapper}>
//             {/* Logo */}
//             <Image
//               source={require('../../assets/images/logo.png')}
//               style={styles.logo}
//               delay={600}
//               animation="fadeInDown"
//               easing="ease-in-out-back"
//               useNativeDriver={true}
//             />
//           </View>
//         </View>
//       </View>
//        {/* LOADING */}
//          {loading && (
//             <>
//               <ActivityIndicator size="large" color="#fff" />

//               <Text style={{ marginTop: 20, color: '#fff', fontSize: 14 }}>
//                 {progressText}
//               </Text>

//               {/* Progress Bar */}
//               <View
//                 style={{
//                   width: '70%',
//                   height: 8,
//                   backgroundColor: 'rgba(255,255,255,0.3)',
//                   borderRadius: 10,
//                   marginTop: 15,
//                   overflow: 'hidden',
//                 }}
//               >
//                 <View
//                   style={{
//                     height: '100%',
//                     width: `${progress}%`,
//                     backgroundColor: '#fff',
//                   }}
//                 />
//               </View>

//               <Text style={{ marginTop: 10, color: '#fff', fontWeight: 'bold' }}>
//                 {progress}%
//               </Text>
//             </>
//           )}

//           {/* ERROR */}
//           {!loading && error && (
//             <>
//               <Text style={{ color: '#fff', marginTop: 20, fontSize: 16, textAlign: 'center' }}>
//                 {error}
//               </Text>

//               <Button label="Retry" onPress={init} style={{ marginTop: 20, width: 150 }} />
//             </>
//           )}
//     </View>
//   );
// };

// // Exporting
// export default SyncScreen;


import React, { useEffect, useState } from 'react';
import {
  View,
  StatusBar,
  Image,
  ActivityIndicator,
  Text,
} from 'react-native';
import { useSelector } from 'react-redux';
import { createTable, resetTable, syncEmployees } from '../../../db';
import Button from '../../components/buttons/Button';
import styles from './styles';
import { Colors, LightThemeColors } from '../../config/Colors';

const SyncScreen = ({ navigation }) => {
  const user = useSelector(state => state?.users);
  const access_token = user?.users?.access_token;

  const [loading, setLoading] = useState(true);
  const [progress, setProgress] = useState(0);
  const [progressText, setProgressText] = useState('Starting...');
  const [error, setError] = useState(null);

  useEffect(() => {
    if (access_token) {
      init();
    }
  }, [access_token]);

  const init = async () => {
    try {
      setLoading(true);
      setError(null);
      setProgress(0);

      // STEP 1
      setProgressText('Resetting database...');
      setProgress(20);
      await resetTable();

      // STEP 2
      setProgressText('Creating database...');
      setProgress(40);
      await createTable();

      // STEP 3
      setProgressText('Syncing employees...');
      setProgress(70);

      const result = await syncEmployees(access_token);
        console.log("resultresultresultresult",result);
        
      if (!result?.success) {
        setError(result?.message || 'No employees found ❌');
        setLoading(false);
        return;
      }

      // STEP 4
      setProgressText('Finalizing...');
      setProgress(100);

      setTimeout(() => {
        navigation.replace('HomeScreen');
      }, 800);

    } catch (e) {
      console.log(e);
      setError('Something went wrong ❌');
      setLoading(false);
    }
  };

  return (
    <View style={styles.mainWrapper}>
      <StatusBar translucent backgroundColor="transparent" barStyle="light-content" />

      <View style={styles.imageBackground}>
        <View style={styles.imageBackgroundOverlay}>

          {/* LOGO */}
          <View style={styles.logoWrapper}>
            <Image
              source={require('../../assets/images/logo.png')}
              style={styles.logo}
            />
          </View>

          {/* LOADING */}
          {loading && (
            <>
              <ActivityIndicator size="large" color="#fff" />

              <Text style={styles.syncText}>{progressText}</Text>

              <View style={styles.progressBar}>
                <View
                  style={[styles.progressFill, { width: `${progress}%` }]}
                />
              </View>

              <Text style={styles.percent}>{progress}%</Text>
            </>
          )}

          {/* ERROR */}
          {!loading && error && (
            <>
              <Text style={styles.errorText}>{error}</Text>

              <Button
                label={'Retry'}
                labelColor={Colors.white}
                backgroundColor={LightThemeColors.titleColor}
                onPress={init}
                style={styles.button}
              />
            </>
          )}

        </View>
      </View>
    </View>
  );
};

export default SyncScreen;