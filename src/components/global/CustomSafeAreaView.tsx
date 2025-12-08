import React, { FC, ReactNode } from 'react';
import {
  StatusBar,
  StyleSheet,
  View,
  ViewStyle,
  StatusBarStyle,
  Platform,
} from 'react-native';
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
        translucent={isAndroid14Plus}
        backgroundColor={
          isAndroid14Plus
            ? 'transparent'
            : statusBarBackgroundColor ?? Colors.white
        }
        barStyle={barStyle || 'light-content'}
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
