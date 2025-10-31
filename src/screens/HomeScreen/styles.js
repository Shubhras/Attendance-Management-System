import { StyleSheet } from 'react-native';
import { scale } from 'react-native-size-matters';
import { widthPercentageToDP as wp } from 'react-native-responsive-screen';
import {
  FONT_SIZE_XS,
  FONT_SIZE_XXS,
  POPPINS_MEDIUM,
  POPPINS_SEMIBOLD,
  SCREEN_WIDTH,
  STANDARD_BORDER_RADIUS,
  STANDARD_CATEGORY_IMAGE_WRAPPER_SIZE,
  STANDARD_FLEX,
  STANDARD_SPACING,
  STANDARD_TEXT_INPUT_HEIGHT,
} from '../../config/Constants';
import { Colors } from '../../config/Colors';

// Exporting style
export default StyleSheet.create({
  mainWrapper: {
    flex: STANDARD_FLEX,
  },
  cardContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    flexWrap: 'wrap',
    justifyContent: 'space-between',
    paddingHorizontal: scale(16),
    rowGap: scale(16), // for vertical spacing between rows (React Native 0.71+)
  },
  titleView:{
    marginHorizontal:SCREEN_WIDTH*0.08,
    marginTop:SCREEN_WIDTH * 0.06,
  },
  title:{
    fontFamily:POPPINS_MEDIUM,
    fontSize:FONT_SIZE_XS
  }
});
