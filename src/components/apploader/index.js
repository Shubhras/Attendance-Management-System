import React from 'react';
import { View, StyleSheet } from 'react-native';
import { SkypeIndicator, BarIndicator } from 'react-native-indicators';
import { FONT_SIZE_SM } from '../../config/Constants';

const styles = StyleSheet.create({
  container: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: 'rgba(0, 0, 0, 0.30)',
    position: 'absolute',
    top: 0,
    right: 0,
    left: 0,
    bottom: 0,
    borderRadius: 0,
  },
  containerBarIndicators: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: 'rgba(0, 0, 0, 0)',
    position: 'absolute',
    top: 0,
    right: 0,
    left: 0,
    bottom: 0,
  },
  indicatorContainer: {
    width: 100,
    height: 100,
    borderRadius: 0,
    alignItems: 'center',
    justifyContent: 'center',
    position: 'absolute',
  },
  text: {
    color: 'white',
    fontSize: FONT_SIZE_SM,
    marginBottom: 20,
  },
});

export const Indicators = () => {
  return (
    <View style={styles.container}>
      <View style={styles.indicatorContainer}>
        <SkypeIndicator color="#fff" size={50} animationDuration={1000} />
      </View>
    </View>
  );
};

export const BarIndicators = () => {
  return (
    <View style={styles.containerBarIndicators}>
      <View style={styles.indicatorContainer}>
        <BarIndicator color="blue" size={40} animationDuration={1000} />
      </View>
    </View>
  );
};
