import { StyleSheet } from 'react-native';
import { scale } from 'react-native-size-matters';
import {
  FONT_SIZE_MD,
  POPPINS_SEMIBOLD,
  STANDARD_FLEX,
  STANDARD_SPACING,
} from '../../config/Constants';

// Exporting style
export default StyleSheet.create({
  mainWrapper: {
    flex: STANDARD_FLEX,
  },
  sectionTitleWrapper: {
    marginHorizontal: STANDARD_SPACING * 3,
    marginBottom: STANDARD_SPACING * 2,
  },
  sectionTitle: {
    fontFamily: POPPINS_SEMIBOLD,
    fontSize: FONT_SIZE_MD,
  },
});
