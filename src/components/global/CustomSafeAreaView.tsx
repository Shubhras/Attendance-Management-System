import React, { FC, ReactNode, useCallback } from 'react';
import { StatusBar, StyleSheet, View, ViewStyle } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { useFocusEffect } from '@react-navigation/native';

interface CustomSafeAreaViewProps {
  statusBarBackgroundColor?: string;
  barStyle?: 'default' | 'light-content' | 'dark-content';
  children: ReactNode;
  style?: ViewStyle;
}

/**
 * CustomSafeAreaView
 * - Handles SafeArea and StatusBar behavior consistently across screens
 * - Optional props for backgroundColor, barStyle, and container style
 */
const CustomSafeAreaView: FC<CustomSafeAreaViewProps> = ({
  statusBarBackgroundColor = '#ffffff',
  barStyle = 'dark-content',
  children,
  style,
}) => {
  // Update StatusBar when screen is focused
  useFocusEffect(
    useCallback(() => {
      StatusBar.setBackgroundColor(statusBarBackgroundColor, true);
      StatusBar.setBarStyle(barStyle, true);
    }, [statusBarBackgroundColor, barStyle])
  );

  return (
    <SafeAreaView
      style={[styles.container, { backgroundColor: statusBarBackgroundColor }, style]}>
      <StatusBar
        backgroundColor={statusBarBackgroundColor}
        barStyle={barStyle}
      />
      <View style={styles.inner}>{children}</View>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  inner: {
    flex: 1,
  },
});

export default CustomSafeAreaView;
 
