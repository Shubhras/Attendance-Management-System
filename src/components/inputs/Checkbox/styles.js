import {StyleSheet} from 'react-native';
import {STANDARD_CHECKBOX_SIZE} from '../../../config/Constants';

// Exporting style
export default StyleSheet.create({
  checkbox: {
    alignItems: 'center',
    justifyContent: 'center',
    width: STANDARD_CHECKBOX_SIZE,
    height: STANDARD_CHECKBOX_SIZE,
    borderRadius: STANDARD_CHECKBOX_SIZE * 0.2,
  },
});
