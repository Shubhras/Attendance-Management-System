import React, { FC, ReactNode } from 'react';
import {
  StatusBar,
  StatusBarStyle,
  StyleSheet,
  View,
  ViewStyle
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Colors } from '../../config/Colors';
 
interface CustomSafeAreaViewProps {
  statusBarBackgroundColor?: string;
  barStyle?: StatusBarStyle;
  children: ReactNode;
  style?: ViewStyle;
}
 
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
        translucent
        backgroundColor={statusBarBackgroundColor ?? Colors.white}
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