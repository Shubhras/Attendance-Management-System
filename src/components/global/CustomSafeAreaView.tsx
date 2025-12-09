import React, { FC, ReactNode } from 'react';
import {
  StyleSheet,
  View,
  ViewStyle,
  StatusBarStyle,
  Platform,
} from 'react-native';
import StatusBar from '../../components/others/Statusbar'
import { SafeAreaView } from 'react-native-safe-area-context';
import { Colors } from '../../config/Colors';

interface CustomSafeAreaViewProps {
  statusBarBackgroundColor?: string;
  barStyle?: StatusBarStyle;
  children: ReactNode;
  style?: ViewStyle;
}

const isAndroid14Plus = Platform.OS === 'android' && Platform.Version >= 34;

const CustomSafeAreaView: FC<CustomSafeAreaViewProps> = ({
  statusBarBackgroundColor,
  barStyle,
  children,
  style,
}) => {
  return (
    <SafeAreaView style={[styles.container, style]}>
      <StatusBar
        animated={true}
        translucent={isAndroid14Plus}
        backgroundColor={
          isAndroid14Plus
            ? 'transparent'
            : statusBarBackgroundColor ?? Colors.white
        }
        barStyle={barStyle || 'light-content'}
        hidden={false}
      />
      <View style={[styles.container, style]}>{children}</View>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
});

export default CustomSafeAreaView;


// import React, { FC, ReactNode, useContext } from 'react';
// import { Platform, StatusBar, StyleSheet, View, ViewStyle } from 'react-native';
// import { SafeAreaView } from 'react-native-safe-area-context';
// import { useFocusEffect } from '@react-navigation/native';
// import { Colors } from '../../config/Colors';
 
// interface CustomSafeAreaViewProps {
//   statusBarBackgroundColor?: string;
//   barStyle?: 'default' | 'light-content' | 'dark-content';
//   children: ReactNode;
//   style?: ViewStyle;
// }
 
// const CustomSafeAreaView: FC<CustomSafeAreaViewProps> = ({
//   statusBarBackgroundColor,
//   barStyle,
//   children,
//   style,
// }) => {
  
 
//   useFocusEffect(
//     React.useCallback(() => {
//       if (Platform.OS == 'android') {        
//         StatusBar.setBackgroundColor( Colors.primary, true);
//       }
//       StatusBar.setBarStyle( 'dark-content' , true);
//     }, [])
//   );
 
//   return (
//     <SafeAreaView style={[styles.container, style]}>
//       <StatusBar
//         backgroundColor={Colors.primary }
//         barStyle={ 'dark-content' }
//       />
//       <View style={[styles.container, style]}>{children}</View>
//     </SafeAreaView>
//   );
// };
 
// const styles = StyleSheet.create({
//   container: {
//     flex: 1,
//   },
// });
 
// export default CustomSafeAreaView;