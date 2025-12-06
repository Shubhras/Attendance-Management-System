import React, { FC, ReactNode } from 'react';
import { StatusBar, StyleSheet, View, ViewStyle, StatusBarStyle } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Colors } from '../../config/Colors';

interface CustomSafeAreaViewProps {
  statusBarBackgroundColor?: string;
  barStyle?: StatusBarStyle;
  children: ReactNode;
  style?: ViewStyle;
}

const CustomSafeAreaView: FC<CustomSafeAreaViewProps> = ({
  statusBarBackgroundColor ,
  barStyle,
  children,
  style,
}) => {

  return (
    <SafeAreaView style={[styles.container, style]}>
      <StatusBar
        backgroundColor={statusBarBackgroundColor ? statusBarBackgroundColor : Colors.white}
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