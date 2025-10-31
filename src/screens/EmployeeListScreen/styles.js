import { StyleSheet } from 'react-native';
import { scale } from 'react-native-size-matters';
import {
  SCREEN_WIDTH,
  STANDARD_FLEX,
  STANDARD_SPACING,
} from '../../config/Constants';
import { Colors } from '../../config/Colors';

// Exporting style
export default StyleSheet.create({
  mainWrapper: {
    flex: STANDARD_FLEX,
    
  },
  searchView: {
    marginHorizontal: SCREEN_WIDTH * 0.05,
    marginTop: STANDARD_SPACING * 5,
  },
  textInputWrapper: {
    paddingHorizontal: scale(10),
    borderColor: Colors.grey,
    borderRadius: scale(10),
  },
  contentContainerStyle: {
    paddingHorizontal: SCREEN_WIDTH * 0.05,
    rowGap: scale(10),
    paddingBottom: scale(10)
  },
  flateList: {
    marginTop: STANDARD_SPACING * 4
  }
});
