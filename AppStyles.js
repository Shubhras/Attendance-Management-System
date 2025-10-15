import {StyleSheet} from 'react-native';
import {Colors} from './src/config/Colors';

// Declaring constants
const STANDARD_FLEX = 1;

// Creating stylesheets
export default StyleSheet.create({
  gestureHandlerRootView: {
    flex: STANDARD_FLEX,
  },
  topSafeAreaView: {
    flex: STANDARD_FLEX,
  },
  bottomSafeAreaView: {
    flex: 0,
    backgroundColor: Colors.white,
  },
});
